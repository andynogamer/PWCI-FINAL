<?php
class Database {

    private static $host = "localhost";
    private static $db = "infografia_mundiales";
    private static $user = "root";
    private static $pass = "";
    private static $port = "3307"; 

    public static function connect() {
        try {
            $dsn = "mysql:host=" . self::$host . 
                   ";port=" . self::$port . 
                   ";dbname=" . self::$db . 
                   ";charset=utf8mb4";

            $pdo = new PDO($dsn, self::$user, self::$pass);

            
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;

        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }
}