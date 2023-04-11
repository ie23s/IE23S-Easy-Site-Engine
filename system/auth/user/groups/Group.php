<?php

namespace ie23s\shop\system\auth\user\group;

require_once __SHOP_DIR__ . '/system/auth/user/groups/GroupModel.php';

use ie23s\shop\system\System;
use Simplon\Mysql\Mysql;
use Simplon\Mysql\MysqlException;

class Group
{
    private Mysql $db;

    public function __construct(System $system)
    {
        $this->db = $system->getComponent('database')->getConn();
    }

    public function getGroupByID(int $id): GroupModel
    {
        $res = $this->db->fetchRow('WITH RECURSIVE groups_extended(id, name, parents)
                                                           AS
                                                           (
                                                               SELECT id, name, CAST(ID AS CHAR(200))
                                                               FROM `groups`
                                                               WHERE parent_id IS NULL
                                                               UNION ALL
                                                               SELECT S.id, S.NAME, CONCAT(M.parents, \',\', S.ID)
                                                               FROM groups_extended M JOIN `groups` S ON
                                                               M.id=S.parent_id
                                                           )
                                        SELECT * FROM groups_extended WHERE id = :id;', ['id' => $id]);
        return new GroupModel($res['id'], $res['name'], $res['parents']);
    }

    /**
     * @throws MysqlException
     */
    public function hasPermission($groups, $perm): bool
    {
        $c = $this->db->fetchColumn('SELECT COUNT(*) FROM `group_permissions` WHERE `group_id` IN (:ids)
                                           AND permission = :perm',
            ['ids' => explode(',', $groups), 'perm' => $perm]);

        return $c > 0;
    }
}