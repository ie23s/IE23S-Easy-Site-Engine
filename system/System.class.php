<?PHP

namespace ie23s\shop\system;

//Component interface loader
use Exception;
use ie23s\shop\admin\Admin;
//use ie23s\shop\engine\utils\breadcrumbs\Engine;
use ie23s\shop\system\api\Api;
use ie23s\shop\system\auth\Auth;
use ie23s\shop\system\files\Files;
use ie23s\shop\system\install\Install;
use ie23s\shop\system\lang\Lang;
use ie23s\shop\system\mail\Mail;
use ie23s\shop\system\pages\Pages;

require_once __SHOP_DIR__ . "system/api/Codes.php";

require_once __SHOP_DIR__ . "system/component.php";
//Config component loader
require_once __SHOP_DIR__ . "system/config/Config.class.php";
//MySQL component loader
require_once __SHOP_DIR__ . "system/lang/Lang.class.php";
//MySQL component loader
require_once __SHOP_DIR__ . "system/database/MySQLMod.php";
//MySQL component loader
require_once __SHOP_DIR__ . "system/pages/Pages.class.php";
//MySQL component loader
require_once __SHOP_DIR__ . "system/api/Api.class.php";
//MySQL component loader
//require_once __SHOP_DIR__ . "engine/Engine.class.php";
//MySQL component loader
require_once __SHOP_DIR__ . "admin/Admin.class.php";
//MySQL component loader
require_once __SHOP_DIR__ . "system/auth/Auth.class.php";
//MySQL component loader
require_once __SHOP_DIR__ . "system/mail/Mail.class.php";
//MySQL component loader
require_once __SHOP_DIR__ . "system/files/Files.class.php";

//Install script
//This file will be deleted
@include_once __SHOP_DIR__ . "system/install/Install.class.php";


/**
 * This class loads all system components
 */
class System
{
    private array $components = array();

    //Initialization components

    /**
     * @throws Exception
     */
    public function init()
    {

        //Init Config
        $config = new config\Config($this);
        try {
            $config->load();
        } catch (Exception $e) {
        }
        $config = null;
        //Init Theme
        $this->components["pages"] = new Pages($this);
        //Init DB
        $this->components["database"] = new database\MySQLMod($this);
        //Init Auth
        $this->components["auth"] = new Auth($this);

        //Init Lang
        $this->components["lang"] = new Lang($this);

        //Init API
        $this->components["api"] = new Api($this);

        //Init Shop engine
        //$this->components["sEngine"] = new Engine($this);
        //Init Shop engine
        $this->components["admin"] = new Admin($this);
        //Init Shop engine
        $this->components["mail"] = new Mail($this);
        //Init Shop engine
        $this->components["files"] = new Files($this);
    }

    public function load()
    {
        //Load DB
        $this->components["database"]->load();
        //Load Lang
        $this->components["lang"]->load();
        //Load Theme

        $this->components["auth"]->load();
        //LAST!
        $this->components["pages"]->load();
        $this->components["auth"]->loadPages();
        $this->components["api"]->load();
        //Init Shop engine
        $this->components["admin"]->load();
        $this->components["files"]->load();


    }

    /**
     * @throws Exception
     */
    public function install()
    {
        //Init Theme
        $this->components["pages"] = new Pages($this);
        try {
            $this->components["install"] = new Install($this);
            $this->components["install"]->load();
        } catch (Exception $e) {
        }
    }

    public function unload()
    {
        //Unload Theme
        $this->components["pages"]->unload();
    }

    public function getLang(): ?Lang
    {
        /** @var $r Lang */
        $r = $this->getComponent('lang');
        return $r;
    }

    /**
     * @param $component
     * @return ?Component
     */
    public function getComponent($component): ?Component
    {
        return $this->components[$component];
    }

    public function addComponent($name, $component)
    {
        return $this->components[$name] = $component;
    }

    public function getPages(): Pages
    {
        /** @var $r Pages */
        $r = $this->getComponent('pages');
        return $r;
    }
//
//    public function getEngine(): Engine
//    {
//        /** @var $r Engine */
//        $r = $this->getComponent('sEngine');
//        return $r;
//    }

    public function getApi(): Api
    {
        /** @var $r Api */
        $r = $this->getComponent('api');
        return $r;
    }

    public function getAuth(): ?Auth
    {
        /** @var $r Auth */
        $r = $this->getComponent('auth');
        return $r;
    }
}
