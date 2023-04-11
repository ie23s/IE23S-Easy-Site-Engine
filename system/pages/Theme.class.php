<?php

namespace ie23s\shop\system\pages;

use Smarty;
use SmartyException;

class Theme
{
    private array $blocks = array();
    private array $objects = [];

    private string $theme;
    private Smarty $smarty;

    public function __construct()
    {

        $this->theme = $_ENV['THEME'] ?? 'default';
        $this->smarty = new Smarty();

        $this->smarty->setTemplateDir(__SHOP_DIR__ . 'templates/' . $this->theme);

        $this->smarty->caching = false; //OFF Cache
    }

    /**
     * Returns ready theme
     *
     * @param string $name - name of tpl file
     * @return String
     * @throws SmartyException
     */
    public function getTpl(string $name): string
    {
        $this->smarty->assign($this->blocks);
        $this->smarty->assign($this->objects);

        return $this->smarty->fetch("{$name}.tpl");
    }

    /**
     * @return Smarty
     */
    public function getSmarty(): Smarty
    {
        return $this->smarty;
    }

    /**
     * @param string $name
     * @param string $block
     */
    public function addBlock(string $name, string $block)
    {
        $this->blocks[$name] = $block;
    }

    public function addObject(string $name, $object)
    {
        $this->objects[$name] = $object;
    }

    public function getThemeName(): string
    {
        return $this->theme;
    }

}