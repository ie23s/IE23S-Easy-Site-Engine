<?PHP

namespace ie23s\shop\system;
/***
 * Interface of system components
 *
 * @author ie23s
 ***/
abstract class Component
{
    protected System $system;

    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * @return void
     */
    public abstract function load();

    /**
     * @return void
     */
    public function unload()
    {
    }

    public final function getSystem(): System
    {
        return $this->system;
    }
}
