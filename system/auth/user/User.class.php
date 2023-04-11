<?php

namespace ie23s\shop\system\auth\user;

use ie23s\shop\system\auth\Auth;
use ie23s\shop\system\auth\user\group\Group;
use ie23s\shop\system\auth\user\group\GroupModel;
use Simplon\Mysql\MysqlException;

class User extends UserModel
{
    private Group $group;
    private GroupModel $groupModel;

    public function __construct(int    $id, string $email, string $first_name, string $last_name,
                                string $salt, string $hash, int $group, Auth $auth)
    {
        parent::__construct($id, $email, $first_name, $last_name, $salt, $hash, $group);
        $this->group = $auth->getGroup();
        $this->groupModel = $this->group->getGroupByID($group);
    }

    public function verifyPassword($password): bool
    {
        return password_verify($this->getSalt() . $_ENV['PEPPER'] . $password, $this->getHash());
    }

    /**
     * @throws MysqlException
     */
    public function hasPermission($perm): bool
    {
        return $this->group->hasPermission($this->groupModel->getParents(), $perm);
    }
}