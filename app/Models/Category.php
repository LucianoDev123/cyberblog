<?php

declare(strict_types=1);

namespace App\Models;

class Category extends Model
{
    protected string $table = 'categorias';

    public function getAllCategories(): array
    {
        $sql = "
            SELECT
                id,
                nombre,
                slug,
                descripcion
            FROM categorias
            ORDER BY nombre ASC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    public function getCategoryBySlug(string $slug): array|false
    {
        $sql = "
            SELECT
                *
            FROM categorias
            WHERE slug = :slug
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'slug' => $slug
        ]);

        return $stmt->fetch();
    }

    public function getArticlesByCategory(string $slug): array
    {
        $sql = "
            SELECT

                a.id,
                a.titulo,
                a.slug,
                a.resumen,
                a.created_at,

                CONCAT(u.nombre,' ',u.apellido) AS autor,

                c.nombre AS categoria

            FROM articulos a

            INNER JOIN usuarios u
                ON a.usuario_id = u.id

            INNER JOIN categorias c
                ON a.categoria_id = c.id

            WHERE
                c.slug = :slug
                AND a.estado = 'publicado'

            ORDER BY a.created_at DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'slug' => $slug
        ]);

        return $stmt->fetchAll();
    }
}