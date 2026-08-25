<?php

declare(strict_types=1);

namespace App\Models;

class Category extends Model
{
    protected string $table = 'categorias';


    /**
     * Obtiene todas las categorías.
     */
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


    /**
     * Obtiene una categoría mediante su ID.
     */
    public function getCategoryById(int $id): array|false
    {
        $sql = "
            SELECT
                id,
                nombre,
                slug,
                descripcion
            FROM categorias
            WHERE id = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch();
    }


    /**
     * Obtiene una categoría mediante su slug.
     */
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


    /**
     * Obtiene todos los artículos publicados
     * pertenecientes a una categoría.
     */
    public function getArticlesByCategory(string $slug): array
    {
        $sql = "
            SELECT

                a.id,
                a.titulo,
                a.slug,
                a.resumen,
                a.imagen,
                a.created_at,

                CONCAT(
                    u.nombre,
                    ' ',
                    u.apellido
                ) AS autor,

                c.nombre AS categoria,
                c.slug AS categoria_slug

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