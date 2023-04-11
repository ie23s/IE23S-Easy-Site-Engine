<?php

namespace ie23s\shop\system\database\PDO;

use PDO;
use PDOException;

class MySQLPDO {
    private $db;

    public function __construct($host, $user, $pass, $dbname)
    {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

        try {
            $this->db = new PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $values = array_values($data);
        $placeholders = implode(",", array_fill(0, count($values), "?"));

        $sql = "INSERT INTO $table (" . implode(",", $keys) . ") VALUES ($placeholders)";
        $this->query($sql, $values);
    }

    public function update($table, $data, $where = "")
    {
        $set_values = [];
        foreach ($data as $key => $value) {
            $set_values[] = "$key = ?";
        }
        $set_values_str = implode(",", $set_values);

        $sql = "UPDATE $table SET $set_values_str";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }

        $this->query($sql, array_values($data));
    }

    public function delete($table, $where)
    {
        $sql = "DELETE FROM $table WHERE $where";
        $this->query($sql);
    }

    public function fetchRow($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchColumn($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function fetchArray($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }
}
