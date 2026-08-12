<?php

declare(strict_types=1);

namespace App\Models;

class Article extends Model
{
    protected string $table = 'articulos';

    public function getPublishedArticles(): array
    {
        $sql = "
            SELECT
                a.id,
                a.titulo,
                a.slug,
                a.resumen,
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

    public function getAllArticles(): array
    {
        $sql = "
            SELECT

                a.id,

                a.titulo,

                a.estado,

                a.created_at,

                CONCAT(u.nombre,' ',u.apellido) AS autor,

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
                :estado
            )
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute($data);
    }

    /**
     * Actualiza un artículo existente.
     *
     * @param int $id ID del artículo que vamos a modificar.
     * @param array $data Datos nuevos que llegarán desde el formulario.
     *
     * @return bool true si el UPDATE fue exitoso.
     */
    public function update(int $id, array $data): bool
    {
        // Consulta SQL para actualizar un artículo
        $sql = "
            UPDATE articulos
            SET
                categoria_id = :categoria_id,
                titulo       = :titulo,
                slug         = :slug,
                resumen      = :resumen,
                contenido    = :contenido,
                estado       = :estado
            WHERE id = :id
        ";

        // Preparamos la consulta para evitar SQL Injection
        $statement = $this->db->prepare($sql);

        // Agregamos el ID al array de datos
        $data['id'] = $id;

        // Ejecutamos el UPDATE
        return $statement->execute($data);
    }



        /**
     * Elimina definitivamente un artículo de la base de datos.
     *
     * @param int $id ID del artículo a eliminar.
     *
     * @return bool true si la eliminación fue exitosa.
     */
    public function delete(int $id): bool
    {
        // Consulta SQL para eliminar un artículo
        $sql = "
            DELETE FROM articulos
            WHERE id = :id
        ";

        // Preparamos la consulta
        $statement = $this->db->prepare($sql);

        // Ejecutamos la consulta enviando el ID
        return $statement->execute([
            'id' => $id
        ]);
    }


}