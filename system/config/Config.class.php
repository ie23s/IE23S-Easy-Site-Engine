<?PHP

namespace ie23s\shop\system\config;

use Dotenv\Dotenv;
use ie23s\shop\system\Component;
use ie23s\shop\system\System;

/**
 * Configuration system component
 */
class Config extends Component
{
    private Dotenv $dotenv;

    //Loading configuration file .config
    public function __construct(System $system, string $name = '.config')
    {
        parent::__construct($system);
        $this->dotenv = Dotenv::createImmutable(__SHOP_DIR__,
            $name);
    }

    //Checking config file
    public function load()
    {
        $this->dotenv->load();
        $this->dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS']);
        $this->dotenv->required(['THEME']);
    }
}
