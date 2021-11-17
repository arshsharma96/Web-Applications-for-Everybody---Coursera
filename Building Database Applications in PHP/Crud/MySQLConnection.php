<?php

class MySQLConnection
{
    private PDO $pdo;


    public function __construct() {
        try{
            $this->pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc','fred', 'zap');
            // See the "errors" folder for details...
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            $now = new DateTime('now');
           // echo "Connection Failed: " . $e->getMessage();
            error_log($now->format('c') . " Database Not Connected: " . $e, 3, "errorLogDatabase.log");
        }
    }

    // to access the pdo
    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}

