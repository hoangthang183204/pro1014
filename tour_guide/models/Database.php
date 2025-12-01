<?php
// Tệp: tour_guide/models/Database.php

class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $dbname = 'pro1014';
    
    private $dbh;
    private $stmt;
    private $error;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname . ';charset=utf8';
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function query($sql) {
        $this->stmt = $this->dbh->prepare($sql);
    }

    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute() {
        try {
            return $this->stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function resultSet() {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single() {
        $this->execute();
        return $this->stmt->fetch();
    }

    public function rowCount() {
        return $this->stmt->rowCount();
    }

    public function selectOne($query, $params = []) {
        try {
            $this->query($query);
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $this->bind($key + 1, $value);
                }
            }
            return $this->single();
        } catch (Exception $e) {
            return false;
        }
    }

    public function executeQuery($query, $params = []) {
        try {
            $this->query($query);
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $this->bind($key + 1, $value);
                }
            }
            return $this->execute();
        } catch (Exception $e) {
            return false;
        }
    }
     public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }
}
?>