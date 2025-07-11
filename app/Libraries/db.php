<?php
// lib/db.php – Koneksi database untuk AlwafaHub

class DB {
    private static $host = 'localhost';
    private static $dbname = 'alwafahub';
    private static $username = 'root';
    private static $password = '';
    private static $charset = 'utf8mb4';

    public static function connect() {
        $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset;

        try {
            $pdo = new PDO($dsn, self::$username, self::$password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            return $pdo;
        } catch (PDOException $e) {
            die("❌ Gagal terhubung ke database: " . $e->getMessage());
        }
    }
}
