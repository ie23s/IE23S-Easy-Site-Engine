<?php

namespace ie23s\shop\system\auth\pages;

use ie23s\shop\system\pages\Page;
use Simplon\Mysql\MysqlException;

class LogoutPage extends Page
{

    /**
     * @throws MysqlException
     */
    public function request(array $request): string
    {
        $auth = $this->getSystem()->getAuth();
        if ($auth->getCurrentUserID() != -1) {
            $auth->logout();
        }
        header("Location: /");
        return '';
    }
}