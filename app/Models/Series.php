<?php

declare(strict_types=1);

namespace App\Models;


/**
 * Modelo encargado de gestionar las series de artículos.
 *
 * Una serie permite agrupar varios artículos relacionados
 * dentro de una misma temática o recorrido de aprendizaje.
 *
 * Ejemplo:
 *
 * Serie:
 * Introducción a Wazuh
 *
 * Artículos:
 * - Qué es Wazuh.
 * - Instalación de Wazuh.
 * - Configuración de agentes.
 * - Monitoreo y alertas.
 */
class Series extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected string $table = 'series';


    /**
     * Obtiene todas las series.
     *
     * Incluye la cantidad de artículos asociados
     * a cada serie.
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

        $statement = $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Obtiene únicamente las series publicadas.
     *
     * Este método será utilizado posteriormente
     * en las páginas públicas del sitio.
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

        $statement = $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Obtiene una serie mediante su ID.
     *
     * Este método será utilizado principalmente
     * desde el panel administrativo.
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

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'id' => $id
        ]);

        return $statement->fetch();
    }


    /**
     * Obtiene una serie mediante su slug.
     *
     * Este método será utilizado posteriormente
     * en la parte pública del sitio.
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

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'slug' => $slug
        ]);

        return $statement->fetch();
    }


    /**
     * Obtiene los artículos asociados a una serie.
     *
     * Solamente devuelve artículos publicados.
     *
     * Los resultados se ordenan desde el artículo
     * más antiguo al más reciente para facilitar
     * la lectura secuencial de la serie.
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

        $statement = $this->db->prepare($sql);

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

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'titulo' => $data['titulo'],
            'slug' => $data['slug'],
            'descripcion' => $data['descripcion'],
            'imagen' => $data['imagen'],
            'estado' => $data['estado']
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

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id,
            'titulo' => $data['titulo'],
            'slug' => $data['slug'],
            'descripcion' => $data['descripcion'],
            'imagen' => $data['imagen'],
            'estado' => $data['estado']
        ]);
    }


    /**
     * Elimina una serie.
     *
     * Debido a la restricción:
     *
     * ON DELETE SET NULL
     *
     * los artículos asociados no serán eliminados.
     * Su campo serie_id simplemente pasará a NULL.
     */
    public function delete(
        int $id
    ): bool {

        $sql = "
            DELETE FROM series
            WHERE id = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id
        ]);
    }


    /**
     * Genera un slug único para una serie.
     *
     * Ejemplo:
     *
     * introduccion-a-wazuh
     * introduccion-a-wazuh-2
     * introduccion-a-wazuh-3
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

            $slug = $baseSlug . '-' . $counter;

            $counter++;
        }

        return $slug;
    }


    /**
     * Comprueba si un slug ya existe.
     *
     * Durante la edición se puede excluir
     * el ID de la serie actual.
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
            'slug' => $slug
        ];


        /**
         * Si estamos editando una serie,
         * ignoramos su propio registro.
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
}