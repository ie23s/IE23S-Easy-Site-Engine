<?php

namespace ie23s\shop\system\auth\api;

use ie23s\shop\engine\utils\breadcrumbs\api\ApiAbstract;
use ie23s\shop\system\auth\Auth;
use Simplon\Mysql\MysqlException;

class LogoutApi extends ApiAbstract
{

    /**
     * @throws MysqlException
     */
    public function get(): string
    {
        /** @var Auth $auth */
        $auth = $this->getSystem()->getComponent('auth');

        if ($auth->getCurrentUserID() == -1)
            return $this->withCode(403);

        $auth->logout();

        return $this->withCode(200);
    }

    public function post(): string
    {
        return $this->withCode(400);
    }

    public function put(): string
    {
        return $this->withCode(400);
    }

    public function delete(): string
    {
        return $this->withCode(400);
    }
}