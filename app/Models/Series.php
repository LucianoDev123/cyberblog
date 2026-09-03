<?php

declare(strict_types=1);

namespace App\Models;

class Series extends Model
{
    protected string $table = 'series';


    /**
     * Obtiene todas las series.
     */
    public function getAllSeries(): array
    {
        $sql = "
            SELECT
                s.id,
                s.titulo,
                s.slug,
                s.descripcion,
                s.imagen,
                s.estado,
                s.created_at,
                s.updated_at,

                COUNT(a.id) AS total_articulos

            FROM series s

            LEFT JOIN articulos a
                ON a.serie_id = s.id

            GROUP BY
                s.id,
                s.titulo,
                s.slug,
                s.descripcion,
                s.imagen,
                s.estado,
                s.created_at,
                s.updated_at

            ORDER BY
                s.created_at DESC
        ";

        $statement =
            $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Obtiene la cantidad total de series.
     */
    public function countAllSeries(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM series
        ";

        $statement =
            $this->db->query($sql);

        return (int) $statement->fetchColumn();
    }


    /**
     * Obtiene únicamente las series publicadas.
     */
    public function getPublishedSeries(): array
    {
        $sql = "
            SELECT
                s.id,
                s.titulo,
                s.slug,
                s.descripcion,
                s.imagen,
                s.estado,
                s.created_at,
                s.updated_at,

                COUNT(a.id) AS total_articulos

            FROM series s

            LEFT JOIN articulos a
                ON a.serie_id = s.id
                AND a.estado = 'publicado'

            WHERE s.estado = 'publicada'

            GROUP BY
                s.id,
                s.titulo,
                s.slug,
                s.descripcion,
                s.imagen,
                s.estado,
                s.created_at,
                s.updated_at

            ORDER BY
                s.created_at DESC
        ";

        $statement =
            $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Busca series publicadas.
     *
     * Este método se conserva para compatibilidad
     * con el modelo actual.
     */
    public function searchPublishedSeries(
        string $search
    ): array {
        $sql = "
            SELECT
                s.id,
                s.titulo,
                s.slug,
                s.descripcion,
                s.imagen,
                s.estado,
                s.created_at,
                s.updated_at,

                COUNT(a.id) AS total_articulos

            FROM series s

            LEFT JOIN articulos a
                ON a.serie_id = s.id
                AND a.estado = 'publicado'

            WHERE s.estado = 'publicada'

            AND (
                s.titulo LIKE :search_title
                OR s.descripcion LIKE :search_description
            )

            GROUP BY
                s.id,
                s.titulo,
                s.slug,
                s.descripcion,
                s.imagen,
                s.estado,
                s.created_at,
                s.updated_at

            ORDER BY
                s.created_at DESC
        ";

        $searchTerm =
            '%'
            . $search
            . '%';

        $statement =
            $this->db->prepare($sql);

        $statement->execute([
            'search_title' =>
                $searchTerm,

            'search_description' =>
                $searchTerm
        ]);

        return $statement->fetchAll();
    }


    /**
     * Obtiene una serie mediante su ID.
     */
    public function getSeriesById(
        int $id
    ): array|false {
        $sql = "
            SELECT
                *
            FROM series
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
     * Obtiene una serie mediante su slug.
     */
    public function getSeriesBySlug(
        string $slug
    ): array|false {
        $sql = "
            SELECT
                *
            FROM series
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
     * Obtiene los artículos publicados asociados a una serie.
     */
    public function getPublishedArticlesBySeriesId(
        int $seriesId
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

            WHERE a.serie_id = :serie_id

            AND a.estado = 'publicado'

            ORDER BY
                a.created_at ASC
        ";

        $statement =
            $this->db->prepare($sql);

        $statement->execute([
            'serie_id' => $seriesId
        ]);

        return $statement->fetchAll();
    }


    /**
     * Crea una nueva serie.
     */
    public function create(
        array $data
    ): bool {
        $sql = "
            INSERT INTO series
            (
                titulo,
                slug,
                descripcion,
                imagen,
                estado
            )
            VALUES
            (
                :titulo,
                :slug,
                :descripcion,
                :imagen,
                :estado
            )
        ";

        $statement =
            $this->db->prepare($sql);

        return $statement->execute([
            'titulo' =>
                $data['titulo'],

            'slug' =>
                $data['slug'],

            'descripcion' =>
                $data['descripcion'],

            'imagen' =>
                $data['imagen'],

            'estado' =>
                $data['estado']
        ]);
    }


    /**
     * Actualiza una serie existente.
     */
    public function update(
        int $id,
        array $data
    ): bool {
        $sql = "
            UPDATE series

            SET
                titulo = :titulo,
                slug = :slug,
                descripcion = :descripcion,
                imagen = :imagen,
                estado = :estado

            WHERE id = :id
        ";

        $statement =
            $this->db->prepare($sql);

        return $statement->execute([
            'id' =>
                $id,

            'titulo' =>
                $data['titulo'],

            'slug' =>
                $data['slug'],

            'descripcion' =>
                $data['descripcion'],

            'imagen' =>
                $data['imagen'],

            'estado' =>
                $data['estado']
        ]);
    }


    /**
     * Elimina una serie.
     */
    public function delete(
        int $id
    ): bool {
        $sql = "
            DELETE FROM series
            WHERE id = :id
        ";

        $statement =
            $this->db->prepare($sql);

        return $statement->execute([
            'id' =>
                $id
        ]);
    }


    /**
     * Genera un slug único para una serie.
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


    /**
     * Comprueba si un slug ya existe.
     */
    private function slugExists(
        string $slug,
        ?int $excludeId = null
    ): bool {
        $sql = "
            SELECT id
            FROM series
            WHERE slug = :slug
        ";

        $params = [
            'slug' =>
                $slug
        ];

        if (
            $excludeId !== null
        ) {
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
}