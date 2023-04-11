<?php

namespace ie23s\shop\system\auth\user;

use Exception;
use ie23s\shop\system\auth\Auth;
use ie23s\shop\system\database\MySQLMod;
use ie23s\shop\system\System;
use Simplon\Mysql\Mysql;
use Simplon\Mysql\MysqlException;

class Session
{
    private Mysql $db;

    public function __construct(System $system)
    {
        /** @var MySQLMod $db */
        $db = $system->getComponent('database');
        $this->db = $db->getConn();
    }

    /**
     * get access token from header
     * */
    private static function getBearerToken()
    {
        $headers = self::getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * Get header Authorization
     * */
    private static function getAuthorizationHeader(): ?string
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * @throws Exception
     */
    public function generateSession($user_id, $expire = 3600): array
    {
        // This token is used by forms to prevent cross site forgery attempts
        if (!isset($_SESSION['nonce']))
            $_SESSION['nonce'] = md5(microtime(true));

        if (!isset($_SESSION['userAgent']))
            $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];

        $_SESSION['server_id'] = bin2hex(random_bytes(32));

        $_SESSION['EXPIRES'] = time() + $expire;


        session_regenerate_id();

        // Grab current session ID and close both sessions to allow other scripts to use them
        $newSession = session_id();

        session_write_close();

        // Set session ID to the new one, and start it back up again
        session_id($newSession);
        session_start();

        $this->writeSQLData($newSession, $_SESSION['server_id'], $user_id, $expire);
        return ['session_id' => $newSession, 'server_id' => $_SESSION['server_id']];
    }

    /**
     * @throws MysqlException
     */
    private function writeSQLData($session_id, $server_id, $user_id, $expire)
    {
        $time = time();
        if ($expire != -1) {
            $expire = $time + $expire;
        } else {
            $expire = $time + 31536000;
        }
        $this->db->insert('sessions', ['session_id' => $session_id, 'server_id' => $server_id,
            'user_id' => $user_id, 'login_time' => Auth::timeToDate($time),
            'expire_time' => Auth::timeToDate($expire)]);
    }

    /**
     * @throws MysqlException
     */
    public function checkSession(): int
    {
        session_start();
        $this->updateExpired();
        try {
            if (!isset($_SESSION['server_id']))
                throw new Exception('No session started.');

            if ($_SESSION['userAgent'] != $_SERVER['HTTP_USER_AGENT'])
                throw new Exception('Useragent mixmatch (possible session hijacking attempt).');

            $user_id = $this->db->fetchColumn('SELECT `user_id` FROM `sessions`
                                    WHERE session_id = :SESSION_ID AND
                                          expired = false AND
                                          server_id = :SERVER_ID',
                ['SESSION_ID' => session_id(), 'SERVER_ID' => $_SESSION['server_id']]);

            if ($user_id == null)
                throw new Exception('User not found');
            /*
                        if ($_SESSION['OBSOLETE'])
                            $this->db->update('sessions', ['session_id' => session_id()],
                                ['expire_time' => Auth::timeToDate(time() + 3600)]);*/

            return $user_id;
        } catch (Exception $e) {
            return -1;
        }
    }

    /**
     * @throws MysqlException
     */
    private function updateExpired()
    {
        $time = Auth::timeToDate(time());
        $this->db->executeSql("UPDATE sessions SET expired = true
                WHERE expired = false AND expire_time <= '{$time}'");
    }

    public function getSessionId(): string
    {
        return session_id();
    }
}