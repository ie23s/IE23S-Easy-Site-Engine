<?php

namespace ie23s\shop\system\auth\pages;

use ie23s\shop\system\pages\Page;

class RegisterPage extends Page
{

    public function request(array $request): string
    {
        $theme = $this->getPages()->getTheme();

        if (isset($request[1]) && $request[1] == 'tonly') {
            $this->needTheme(false);
        }
        return $theme->getTpl('auth/register');
    }
}