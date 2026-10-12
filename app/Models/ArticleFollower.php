<?php

declare(strict_types=1);

namespace App\Models;

class ArticleFollower extends Model
{
    protected string $table = 'articulo_seguidores';

    /**
     * Comprueba si un usuario sigue un artículo.
     */
    public function isFollowing(
        int $userId,
        int $articleId
    ): bool {
        $sql = "
            SELECT 1
            FROM articulo_seguidores
            WHERE usuario_id = :usuario_id
              AND articulo_id = :articulo_id
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'usuario_id' => $userId,
            'articulo_id' => $articleId
        ]);

        return $statement->fetchColumn() !== false;
    }

    /**
     * Registra el seguimiento de un artículo publicado.
     *
     * Si el usuario ya lo sigue, no duplica el registro.
     * No permite seguir artículos en borrador.
     */
    public function follow(
        int $userId,
        int $articleId
    ): bool {
        $sql = "
            INSERT INTO articulo_seguidores (
                usuario_id,
                articulo_id
            )
            SELECT
                :usuario_id,
                a.id
            FROM articulos a
            WHERE a.id = :articulo_id
              AND a.estado = 'publicado'
            ON DUPLICATE KEY UPDATE
                articulo_id = VALUES(articulo_id)
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'usuario_id' => $userId,
            'articulo_id' => $articleId
        ]);
    }

    /**
     * Deja de seguir un artículo.
     */
    public function unfollow(
        int $userId,
        int $articleId
    ): bool {
        $sql = "
            DELETE FROM articulo_seguidores
            WHERE usuario_id = :usuario_id
              AND articulo_id = :articulo_id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'usuario_id' => $userId,
            'articulo_id' => $articleId
        ]);
    }

    /**
     * Cuenta los seguidores de un artículo.
     */
    public function countFollowers(
        int $articleId
    ): int {
        $sql = "
            SELECT COUNT(*)
            FROM articulo_seguidores
            WHERE articulo_id = :articulo_id
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'articulo_id' => $articleId
        ]);

        return (int) $statement->fetchColumn();
    }
}
