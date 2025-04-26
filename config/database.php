<?php
// config/database.php

class Database {
    private static $host = 'localhost';
    private static $dbname = 'kos_kosan'; 
    private static $username = 'root';
    private static $password = '';
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname;
            self::$connection = new PDO($dsn, self::$username, self::$password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$connection;
    }
}
