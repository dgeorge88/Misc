<?php

// Set DB Parametres
class Database{

    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "logreg";
    private $conn;

    //DB Connect
    public function connect () {
        $this->conn = null;

        try{
            $this->conn = new PDO(

            'mmysql:host=' . $this->host 
            . ';dbname= ' . $this->dbname, 
            $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        } catch (PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
        }

        return $this->conn;
    }
}

