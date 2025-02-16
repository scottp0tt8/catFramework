<?php

class Database
{
    private $pdo;
    private $stmt;

    public function __construct($host, $port, $user, $pass, $dbname = "", $driver = "mysql")
    {
        try {
            $dsn = "$driver:host=$host;port=$port" . ($dbname ? ";dbname=$dbname" : "");
            $this->pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function prepare($query, $params = [])
    {
        $this->stmt = $this->pdo->prepare($query);
        foreach ($params as $key => $value) {
            $this->stmt->bindValue($key, $value);
        }
        return $this;
    }

    public function run()
    {
        $this->stmt->execute();
        return $this->stmt;
    }

    public function fetch()
    {
        return $this->stmt->fetch();
    }

    public function fetchAll()
    {
        return $this->stmt->fetchAll();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function close()
    {
        $this->pdo = null;
    }
}