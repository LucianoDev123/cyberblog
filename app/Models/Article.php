<?php

declare(strict_types=1);

namespace App\Models;

class Article extends Model
{
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

                CONCAT(u.nombre, ' ', u.apellido) AS autor,

                c.nombre AS categoria,
                c.slug AS categoria_slug

            FROM articulos a

            INNER JOIN usuarios u
                ON a.usuario_id = u.id

            INNER JOIN categorias c
                ON a.categoria_id = c.id

            WHERE a.estado = 'publicado'

            ORDER BY a.created_at DESC
        ";

        $statement = $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Obtiene un artículo publicado mediante su slug.
     */
    public function getArticleBySlug(string $slug): array|false
    {
        $sql = "
            SELECT
                a.*,

                CONCAT(u.nombre, ' ', u.apellido) AS autor,

                c.nombre AS categoria,
                c.slug AS categoria_slug

            FROM articulos a

            INNER JOIN usuarios u
                ON a.usuario_id = u.id

            INNER JOIN categorias c
                ON a.categoria_id = c.id

            WHERE a.slug = :slug

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

                CONCAT(u.nombre, ' ', u.apellido) AS autor,

                c.nombre AS categoria

            FROM articulos a

            INNER JOIN usuarios u
                ON a.usuario_id = u.id

            INNER JOIN categorias c
                ON a.categoria_id = c.id

            ORDER BY a.created_at DESC
        ";

        $statement = $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Genera un slug único.
     *
     * Ejemplo:
     *
     * mi-articulo
     * mi-articulo-2
     * mi-articulo-3
     *
     * Al editar un artículo se puede excluir
     * el ID actual para que conserve su propio slug.
     */
    public function generateUniqueSlug(
        string $baseSlug,
        ?int $excludeId = null
    ): string {
        $slug = $baseSlug;
        $counter = 2;

        while ($this->slugExists($slug, $excludeId)) {

            $slug = $baseSlug . '-' . $counter;

            $counter++;
        }

        return $slug;
    }


    /**
     * Comprueba si un slug ya existe.
     *
     * $excludeId se utiliza durante la edición
     * para ignorar el artículo actual.
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

        /*
         * Si estamos editando un artículo,
         * excluimos su propio ID.
         */
        if ($excludeId !== null) {

            $sql .= "
                AND id != :id
            ";

            $params['id'] = $excludeId;
        }

        $sql .= "
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute($params);

        return $statement->fetch() !== false;
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
                :titulo,
                :slug,
                :resumen,
                :contenido,
                :imagen,
                :estado
            )
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute($data);
    }


    /**
     * Actualiza un artículo existente.
     */
    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE articulos
            SET
                categoria_id = :categoria_id,
                titulo       = :titulo,
                slug         = :slug,
                resumen      = :resumen,
                contenido    = :contenido,
                imagen       = :imagen,
                estado       = :estado
            WHERE id = :id
        ";

        $statement = $this->db->prepare($sql);

        $data['id'] = $id;

        return $statement->execute($data);
    }


    /**
     * Elimina definitivamente un artículo.
     */
    public function delete(int $id): bool
    {
        $sql = "
            DELETE FROM articulos
            WHERE id = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id
        ]);
    }
}