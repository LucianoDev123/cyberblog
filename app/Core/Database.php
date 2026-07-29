<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;

    private PDO $connection;

    private function __construct()
    {
        $config = require __DIR__ . '/../Config/database.php';

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $config['host'],
            $config['dbname'],
            $config['charset']
        );

        try {

            $this->connection = new PDO(
                $dsn,
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

        } catch (PDOException $e) {

            die('Error de conexión: ' . $e->getMessage());

        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {

            self::$instance = new self();

        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}