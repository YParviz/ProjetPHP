<?php

namespace Util;

use PDO;

class Database
{
    private static PDO $PDO;
    private static function connect(): void
    {
        $host = $_ENV['DB_HOST'];
        $name = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASSWORD'];
        $port = $_ENV['DB_PORT'];
        $charset = $_ENV['DB_CHARSET'];
        Database::$PDO = new PDO("mysql:host=$host;port=$port;dbname=$name;charset=$charset", $user, $pass);
    }

    public static function getPDO(): PDO
    {
        if (!isset(Database::$PDO)) {
            self::connect();
        }
        return Database::$PDO;
    }
}