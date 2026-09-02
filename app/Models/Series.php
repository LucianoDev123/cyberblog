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
     *
     * Este método se utiliza principalmente
     * desde el panel administrativo.
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
     * Obtiene únicamente las series publicadas.
     *
     * Incluye únicamente los artículos publicados
     * al calcular la cantidad de artículos.
     *
     * Este método se utiliza en la página pública
     * principal de series.
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
     * La búsqueda se realiza sobre:
     *
     * - El título.
     * - La descripción.
     *
     * Solamente se devuelven series cuyo estado
     * sea "publicada".
     *
     * También se incluye la cantidad de artículos
     * publicados asociados a cada serie.
     *
     * La consulta utiliza una sentencia preparada
     * para separar los datos introducidos por el usuario
     * de la estructura SQL.
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


        /*
         * Agregamos "%" antes y después
         * del término de búsqueda.
         *
         * Por ejemplo:
         *
         * wazuh
         *
         * se convierte en:
         *
         * %wazuh%
         */
        $searchTerm =
            '%'
            . $search
            . '%';


        /*
         * Preparamos la consulta SQL.
         */
        $statement =
            $this->db->prepare($sql);


        /*
         * Ejecutamos la consulta.
         *
         * El mismo término se utiliza
         * tanto para el título como
         * para la descripción.
         */
        $statement->execute([
            'search_title' =>
                $searchTerm,

            'search_description' =>
                $searchTerm
        ]);


        /*
         * Devolvemos todas las series encontradas.
         */
        return $statement->fetchAll();
    }


    /**
     * Obtiene una serie mediante su ID.
     *
     * Este método se utiliza principalmente
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


        $statement =
            $this->db->prepare($sql);


        $statement->execute([
            'id' => $id
        ]);


        return $statement->fetch();
    }


    /**
     * Obtiene una serie mediante su slug.
     *
     * Este método puede utilizarse tanto
     * desde el panel administrativo como
     * desde la parte pública.
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


        $statement =
            $this->db->prepare($sql);


        return $statement->execute([
            'id' =>
                $id
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
            'slug' =>
                $slug
        ];


        /**
         * Si estamos editando una serie,
         * ignoramos su propio registro.
         */
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