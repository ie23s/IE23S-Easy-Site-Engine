<?PHP

namespace ie23s\shop\system\database;

use Exception;
use ie23s\shop\system\Component;
use ie23s\shop\system\System;
use PDO;
use Simplon\Mysql;

/**
 * MySQL PDO system component
 */
class MySQLMod extends Component
{
    private PDO $pdoConnection;
    private Mysql\Mysql $dbConnection;

    /**
     * MysqlConnection init
     * @throws Exception
     */
    public function __construct(System $system)
    {
        parent::__construct($system);
        $pdo = new Mysql\PDOConnector(
            $_ENV['DB_HOST'], // server
            $_ENV['DB_USER'],      // user
            $_ENV['DB_PASS'],      // password
            $_ENV['DB_NAME']   // database
        );
        $this->pdoConnection = $pdo->connect('utf8', []); // charset, ops
        // $pdoConnection->setAttribute($attribute, $value);
    }

    public static function testConnection($server, $user, $password, $db, &$exception): bool
    {
        $pdo = new Mysql\PDOConnector(
            $server, // server
            $user,      // user
            $password,      // password
            $db   // database
        );
        try {
            $pdo->connect('utf8', []);
        } catch (Exception $e) {
            $exception = $e;
            return false;
        }
        return true;
    }
    //Database info init

    /**
     * @return Mysql\Mysql
     */
    public function getConn(): Mysql\Mysql
    {
        return $this->dbConnection;
    }

    /**
     * Mysql connection
     */
    public function load()
    {
        $this->dbConnection =
            new Mysql\Mysql($this->pdoConnection);
    }

    public function unload()
    {
        $this->dbConnection->close();
    }
}
