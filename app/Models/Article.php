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



    /**
     * Busca artículos publicados relacionados
     * con el término introducido por el usuario.
     *
     * La búsqueda se realiza sobre:
     *
     * - El título.
     * - El resumen.
     * - El contenido.
     * - La categoría.
     *
     * Solamente se devuelven artículos cuyo estado
     * sea "publicado".
     *
     * Se utilizan consultas preparadas mediante PDO
     * para separar los datos proporcionados por el usuario
     * de la estructura de la consulta SQL.
     */
    public function searchPublishedArticles(
        string $search
    ): array {

        /*
        * Definimos la consulta SQL que utilizaremos
        * para buscar artículos relacionados.
        */
        $sql = "
            SELECT
                a.id,
                a.titulo,
                a.slug,
                a.resumen,
                a.imagen,
                a.created_at,

                /*
                * Unimos el nombre y el apellido del autor
                * para devolverlos como un único campo llamado
                * 'autor'.
                */
                CONCAT(
                    u.nombre,
                    ' ',
                    u.apellido
                ) AS autor,

                /*
                * Obtenemos también el nombre y el slug
                * de la categoría a la que pertenece
                * cada artículo.
                */
                c.nombre AS categoria,
                c.slug AS categoria_slug

            /*
            * La tabla principal de la consulta
            * es la tabla 'articulos'.
            *
            * Utilizamos el alias 'a' para referirnos
            * a ella de forma más corta.
            */
            FROM articulos a

            /*
            * Relacionamos cada artículo con el usuario
            * que lo creó.
            */
            INNER JOIN usuarios u
                ON a.usuario_id = u.id

            /*
            * Relacionamos cada artículo con su categoría.
            */
            INNER JOIN categorias c
                ON a.categoria_id = c.id

            /*
            * Solamente permitimos que aparezcan
            * artículos publicados.
            *
            * De esta manera los artículos en estado
            * borrador no pueden aparecer en la búsqueda
            * pública.
            */
            WHERE a.estado = 'publicado'

            /*
            * Agrupamos las condiciones de búsqueda.
            *
            * El término introducido por el usuario
            * puede aparecer en cualquiera de estos campos.
            */
            AND (
                a.titulo LIKE :search_title

                OR a.resumen LIKE :search_summary

                OR a.contenido LIKE :search_content

                OR c.nombre LIKE :search_category
            )

            /*
            * Ordenamos los resultados según su relevancia.
            */
            ORDER BY

                /*
                * CASE permite asignar una prioridad
                * numérica a cada resultado.
                */
                CASE

                    /*
                    * Un resultado cuya coincidencia esté
                    * en el título recibe prioridad 1.
                    */
                    WHEN a.titulo LIKE :priority_title
                        THEN 1

                    /*
                    * Una coincidencia en el resumen
                    * recibe prioridad 2.
                    */
                    WHEN a.resumen LIKE :priority_summary
                        THEN 2

                    /*
                    * Una coincidencia en el contenido
                    * recibe prioridad 3.
                    */
                    WHEN a.contenido LIKE :priority_content
                        THEN 3

                    /*
                    * Las demás coincidencias reciben
                    * prioridad 4.
                    */
                    ELSE 4

                END,

                /*
                * Cuando varios resultados tienen
                * la misma prioridad, mostramos primero
                * los artículos más recientes.
                */
                a.created_at DESC
        ";

        /*
        * Agregamos los caracteres '%' antes y después
        * del término de búsqueda.
        *
        * Por ejemplo:
        *
        * OPNsense
        *
        * se convierte en:
        *
        * %OPNsense%
        *
        * Esto permite encontrar coincidencias parciales
        * mediante el operador SQL LIKE.
        */
        $searchTerm = '%' . $search . '%';

        /*
        * Preparamos la consulta SQL.
        *
        * prepare() todavía no ejecuta la consulta.
        */
        $statement = $this->db->prepare($sql);

        /*
        * Ejecutamos la consulta y asociamos un valor
        * independiente a cada placeholder.
        *
        * Aunque todos contienen el mismo término,
        * cada placeholder tiene un nombre único.
        *
        * Esto evita el error:
        *
        * SQLSTATE[HY093]: Invalid parameter number
        *
        * cuando PDO trabaja con consultas preparadas
        * reales y ATTR_EMULATE_PREPARES está configurado
        * como false.
        */
        $statement->execute([

            /*
            * Término utilizado para buscar
            * dentro del título.
            */
            'search_title' => $searchTerm,

            /*
            * Término utilizado para buscar
            * dentro del resumen.
            */
            'search_summary' => $searchTerm,

            /*
            * Término utilizado para buscar
            * dentro del contenido.
            */
            'search_content' => $searchTerm,

            /*
            * Término utilizado para buscar
            * dentro del nombre de la categoría.
            */
            'search_category' => $searchTerm,

            /*
            * Término utilizado para establecer
            * la prioridad de coincidencias
            * encontradas en el título.
            */
            'priority_title' => $searchTerm,

            /*
            * Término utilizado para establecer
            * la prioridad de coincidencias
            * encontradas en el resumen.
            */
            'priority_summary' => $searchTerm,

            /*
            * Término utilizado para establecer
            * la prioridad de coincidencias
            * encontradas en el contenido.
            */
            'priority_content' => $searchTerm
        ]);

        /*
        * fetchAll() obtiene todos los artículos
        * encontrados por la consulta.
        *
        * El resultado será un array asociativo
        * con los artículos encontrados.
        */
        return $statement->fetchAll();
    }


}