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

                c.nombre AS categoria

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

                c.nombre AS categoria

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
}