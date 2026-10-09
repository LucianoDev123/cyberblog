<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use Throwable;

class Comment extends Model
{
    protected string $table = 'comentarios';

    /**
     * Crea un comentario o una respuesta.
     *
     * Los comentarios nuevos siempre quedan pendientes
     * de moderación.
     */
    public function create(
        int $articuloId,
        int $usuarioId,
        string $autor,
        string $email,
        string $comentario,
        ?int $comentarioPadreId = null
    ): bool {
        $sql = "
            INSERT INTO {$this->table} (
                articulo_id,
                usuario_id,
                comentario_padre_id,
                autor,
                email,
                comentario,
                estado
            )
            VALUES (
                :articulo_id,
                :usuario_id,
                :comentario_padre_id,
                :autor,
                :email,
                :comentario,
                'pendiente'
            )
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'articulo_id'         => $articuloId,
            'usuario_id'          => $usuarioId,
            'comentario_padre_id' => $comentarioPadreId,
            'autor'               => $autor,
            'email'               => $email,
            'comentario'          => $comentario,
        ]);
    }

    /**
     * Obtiene los comentarios aprobados de un artículo.
     *
     * Incluye los datos necesarios para construir las
     * conversaciones y mostrar las insignias de usuario.
     */
    public function getApprovedByArticleId(
        int $articuloId
    ): array {
        $sql = "
            SELECT
                c.id,
                c.articulo_id,
                c.usuario_id,
                c.comentario_padre_id,
                c.autor,
                c.comentario,
                c.estado,
                c.created_at,
                u.rol AS usuario_rol,
                a.usuario_id AS articulo_autor_id
            FROM {$this->table} c
            INNER JOIN articulos a
                ON a.id = c.articulo_id
            LEFT JOIN usuarios u
                ON u.id = c.usuario_id
            WHERE c.articulo_id = :articulo_id
              AND c.estado = 'aprobado'
            ORDER BY c.created_at ASC, c.id ASC
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'articulo_id' => $articuloId,
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Comprueba si un comentario puede recibir una respuesta.
     *
     * Debe existir, pertenecer al artículo indicado y estar
     * aprobado.
     */
    public function canReplyTo(
        int $comentarioId,
        int $articuloId
    ): bool {
        $sql = "
            SELECT id
            FROM {$this->table}
            WHERE id = :id
              AND articulo_id = :articulo_id
              AND estado = 'aprobado'
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'id'          => $comentarioId,
            'articulo_id' => $articuloId,
        ]);

        return $statement->fetch(PDO::FETCH_ASSOC) !== false;
    }

    /**
     * Obtiene los comentarios pendientes de moderación.
     *
     * Incluye información del artículo y del comentario padre.
     */
    public function getPending(): array
    {
        $sql = "
            SELECT
                c.id,
                c.articulo_id,
                c.usuario_id,
                c.comentario_padre_id,
                c.autor,
                c.email,
                c.comentario,
                c.estado,
                c.created_at,
                a.titulo AS articulo_titulo,
                a.slug AS articulo_slug,
                padre.comentario AS comentario_padre
            FROM {$this->table} c
            INNER JOIN articulos a
                ON a.id = c.articulo_id
            LEFT JOIN {$this->table} padre
                ON padre.id = c.comentario_padre_id
            WHERE c.estado = 'pendiente'
            ORDER BY c.created_at ASC, c.id ASC
        ";

        $statement = $this->db->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene todos los comentarios aprobados para el
     * panel administrativo.
     *
     * Incluye información del artículo y del comentario padre.
     */
    public function getApproved(): array
    {
        $sql = "
            SELECT
                c.id,
                c.articulo_id,
                c.usuario_id,
                c.comentario_padre_id,
                c.autor,
                c.email,
                c.comentario,
                c.estado,
                c.created_at,
                a.titulo AS articulo_titulo,
                a.slug AS articulo_slug,
                padre.comentario AS comentario_padre
            FROM {$this->table} c
            INNER JOIN articulos a
                ON a.id = c.articulo_id
            LEFT JOIN {$this->table} padre
                ON padre.id = c.comentario_padre_id
            WHERE c.estado = 'aprobado'
            ORDER BY c.created_at DESC, c.id DESC
        ";

        $statement = $this->db->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Aprueba un comentario pendiente.
     */
    public function approve(int $id): bool
    {
        $sql = "
            UPDATE {$this->table}
            SET estado = 'aprobado'
            WHERE id = :id
              AND estado = 'pendiente'
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'id' => $id,
        ]);

        return $statement->rowCount() > 0;
    }

    /**
     * Elimina un comentario y todos sus descendientes.
     *
     * La operación se realiza dentro de una transacción
     * para evitar eliminaciones parciales.
     */
    public function delete(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        try {
            $this->db->beginTransaction();

            /*
             * Comprobamos y bloqueamos el comentario solicitado.
             */
            $statement = $this->db->prepare("
                SELECT id
                FROM {$this->table}
                WHERE id = :id
                FOR UPDATE
            ");

            $statement->execute([
                'id' => $id,
            ]);

            if ($statement->fetch(PDO::FETCH_ASSOC) === false) {
                $this->db->rollBack();

                return false;
            }

            /*
             * Buscamos recursivamente todos los descendientes.
             */
            $pendientes = [$id];
            $visitados = [];

            $buscarHijos = $this->db->prepare("
                SELECT id
                FROM {$this->table}
                WHERE comentario_padre_id = :padre_id
                FOR UPDATE
            ");

            while ($pendientes !== []) {
                $actual = array_pop($pendientes);

                if (isset($visitados[$actual])) {
                    continue;
                }

                $visitados[$actual] = true;

                $buscarHijos->execute([
                    'padre_id' => $actual,
                ]);

                $hijos = $buscarHijos->fetchAll(PDO::FETCH_COLUMN);

                foreach ($hijos as $hijoId) {
                    $hijoId = (int) $hijoId;

                    if (!isset($visitados[$hijoId])) {
                        $pendientes[] = $hijoId;
                    }
                }
            }

            /*
             * Preparamos una única eliminación para todos
             * los comentarios identificados.
             */
            $ids = array_map(
                'intval',
                array_keys($visitados)
            );

            $placeholders = [];
            $parametros = [];

            foreach ($ids as $indice => $comentarioId) {
                $nombreParametro = ':id_' . $indice;

                $placeholders[] = $nombreParametro;
                $parametros[$nombreParametro] = $comentarioId;
            }

            $sql = "
                DELETE FROM {$this->table}
                WHERE id IN (" . implode(', ', $placeholders) . ")
            ";

            $eliminar = $this->db->prepare($sql);
            $eliminar->execute($parametros);

            if ($eliminar->rowCount() !== count($ids)) {
                throw new \RuntimeException(
                    'La cantidad de comentarios eliminados no coincide.'
                );
            }

            $this->db->commit();

            return true;
        } catch (Throwable $exception) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $exception;
        }
    }
}