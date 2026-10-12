<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use Throwable;

class Notification extends Model
{
    protected string $table = 'notificaciones';

    /**
     * Devuelve las notificaciones de un usuario, de más nuevas a más antiguas.
     */
    public function getForUser(int $userId, int $limit = 100): array
    {
        $limit = max(1, min($limit, 200));

        $sql = "
            SELECT
                n.id,
                n.usuario_id,
                n.tipo,
                n.articulo_id,
                n.comentario_id,
                n.actor_usuario_id,
                n.mensaje,
                n.leida,
                n.created_at,
                a.titulo AS articulo_titulo,
                a.slug AS articulo_slug
            FROM {$this->table} n
            LEFT JOIN articulos a ON a.id = n.articulo_id
            WHERE n.usuario_id = :usuario_id
            ORDER BY n.leida ASC, n.created_at DESC, n.id DESC
            LIMIT {$limit}
        ";

        $statement = $this->db->prepare($sql);
        $statement->execute(['usuario_id' => $userId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cuenta las notificaciones no leídas de un usuario.
     */
    public function countUnread(int $userId): int
    {
        $statement = $this->db->prepare("
            SELECT COUNT(*)
            FROM {$this->table}
            WHERE usuario_id = :usuario_id
              AND leida = 0
        ");
        $statement->execute(['usuario_id' => $userId]);

        return (int) $statement->fetchColumn();
    }

    /**
     * Marca como leída una notificación, solo si pertenece al usuario.
     */
    public function markRead(int $notificationId, int $userId): bool
    {
        $statement = $this->db->prepare("
            UPDATE {$this->table}
            SET leida = 1
            WHERE id = :id
              AND usuario_id = :usuario_id
              AND leida = 0
        ");

        return $statement->execute([
            'id' => $notificationId,
            'usuario_id' => $userId,
        ]);
    }

    /**
     * Marca todas las notificaciones del usuario como leídas.
     */
    public function markAllRead(int $userId): bool
    {
        $statement = $this->db->prepare("
            UPDATE {$this->table}
            SET leida = 1
            WHERE usuario_id = :usuario_id
              AND leida = 0
        ");

        return $statement->execute(['usuario_id' => $userId]);
    }

    /**
     * Genera notificaciones para un comentario que ya fue aprobado.
     *
     * Destinatarios:
     * - autor del comentario padre, si es una respuesta;
     * - seguidores del artículo.
     *
     * Se excluye al autor de la acción y se consolida cada destinatario
     * en una sola notificación por comentario aprobado.
     */
    public function createForApprovedComment(int $commentId): void
    {
        $statement = $this->db->prepare("
            SELECT
                c.id,
                c.articulo_id,
                c.usuario_id,
                c.comentario_padre_id,
                c.estado,
                a.titulo AS articulo_titulo,
                a.slug AS articulo_slug,
                padre.usuario_id AS padre_usuario_id
            FROM comentarios c
            INNER JOIN articulos a ON a.id = c.articulo_id
            LEFT JOIN comentarios padre ON padre.id = c.comentario_padre_id
            WHERE c.id = :id
            LIMIT 1
        ");
        $statement->execute(['id' => $commentId]);
        $comment = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$comment || $comment['estado'] !== 'aprobado') {
            return;
        }

        $actorId = isset($comment['usuario_id'])
            ? (int) $comment['usuario_id']
            : 0;

        $articleId = (int) $comment['articulo_id'];
        $commentParentId = isset($comment['comentario_padre_id'])
            ? (int) $comment['comentario_padre_id']
            : 0;

        // Un mapa por usuario evita duplicar si el autor del padre también sigue el artículo.
        $recipients = [];

        if (
            $commentParentId > 0 &&
            !empty($comment['padre_usuario_id'])
        ) {
            $parentAuthorId = (int) $comment['padre_usuario_id'];

            if ($parentAuthorId > 0 && $parentAuthorId !== $actorId) {
                $recipients[$parentAuthorId] = [
                    'tipo' => 'respuesta_comentario',
                    'mensaje' => 'Respondieron a tu comentario en «' . $comment['articulo_titulo'] . '».',
                ];
            }
        }

        $followersStatement = $this->db->prepare("
            SELECT usuario_id
            FROM articulo_seguidores
            WHERE articulo_id = :articulo_id
        ");
        $followersStatement->execute(['articulo_id' => $articleId]);

        $isReply = $commentParentId > 0;
        $type = $isReply ? 'respuesta_articulo_seguido' : 'comentario_articulo_seguido';
        $message = $isReply
            ? 'Hay una nueva respuesta en el artículo que seguís: «' . $comment['articulo_titulo'] . '».'
            : 'Hay un nuevo comentario en el artículo que seguís: «' . $comment['articulo_titulo'] . '».';

        foreach ($followersStatement->fetchAll(PDO::FETCH_COLUMN) as $followerId) {
            $followerId = (int) $followerId;

            if ($followerId <= 0 || $followerId === $actorId) {
                continue;
            }

            // Si ya recibió aviso como autor del comentario padre, no se duplica.
            if (!isset($recipients[$followerId])) {
                $recipients[$followerId] = [
                    'tipo' => $type,
                    'mensaje' => $message,
                ];
            }
        }

        if ($recipients === []) {
            return;
        }

        $insert = $this->db->prepare("
            INSERT INTO {$this->table} (
                usuario_id,
                tipo,
                articulo_id,
                comentario_id,
                actor_usuario_id,
                mensaje,
                leida
            ) VALUES (
                :usuario_id,
                :tipo,
                :articulo_id,
                :comentario_id,
                :actor_usuario_id,
                :mensaje,
                0
            )
        ");

        foreach ($recipients as $recipientId => $details) {
            $insert->execute([
                'usuario_id' => $recipientId,
                'tipo' => $details['tipo'],
                'articulo_id' => $articleId,
                'comentario_id' => $commentId,
                'actor_usuario_id' => $actorId > 0 ? $actorId : null,
                'mensaje' => mb_substr($details['mensaje'], 0, 255, 'UTF-8'),
            ]);
        }
    }
}
