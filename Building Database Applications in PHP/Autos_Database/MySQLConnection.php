<?php

class MySQLConnection
{
    private PDO $pdo;
    public function __construct() {
        try{
            $this->pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc','fred', 'zap');
            // See the "errors" folder for details...
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (Exception $e){
            echo "Connection Failed: " . $e->getMessage();
        }
    }

    // to access the pdo
    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}

