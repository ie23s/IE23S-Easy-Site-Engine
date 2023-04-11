<?php

namespace ie23s\shop\admin\pages;

use ie23s\shop\engine\utils\breadcrumbs\Engine;
use ie23s\shop\system\System;
use Simplon\Mysql\MysqlException;

abstract class AdminPage
{
    private System $system;
    private string $name;
    private string $uri;
    private Engine $engine;

    /**
     * @param System $system
     * @param string $name
     * @param string $uri
     * @throws MysqlException
     */
    public function __construct(System $system, string $name, string $uri)
    {
        $this->system = $system;
        $this->name = $system->getLang()->getRow($name);
        $this->uri = $uri;
        $this->engine = $system->getEngine();
    }

    /**
     * @return Engine
     */
    public function getEngine(): Engine
    {
        return $this->engine;
    }

    /**
     * @return System
     */
    public function getSystem(): System
    {
        return $this->system;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    abstract function getPage(): string;


}