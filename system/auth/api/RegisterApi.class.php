<?php

namespace ie23s\shop\system\auth\api;

use Exception;
use ie23s\shop\engine\utils\breadcrumbs\api\ApiAbstract;
use ie23s\shop\system\auth\Auth;
use ie23s\shop\system\auth\user\UserModel;

class RegisterApi extends ApiAbstract
{

    public function get(): string
    {
        return $this->withCode(400);
    }

    /**
     * @throws Exception
     */
    public function post(): string
    {
        /** @var Auth $auth */
        $auth = $this->getSystem()->getComponent('auth');

        if ($auth->getCurrentUserID() != -1)
            return $this->withCode(403);

        //Check fields
        if (!$this->hasRequest('email') || !$this->hasRequest('first_name') ||
            !$this->hasRequest('last_name') || !$this->hasRequest('password'))
            return $this->withCode(401, 'You should fill all fields!');


        //Check email
        if (!filter_var($this->getRequest('email'), FILTER_VALIDATE_EMAIL)) {
            return $this->withCode(401, 'Invalid email format!');
        }
        if ($auth->getUserByEmail($this->getRequest('email')) != null)
            return $this->withCode(401, 'This email is already taken!');

        //Check password
        if (mb_strlen($this->getRequest('password')) < 6)
            return $this->withCode(401, 'Your password too short!');

        $hash = Auth::hashPassword($this->getRequest('password'));
        $user = new UserModel(0, trim(strtolower($this->getRequest('email'))), $this->getRequest('first_name'),
            $this->getRequest('last_name'), $hash['salt'], $hash['hash'], 1);
        try {
            $this->getSystem()->getComponent('mail')->sendMail(
                ['name' => "{$this->getRequest('first_name')} {$this->getRequest('last_name')}",
                    'email' => $this->getRequest('email')],
                'Welcome!', "Hi, {$this->getRequest('first_name')} {$this->getRequest('last_name')}!<br>Congratulations! You`re Pidor!");
        } catch (Exception $e) {
        }
        return $this->withData(200, $auth->createUser($user));
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