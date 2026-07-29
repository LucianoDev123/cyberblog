<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

abstract class Model
{
    protected PDO $db;

    protected string $table;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";

        $statement = $this->db->query($sql);

        return $statement->fetchAll();
    }

    public function find(int $id): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'id' => $id
        ]);

        return $statement->fetch();
    }

    public function findBySlug(string $slug): array|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE slug = :slug LIMIT 1";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'slug' => $slug
        ]);

        return $statement->fetch();
    }
}