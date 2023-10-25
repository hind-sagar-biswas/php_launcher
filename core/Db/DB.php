<?php

namespace Core\Db;

class DB
{

    public static function mysqli(): \mysqli|null
    {
        $dbhost = DB_HOST;
        $dbname = DB_NAME;
        $dbuser = DB_USER;
        $dbpass = DB_PASS;
        $dbport = DB_PORT;
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            $mysqli = new \mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport);
            $mysqli->set_charset("utf8mb4");
            return $mysqli;
        } catch (\Exception $e) {
            dd("DB CONNECTION ERROR: " . $e->getMessage());
            return null;
        }
    }

    public static function pdo(int $db_number = 1): \PDO|null
    {
        $dbhost = DB_HOST;
        $dbname = DB_NAME;
        $dbuser = DB_USER;
        $dbpass = DB_PASS;
        $dbport = DB_PORT;
        try {
            $pdo = new \PDO("mysql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (\PDOException $e) {
            dd('DB CONNECTION ERROR: ' . $e->getMessage());
            return null;
        }
    }
}
