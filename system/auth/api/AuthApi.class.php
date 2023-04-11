<?php

namespace ie23s\shop\system\auth\api;

use Exception;
use ie23s\shop\engine\utils\breadcrumbs\api\ApiAbstract;
use ie23s\shop\system\auth\Auth;

class AuthApi extends ApiAbstract
{

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->withCode(400);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function post(): string
    {
        /** @var Auth $auth */
        $auth = $this->getSystem()->getComponent('auth');

        if ($auth->getCurrentUserID() != -1)
            return $this->withCode(403);

        //Check fields
        if (!$this->hasRequest('email') || !$this->hasRequest('password'))
            return $this->withCode(401, 'You should fill all fields!');

        //Check email
        if (!filter_var($this->getRequest('email'), FILTER_VALIDATE_EMAIL)) {
            return $this->withCode(401, 'Invalid email format!');
        }

        //Auth user and write session
        $session = $auth->authUser($this->getRequest('email'), $this->getRequest('password'),
            $this->hasRequest('remember'));

        if ($session == null) {
            return $this->withCode(401, 'Email or password is wrong!');
        }

        return $this->withData(200, $session);
    }

    /**
     * @return string
     */
    public function put(): string
    {
        return $this->withCode(400);
    }

    /**
     * @return string
     */
    public function delete(): string
    {
        return $this->withCode(400);
    }
}