<?php

namespace Util;

use PDO;
use PDOException;

class Database
{
    public static function connect(): PDO
    {
        try {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $name = $_ENV['DB_NAME'] ?? 'debatarena';
            $user = $_ENV['DB_USER'] ?? 'thay';
            $pass = $_ENV['DB_PASSWORD'] ?? 'thay';
            $port = $_ENV['DB_PORT'] ?? '5432';

            // Supprimer charset (PostgreSQL ne le supporte pas)
            $dsn = "pgsql:host=$host;port=$port;dbname=$name";

            // Ajout des options de connexion
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            return new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }
}
