<?php

namespace ie23s\shop\system\lang;

use ie23s\shop\system\Component;
use ie23s\shop\system\database\MySQLMod;
use ie23s\shop\system\System;
use Simplon\Mysql\MysqlException;

class Lang extends Component
{
    private string $lang;
    private string $accept;
    private int $lang_id = 0;

    private MySQLMod $mySQL;

    /**
     * @return void
     */
    public function __construct(System $system)
    {
        parent::__construct($system);
        $this->lang = isset($_COOKIE['language']) && !empty($_COOKIE['language']) ? $_COOKIE['language'] : '';
        $this->accept = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    }

    /**
     * @return void
     * @throws MysqlException
     */
    public function load()
    {
        $this->mySQL = $this->getSystem()->getComponent('database');

        if ($this->lang != '') {
            $row = $this->getMySQLLang($this->lang);
            if ($row != null) {
                $this->setLang($row['name']);
                $this->lang_id = $row['id'];
            }
        }
        if ($this->lang_id == 0) {
            $list = explode(',', $this->accept);
            foreach ($list as $lang) {
                $lang = explode(';', $lang);

                $row = $this->getMySQLLang($lang);
                if ($row != null) {
                    $this->setLang($row['name']);
                    $this->lang_id = $row['id'];
                    break;
                }
            }
            if ($this->lang_id == 0) {
                $this->lang_id = 1;
                $this->setLang('en');
            }
        }
    }

    /**
     * MySQL query get lang name and ID
     *
     * @throws MysqlException
     */
    private function getMySQLLang($short): ?array
    {
        return $this->mySQL->getConn()->fetchRow("SELECT `id`, `name`
                                                        FROM `languages`
                                                        WHERE shorts LIKE '%:short%'", ['short' => $short]);
    }

    private function setLang($short)
    {
        $this->lang = $short;
        setcookie("language", $short);
    }

    /**
     * @throws MysqlException
     */
    public function getRow($key): string
    {
        return $this->mySQL->getConn()->fetchColumn("SELECT `value`
                                                        FROM `language`
                                                        WHERE lang_id = :id AND `key` = :key",
            ['id' => $this->lang_id, 'key' => $key]) ?? 'undefined';
    }

    /**
     * @throws MysqlException
     */
    public function s($key): string
    {
        return $this->getRow($key);
    }

    /**
     * @throws MysqlException
     */
    public function remRow($key): string
    {
        return $this->mySQL->getConn()->delete('language',
            ['lang_id' => $this->lang_id, 'key' => $key]);
    }

    public function addRow($key, $value)
    {
        return $this->mySQL->getConn()->insert('language', ['key' => $key, 'value' => $value,
            'lang_id' => $this->lang_id]);
    }

    public function updateRow($key, $value)
    {
        $this->mySQL->getConn()->update('language',
            ['lang_id' => $this->lang_id, 'key' => $key],
            ['value' => $value]);
    }

    public function getAllLang(): array
    {
        return $this->mySQL->getConn()->fetchRowMany("SELECT *
                                                        FROM `language`
                                                        WHERE lang_id = :id",
            ['id' => $this->lang_id]);
    }

    /**
     * @throws MysqlException
     */
    public function getEditableRow($type, $id): ?string
    {
        $row = $this->mySQL->getConn()->fetchColumn("SELECT `value`
                                                        FROM `language_editable`
                                                        WHERE lang_id = :id AND `external_id` = :ex_id
                                                        AND `type` = :type",
            ['id' => $this->lang_id, 'ex_id' => $id, 'type' => $type]);
        return $row == null ? 'undefined' : $row;
    }

    /**
     * @throws MysqlException
     */
    public function addEditableRow($type, $id, array $values): array
    {
        foreach ($values as $i => $value) {
            $values[$i]['external_id'] = $id;
            $values[$i]['type'] = $type;
        }
        return $this->mySQL->getConn()->insertMany("language_editable",
            $values);
    }

    /**
     * @throws MysqlException
     */
    public function deleteEditableRow(string $type, int $id): int
    {
        return $this->mySQL->getConn()->delete("language_editable",
            ['external_id' => $id, 'type' => $type]);
    }

    /**
     * @throws MysqlException
     */
    public function editEditableRow($type, $id, $array)
    {
        foreach ($array as $value) {

            $this->mySQL->getConn()->update("language_editable",
                ['external_id' => $id, 'type' => $type, 'lang_id' => $value['lang_id']],
                ['value' => $value['value']]);
        }
    }

    /**
     * @return int
     */
    public function getLangId(): int
    {
        return $this->lang_id;
    }

}