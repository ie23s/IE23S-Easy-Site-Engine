<?php

namespace ie23s\shop\system\auth;

require_once __SHOP_DIR__ . '/system/auth/user/UserModel.class.php';
require_once __SHOP_DIR__ . '/system/auth/user/User.class.php';
require_once __SHOP_DIR__ . '/system/auth/user/Session.class.php';
require_once __SHOP_DIR__ . '/system/auth/pages/RegisterPage.php';
require_once __SHOP_DIR__ . '/system/auth/pages/LogoutPage.php';
require_once __SHOP_DIR__ . '/system/auth/pages/LoginPage.php';
require_once __SHOP_DIR__ . '/system/auth/api/RegisterApi.class.php';
require_once __SHOP_DIR__ . '/system/auth/api/AuthApi.class.php';
require_once __SHOP_DIR__ . '/system/auth/api/LogoutApi.php';
require_once __SHOP_DIR__ . '/system/auth/user/groups/Group.php';


use Exception;
use ie23s\shop\system\auth\api\AuthApi;
use ie23s\shop\system\auth\api\LogoutApi;
use ie23s\shop\system\auth\api\RegisterApi;
use ie23s\shop\system\auth\pages\LoginPage;
use ie23s\shop\system\auth\pages\LogoutPage;
use ie23s\shop\system\auth\pages\RegisterPage;
use ie23s\shop\system\auth\user\group\Group;
use ie23s\shop\system\auth\user\Session;
use ie23s\shop\system\auth\user\User;
use ie23s\shop\system\auth\user\UserModel;
use ie23s\shop\system\Component;
use Simplon\Mysql\Mysql;
use Simplon\Mysql\MysqlException;

class Auth extends Component
{
    private Mysql $db;
    private Session $session;
    private ?User $user;
    private int $currentUserID;
    private Group $group;

    public function __construct($system)
    {
        parent::__construct($system);

    }

    /**
     * This method make hash of written password
     *
     * @param string $password - user created password
     * @throws Exception
     */
    public static function hashPassword(string $password): array
    {
        $salt = base64_encode(random_bytes(12));

        $hash = password_hash($salt . $_ENV['PEPPER'] . $password, PASSWORD_BCRYPT);
        return ['salt' => $salt, 'hash' => $hash];
    }

    public static function timeToDate($unixTimestamp)
    {
        return date("Y-m-d H:i:s", $unixTimestamp);
    }

    /**
     * @inheritDoc
     * @throws MysqlException
     */
    public function load()
    {
        $this->db = $this->getSystem()->getComponent('database')->getConn();
        $this->session = new Session($this->getSystem());
        $this->currentUserID = $this->session->checkSession();
        $this->group = new Group($this->getSystem());
    }

    public function loadPages()
    {
        new RegisterPage('register', $this->getSystem()->getPages(), 'register');
        new LoginPage('login', $this->getSystem()->getPages(), 'login');
        new LogoutPage('logout', $this->getSystem()->getPages(), 'logout');

        $this->system->getApi()->addPath('register', new RegisterApi($this->system));
        $this->system->getApi()->addPath('auth', new AuthApi($this->system));
        $this->system->getApi()->addPath('logout', new LogoutApi($this->system));


    }

    /**
     * This function creates new user and writes all data to database
     *
     * @param UserModel $user
     * @return array
     * @throws MysqlException
     * @throws Exception
     */
    public function createUser(UserModel $user): array
    {
        $id = $this->db->insert('users', ['email' => $user->getEmail(), 'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(), 'salt' => $user->getSalt(), 'hash' => $user->getHash(),
            'group' => $user->getGroup()]);
        return $this->session->generateSession($id, -1);
    }

    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @throws Exception
     */
    public function authUser($email, $password, $remember = false): ?array
    {
        try {
            $this->user = $this->getUserByEmail($email);
        } catch (MysqlException $e) {
        }
        if ($this->user == null || !$this->user->verifyPassword($password))
            return null;

        return $this->session->generateSession($this->user->getId(), $remember ? -1 : 3600);
    }

    /**
     * @throws MysqlException
     */
    public function getUserByEmail($email): ?User
    {
        $email = trim(strtolower($email));

        $userData = $this->db->fetchRow('SELECT * FROM users WHERE `email` = :email', ['email' => $email]);
        if ($userData == null)
            return null;
        return new User($userData['id'], $userData['email'], $userData['first_name'], $userData['last_name'], $userData['salt'],
            $userData['hash'], $userData['group'], $this);
    }

    /**
     * @throws MysqlException
     */
    public function getCurrentUser(): ?User
    {
        if (!$this->isAuth())
            return new User(0, '', '', '', '', '', 1, $this);
        return $this->getUserByID($this->currentUserID);
    }

    public function isAuth(): bool
    {
        return $this->getCurrentUserID() != -1;
    }

    public function getCurrentUserID(): int
    {
        return $this->currentUserID;
    }

    /**
     * @throws MysqlException
     */
    public function getUserByID(int $id): ?User
    {

        $userData = $this->db->fetchRow('SELECT * FROM users WHERE `id` = :id', ['id' => $id]);
        if ($userData == null)
            return null;
        return new User($userData['id'], $userData['email'], $userData['first_name'], $userData['last_name'], $userData['salt'],
            $userData['hash'], $userData['group'], $this);
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @throws MysqlException
     */
    public function logout()
    {
        $this->db->update('sessions', ['session_id' => $this->session->getSessionId()], ['expired' => true]);
    }


}