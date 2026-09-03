<?php

declare(strict_types=1);

namespace App\Models;

class Category extends Model
{
    protected string $table = 'categorias';


    /**
     * Obtiene todas las categorías.
     *
     * Incluye la cantidad de artículos
     * asociados a cada categoría.
     */
    public function getAllCategories(): array
    {
        $sql = "
            SELECT
                c.id,
                c.nombre,
                c.slug,
                c.descripcion,
                COUNT(a.id) AS total_articulos
            FROM categorias c
            LEFT JOIN articulos a
                ON a.categoria_id = c.id
            GROUP BY
                c.id,
                c.nombre,
                c.slug,
                c.descripcion
            ORDER BY
                c.nombre ASC
        ";

        $statement =
            $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Obtiene la cantidad total de categorías.
     */
    public function countAllCategories(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM categorias
        ";

        $statement =
            $this->db->query($sql);

        return (int) $statement->fetchColumn();
    }


    /**
     * Obtiene una categoría mediante su ID.
     */
    public function getCategoryById(
        int $id
    ): array|false {
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

        $statement =
            $this->db->prepare($sql);

        $statement->execute([
            'id' => $id
        ]);

        return $statement->fetch();
    }


    /**
     * Obtiene una categoría mediante su slug.
     */
    public function getCategoryBySlug(
        string $slug
    ): array|false {
        $sql = "
            SELECT
                *
            FROM categorias
            WHERE slug = :slug
            LIMIT 1
        ";

        $statement =
            $this->db->prepare($sql);

        $statement->execute([
            'slug' => $slug
        ]);

        return $statement->fetch();
    }


    /**
     * Obtiene todos los artículos publicados
     * pertenecientes a una categoría.
     */
    public function getArticlesByCategory(
        string $slug
    ): array {
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

            ORDER BY
                a.created_at DESC
        ";

        $statement =
            $this->db->prepare($sql);

        $statement->execute([
            'slug' => $slug
        ]);

        return $statement->fetchAll();
    }


    /**
     * Obtiene la cantidad de artículos
     * asociados a una categoría.
     */
    public function countArticles(
        int $categoryId
    ): int {
        $sql = "
            SELECT
                COUNT(*) AS total
            FROM articulos
            WHERE categoria_id = :categoria_id
        ";

        $statement =
            $this->db->prepare($sql);

        $statement->execute([
            'categoria_id' => $categoryId
        ]);

        return (int) $statement
            ->fetchColumn();
    }


    /**
     * Crea una nueva categoría.
     */
    public function create(
        array $data
    ): bool {
        $sql = "
            INSERT INTO categorias
            (
                nombre,
                slug,
                descripcion
            )
            VALUES
            (
                :nombre,
                :slug,
                :descripcion
            )
        ";

        $statement =
            $this->db->prepare($sql);

        return $statement->execute([
            'nombre' =>
                $data['nombre'],

            'slug' =>
                $data['slug'],

            'descripcion' =>
                $data['descripcion']
        ]);
    }


    /**
     * Actualiza una categoría existente.
     */
    public function update(
        int $id,
        array $data
    ): bool {
        $sql = "
            UPDATE categorias
            SET
                nombre = :nombre,
                slug = :slug,
                descripcion = :descripcion
            WHERE id = :id
        ";

        $statement =
            $this->db->prepare($sql);

        return $statement->execute([
            'id' =>
                $id,

            'nombre' =>
                $data['nombre'],

            'slug' =>
                $data['slug'],

            'descripcion' =>
                $data['descripcion']
        ]);
    }


    /**
     * Elimina una categoría.
     */
    public function delete(
        int $id
    ): bool {
        $sql = "
            DELETE FROM categorias
            WHERE id = :id
        ";

        $statement =
            $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id
        ]);
    }


    /**
     * Comprueba si un slug ya existe.
     */
    public function slugExists(
        string $slug,
        ?int $excludeId = null
    ): bool {
        $sql = "
            SELECT
                id
            FROM categorias
            WHERE slug = :slug
        ";

        $params = [
            'slug' => $slug
        ];

        if ($excludeId !== null) {
            $sql .= "
                AND id != :id
            ";

            $params['id'] =
                $excludeId;
        }

        $sql .= "
            LIMIT 1
        ";

        $statement =
            $this->db->prepare($sql);

        $statement->execute(
            $params
        );

        return
            $statement->fetch()
            !== false;
    }


    /**
     * Genera un slug único.
     */
    public function generateUniqueSlug(
        string $baseSlug,
        ?int $excludeId = null
    ): string {
        $slug =
            $baseSlug;

        $counter =
            2;

        while (
            $this->slugExists(
                $slug,
                $excludeId
            )
        ) {
            $slug =
                $baseSlug
                . '-'
                . $counter;

            $counter++;
        }

        return $slug;
    }
}