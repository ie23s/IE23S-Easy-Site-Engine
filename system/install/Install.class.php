<?php

namespace ie23s\shop\system\install;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\Adapter\ReplacingWriter;
use Dotenv\Repository\RepositoryBuilder;
use Exception;
use ie23s\shop\system\auth\Auth;
use ie23s\shop\system\Component;
use ie23s\shop\system\config\Config;
use ie23s\shop\system\database\MySQLMod;
use ie23s\shop\system\pages\Pages;

class Install extends Component
{
    private ?array $path;

    /**
     * @inheritDoc
     */
    public function load()
    {
        $this->path = $this->getSystem()->getPages()->getPath();

        if ($this->path[0] != 'install') {
            header("Location: /install/");
        } else {
            define("offTimer", true);
            if(isset($this->path[1])) {
                if ($this->path[1] == 'req') {
                    $this->requirements();
                }
                if ($this->path[1] == 'db') {
                    $this->DBConfig();
                }
            } else {
                echo file_get_contents(__SHOP_DIR__ . 'system/install/index.html');

            }
        }
    }

    function createEnvFile(array $env, string $name = '/.config'): bool {
        $content = '';
        foreach ($env as $key => $value) {
            $content .= $key . '=\'' . $value . "'". PHP_EOL;
        }

        $filename = __SHOP_DIR__ . $name;
        if (file_put_contents($filename, $content) !== false) {
            return true;
        } else {
            return false;
        }
    }

    private function requirements() {
        $error_list = array();

        $dirs = ['./',
            './system/',
            './system/install/',
            './uploads/'];

        foreach ($dirs as $dir) {
            if(!is_writeable($dir))
                $error_list[] = "Directory isn't writeable: {$dir}";
        }

        echo json_encode($error_list);
    }
    private function DBConfig() {
        $cfg= array(
            'DB_HOST'=>$_POST['DB_HOST'],
            'DB_USER'=>$_POST['DB_USER'],
            'DB_PASS'=>$_POST['DB_PASS'],
            'DB_NAME'=>$_POST['DB_NAME']);
        $cfg['PEPPER'] = $this->random_str(16);
        $cfg['THEME'] = 'Default';
        /** @var Exception $e */
        $e = '';
        $empty = false;
        foreach ($cfg as $point) {
            if(empty($point)){
                $empty = true;
                break;
            }
        }
        if($empty) {
            echo "Please fill in all the fields!";
        }
        elseif(MySQLMod::testConnection($cfg['DB_HOST'], $cfg['DB_USER'],$cfg['DB_PASS'],$cfg['DB_NAME'],$e)){

            if(!$this->createEnvFile($cfg, '/.config.tmp')) {
                echo "Error while creating config file!";
            }
            try {
                $this->restoreDB();
                echo "OK";
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        } else {
            echo $e->getMessage();
        }
    }

    /**
     * @throws Exception
     */
    private function restoreDB() {

        //Init Config
        $config = new Config($this->getSystem(), '.config.tmp');
        $config->load();
        $config = null;
        //Init DB
        $db = new MySQLMod($this->getSystem());
        $this->getSystem()->addComponent('database', $db);
        $db->load();
        $file = file_get_contents(__SHOP_DIR__.'/system/install/install.sql', true);
        $db->getConn()->executeSql($file);
    }

    /**
     * @throws Exception
     */
    private function userConfig() {
        //Init Config
        $config = new Config($this->getSystem(), '.config.tmp');
        $config->load();
        $config = null;
        //Init DB
        $db = new MySQLMod($this->getSystem());
        $this->getSystem()->addComponent('database', $db);
        //Init Auth
        $auth = new Auth($this->getSystem());
        $this->getSystem()->addComponent('auth', $auth);
        $db->load();
        $auth->load();
    }
    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * This function uses type hints now (PHP 7+ only), but it was originally
     * written for PHP 5 as well.
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     */
    function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}