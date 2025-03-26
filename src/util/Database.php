<?php

namespace Util;

use PDO;

class Database
{
    public static function connect(): PDO
    {
        $host = $_ENV['DB_HOST'];
        $name = $_ENV['DB_NAME'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASSWORD'];
        $port = $_ENV['DB_PORT'];
        $charset = $_ENV['DB_CHARSET'];
        return new PDO("mysql:host=$host;port=$port;dbname=$name;charset=$charset", $user, $pass);
    }
}