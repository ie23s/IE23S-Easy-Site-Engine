<?php

namespace ie23s\shop\system\pages;

use ie23s\shop\system\pages\Page;
use SmartyException;

class MainPage extends Page
{

    /**
     * @throws SmartyException
     */
    public function request(array $request): string
    {
        $theme = $this->getPages()->getTheme();


        return $theme->getTpl('index');
    }
}