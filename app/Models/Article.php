<?php

declare(strict_types=1);

namespace App\Models;

class Article extends Model
{
    /**
     * Tabla principal utilizada por el modelo.
     */
    protected string $table = 'articulos';


    /**
     * Obtiene todos los artículos publicados.
     */
    public function getPublishedArticles(): array
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
                c.slug AS categoria_slug,

                s.id AS serie_id,
                s.titulo AS serie,
                s.slug AS serie_slug

            FROM articulos a

            INNER JOIN usuarios u
                ON a.usuario_id = u.id

            INNER JOIN categorias c
                ON a.categoria_id = c.id

            LEFT JOIN series s
                ON a.serie_id = s.id

            WHERE a.estado = 'publicado'

            ORDER BY a.created_at DESC
        ";

        $statement = $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Obtiene un artículo publicado mediante su slug.
     */
    public function getArticleBySlug(
        string $slug
    ): array|false {
        $sql = "
            SELECT
                a.*,

                CONCAT(
                    u.nombre,
                    ' ',
                    u.apellido
                ) AS autor,

                c.nombre AS categoria,
                c.slug AS categoria_slug,

                s.id AS serie_id,
                s.titulo AS serie,
                s.slug AS serie_slug

            FROM articulos a

            INNER JOIN usuarios u
                ON a.usuario_id = u.id

            INNER JOIN categorias c
                ON a.categoria_id = c.id

            LEFT JOIN series s
                ON a.serie_id = s.id

            WHERE a.slug = :slug

            AND a.estado = 'publicado'

            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'slug' => $slug
        ]);

        return $statement->fetch();
    }


    /**
     * Obtiene todos los artículos para el panel administrativo.
     */
    public function getAllArticles(): array
    {
        $sql = "
            SELECT
                a.id,
                a.titulo,
                a.imagen,
                a.estado,
                a.created_at,

                CONCAT(
                    u.nombre,
                    ' ',
                    u.apellido
                ) AS autor,

                c.nombre AS categoria,

                s.id AS serie_id,
                s.titulo AS serie

            FROM articulos a

            INNER JOIN usuarios u
                ON a.usuario_id = u.id

            INNER JOIN categorias c
                ON a.categoria_id = c.id

            LEFT JOIN series s
                ON a.serie_id = s.id

            ORDER BY a.created_at DESC
        ";

        $statement = $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Obtiene la cantidad total de artículos.
     *
     * Este método se utiliza para las estadísticas
     * del Dashboard administrativo.
     */
    public function countAllArticles(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM articulos
        ";

        $statement = $this->db->query($sql);

        return (int) $statement->fetchColumn();
    }


    /**
     * Obtiene la cantidad de artículos publicados.
     *
     * Este método se utiliza para las estadísticas
     * del Dashboard administrativo.
     */
    public function countPublishedArticles(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM articulos
            WHERE estado = 'publicado'
        ";

        $statement = $this->db->query($sql);

        return (int) $statement->fetchColumn();
    }


    /**
     * Obtiene la cantidad de artículos en borrador.
     *
     * Este método se utiliza para las estadísticas
     * del Dashboard administrativo.
     */
    public function countDraftArticles(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM articulos
            WHERE estado = 'borrador'
        ";

        $statement = $this->db->query($sql);

        return (int) $statement->fetchColumn();
    }


    /**
     * Obtiene los artículos más recientes.
     *
     * Se utiliza para mostrar actividad reciente
     * en el Dashboard administrativo.
     */
    public function getRecentArticles(
        int $limit = 5
    ): array {
        $limit = max(1, min($limit, 20));

        $sql = "
            SELECT
                a.id,
                a.titulo,
                a.slug,
                a.estado,
                a.created_at,

                CONCAT(
                    u.nombre,
                    ' ',
                    u.apellido
                ) AS autor,

                c.nombre AS categoria,

                s.id AS serie_id,
                s.titulo AS serie

            FROM articulos a

            INNER JOIN usuarios u
                ON a.usuario_id = u.id

            INNER JOIN categorias c
                ON a.categoria_id = c.id

            LEFT JOIN series s
                ON a.serie_id = s.id

            ORDER BY a.created_at DESC

            LIMIT {$limit}
        ";

        $statement = $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Genera un slug único.
     */
    public function generateUniqueSlug(
        string $baseSlug,
        ?int $excludeId = null
    ): string {
        $slug = $baseSlug;
        $counter = 2;

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
            FROM articulos
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

        $statement->execute($params);

        return
            $statement->fetch() !== false;
    }


    /**
     * Crea un nuevo artículo.
     */
    public function create(array $data): bool
    {
        $sql = "
            INSERT INTO articulos
            (
                usuario_id,
                categoria_id,
                serie_id,
                titulo,
                slug,
                resumen,
                contenido,
                imagen,
                estado
            )
            VALUES
            (
                :usuario_id,
                :categoria_id,
                :serie_id,
                :titulo,
                :slug,
                :resumen,
                :contenido,
                :imagen,
                :estado
            )
        ";

        $statement =
            $this->db->prepare($sql);

        return $statement->execute($data);
    }


    /**
     * Actualiza un artículo existente.
     */
    public function update(
        int $id,
        array $data
    ): bool {
        $sql = "
            UPDATE articulos

            SET
                categoria_id = :categoria_id,
                serie_id     = :serie_id,
                titulo       = :titulo,
                slug         = :slug,
                resumen      = :resumen,
                contenido    = :contenido,
                imagen       = :imagen,
                estado       = :estado

            WHERE id = :id
        ";

        $statement =
            $this->db->prepare($sql);

        $data['id'] = $id;

        return $statement->execute($data);
    }


    /**
     * Elimina definitivamente un artículo.
     */
    public function delete(
        int $id
    ): bool {
        $sql = "
            DELETE FROM articulos
            WHERE id = :id
        ";

        $statement =
            $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id
        ]);
    }


    /**
     * Busca artículos publicados relacionados
     * con el término introducido por el usuario.
     */
    public function searchPublishedArticles(
        string $search
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
                c.slug AS categoria_slug,

                s.id AS serie_id,
                s.titulo AS serie,
                s.slug AS serie_slug

            FROM articulos a

            INNER JOIN usuarios u
                ON a.usuario_id = u.id

            INNER JOIN categorias c
                ON a.categoria_id = c.id

            LEFT JOIN series s
                ON a.serie_id = s.id

            WHERE a.estado = 'publicado'

            AND (
                a.titulo LIKE :search_title

                OR a.resumen LIKE :search_summary

                OR a.contenido LIKE :search_content

                OR c.nombre LIKE :search_category
            )

            ORDER BY

                CASE

                    WHEN a.titulo LIKE :priority_title
                        THEN 1

                    WHEN a.resumen LIKE :priority_summary
                        THEN 2

                    WHEN a.contenido LIKE :priority_content
                        THEN 3

                    ELSE 4

                END,

                a.created_at DESC
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

            'search_summary' =>
                $searchTerm,

            'search_content' =>
                $searchTerm,

            'search_category' =>
                $searchTerm,

            'priority_title' =>
                $searchTerm,

            'priority_summary' =>
                $searchTerm,

            'priority_content' =>
                $searchTerm
        ]);

        return $statement->fetchAll();
    }
}