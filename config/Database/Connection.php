<?php

namespace config\Database;

require_once __DIR__ . '/../env.php';
loadEnv(__DIR__ . '/../../.env');

use PDO;
use PDOException;

final class Connection
{
    public static function getInstance(): PDO
    {
        $host = getenv("DB_HOST");
        $db   = getenv("DB_NAME");
        $user = getenv("DB_USER");
        $pass = getenv("DB_PASS");

        try {
            $pdo = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8",
                $user,
                $pass
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;

        } catch (PDOException $e) {
            throw new \RuntimeException(
                "Erro de conexão: " . $e->getMessage()
            );
        }
    }
}
