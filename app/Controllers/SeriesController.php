<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Series;
use App\Middleware\RoleMiddleware;


/**
 * Controlador encargado de gestionar las series
 * tanto desde el panel administrativo como
 * desde la parte pública de CyberBlog.
 *
 * La parte administrativa permite:
 *
 * - Listar series.
 * - Crear series.
 * - Editar series.
 * - Eliminar series.
 *
 * La parte pública permite:
 *
 * - Listar series publicadas.
 * - Buscar series publicadas.
 * - Mostrar una serie individual.
 * - Mostrar los artículos publicados
 *   pertenecientes a una serie.
 */
class SeriesController extends Controller
{
    /**
     * Modelo de Series.
     */
    private Series $seriesModel;


    /**
     * Inicializamos el modelo.
     */
    public function __construct()
    {
        $this->seriesModel = new Series();
    }


    /* =========================================================
     * PARTE PÚBLICA
     * ========================================================= */


    /**
     * Muestra el listado público de series.
     *
     * URL:
     *
     * /series
     *
     * Solamente se muestran series publicadas.
     */
    public function publicIndex(): void
    {
        /*
         * Obtenemos únicamente las series
         * que están publicadas.
         */
        $series =
            $this->seriesModel->getPublishedSeries();


        /*
         * Cargamos la vista pública.
         *
         * La vista recibe únicamente
         * las series que debe mostrar.
         */
        $this->view(
            'series/public/index',
            [
                'title' =>
                    'Series de artículos',

                'series' =>
                    $series
            ]
        );
    }


    /**
     * Muestra una serie individual.
     *
     * URL:
     *
     * /series/{slug}
     *
     * Ejemplo:
     *
     * /series/introduccion-a-wazuh
     *
     * Solamente permite acceder a series
     * que estén publicadas.
     */
    public function publicShow(
        string $slug
    ): void {

        /*
         * Buscamos la serie utilizando
         * el slug recibido desde la URL.
         */
        $serie =
            $this->seriesModel->getSeriesBySlug(
                $slug
            );


        /*
         * Si la serie no existe,
         * devolvemos un error 404.
         */
        if ($serie === false) {

            http_response_code(404);

            die(
                'La serie solicitada no existe.'
            );
        }


        /*
         * Una serie puede existir en la base de datos
         * pero estar en estado "borrador".
         *
         * Las series borrador no deben ser accesibles
         * desde la parte pública.
         */
        if (
            $serie['estado'] !== 'publicada'
        ) {

            http_response_code(404);

            die(
                'La serie solicitada no existe.'
            );
        }


        /*
         * Obtenemos únicamente los artículos
         * publicados pertenecientes a la serie.
         *
         * El modelo los devuelve ordenados
         * desde el más antiguo al más reciente.
         */
        $articles =
            $this->seriesModel
                ->getPublishedArticlesBySeriesId(
                    (int) $serie['id']
                );


        /*
         * Calculamos la cantidad de artículos
         * publicados que contiene la serie.
         *
         * count() cuenta los elementos del array
         * recibido desde el modelo.
         *
         * Ejemplo:
         *
         * $articles = [
         *     artículo 1,
         *     artículo 2
         * ];
         *
         * count($articles) devuelve:
         *
         * 2
         */
        $totalArticles =
            count($articles);


        /*
         * Cargamos la vista pública
         * de la serie.
         *
         * Enviamos todos los datos que
         * la vista necesita.
         */
        $this->view(
            'series/public/show',
            [
                'title' =>
                    $serie['titulo'],

                'serie' =>
                    $serie,

                'articles' =>
                    $articles,

                'totalArticles' =>
                    $totalArticles
            ]
        );
    }


    /* =========================================================
     * PARTE ADMINISTRATIVA
     * ========================================================= */


    /**
     * Muestra el listado administrativo
     * de todas las series.
     */
    public function index(): void
    {
        /*
         * Permitimos acceder al módulo
         * únicamente a administradores
         * y editores.
         */
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);


        /*
         * Obtenemos todas las series,
         * incluyendo borradores y publicadas.
         */
        $series =
            $this->seriesModel->getAllSeries();


        /*
         * Cargamos la vista administrativa.
         */
        $this->adminView(
            'series/index',
            [
                'title' =>
                    'Administración de Series',

                'series' =>
                    $series
            ]
        );
    }


    /**
     * Muestra el formulario para crear
     * una nueva serie.
     */
    public function create(): void
    {
        /*
         * Solo administradores y editores
         * pueden crear series.
         */
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);


        /*
         * Cargamos el formulario
         * administrativo.
         */
        $this->adminView(
            'series/create',
            [
                'title' =>
                    'Nueva Serie'
            ]
        );
    }


    /**
     * Guarda una nueva serie.
     */
    public function store(): void
    {
        /*
         * Solo administradores y editores
         * pueden crear series.
         */
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);


        /*
         * Obtenemos y limpiamos
         * el título.
         */
        $titulo =
            trim(
                $_POST['titulo'] ?? ''
            );


        /*
         * Obtenemos y limpiamos
         * la descripción.
         */
        $descripcion =
            trim(
                $_POST['descripcion'] ?? ''
            );


        /*
         * Obtenemos el estado.
         *
         * Si no se recibe ninguno,
         * utilizamos "borrador".
         */
        $estado =
            $_POST['estado']
            ?? 'borrador';


        /*
         * Validamos que el título
         * no esté vacío.
         */
        if (
            $titulo === ''
        ) {

            $this->setFlash(
                'error',
                'El título de la serie es obligatorio.'
            );


            header(
                'Location: /incuyo/cyberblog/public/admin/series/create'
            );


            exit;
        }


        /*
         * Validamos que el estado
         * recibido sea uno de los permitidos.
         */
        if (
            !in_array(
                $estado,
                [
                    'borrador',
                    'publicada'
                ],
                true
            )
        ) {

            $estado =
                'borrador';
        }


        /*
         * Generamos el slug base
         * a partir del título.
         */
        $baseSlug =
            $this->generateSlug(
                $titulo
            );


        /*
         * Nos aseguramos de que el slug
         * sea único.
         */
        $slug =
            $this->seriesModel->generateUniqueSlug(
                $baseSlug
            );


        /*
         * Preparamos los datos
         * que serán enviados al modelo.
         */
        $data = [

            'titulo' =>
                $titulo,

            'slug' =>
                $slug,

            'descripcion' =>
                (
                    $descripcion !== ''
                        ? $descripcion
                        : null
                ),

            'imagen' =>
                null,

            'estado' =>
                $estado
        ];


        /*
         * Creamos la serie.
         */
        $created =
            $this->seriesModel->create(
                $data
            );


        /*
         * Verificamos el resultado.
         */
        if (
            $created === false
        ) {

            $this->setFlash(
                'error',
                'No fue posible crear la serie.'
            );


            header(
                'Location: /incuyo/cyberblog/public/admin/series/create'
            );


            exit;
        }


        /*
         * Guardamos un mensaje
         * de éxito en la sesión.
         */
        $this->setFlash(
            'success',
            'Serie creada correctamente.'
        );


        /*
         * Redirigimos al listado administrativo.
         */
        header(
            'Location: /incuyo/cyberblog/public/admin/series'
        );


        exit;
    }


    /**
     * Muestra el formulario de edición
     * de una serie.
     */
    public function edit(
        int $id
    ): void
    {
        /*
         * Solo administradores y editores
         * pueden editar series.
         */
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);


        /*
         * Buscamos la serie.
         */
        $serie =
            $this->seriesModel->getSeriesById(
                $id
            );


        /*
         * Si no existe,
         * devolvemos un error 404.
         */
        if (
            $serie === false
        ) {

            http_response_code(404);

            die(
                'La serie solicitada no existe.'
            );
        }


        /*
         * Cargamos la vista administrativa.
         */
        $this->adminView(
            'series/edit',
            [
                'title' =>
                    'Editar Serie',

                'serie' =>
                    $serie
            ]
        );
    }


    /**
     * Actualiza una serie existente.
     */
    public function update(
        int $id
    ): void
    {
        /*
         * Solo administradores y editores
         * pueden actualizar series.
         */
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);


        /*
         * Verificamos que la serie exista.
         */
        $serie =
            $this->seriesModel->getSeriesById(
                $id
            );


        if (
            $serie === false
        ) {

            http_response_code(404);

            die(
                'La serie solicitada no existe.'
            );
        }


        /*
         * Obtenemos los datos enviados.
         */
        $titulo =
            trim(
                $_POST['titulo'] ?? ''
            );


        $descripcion =
            trim(
                $_POST['descripcion'] ?? ''
            );


        $estado =
            $_POST['estado']
            ?? 'borrador';


        /*
         * Validamos el título.
         */
        if (
            $titulo === ''
        ) {

            $this->setFlash(
                'error',
                'El título de la serie es obligatorio.'
            );


            header(
                'Location: /incuyo/cyberblog/public/admin/series/edit/' . $id
            );


            exit;
        }


        /*
         * Validamos el estado.
         */
        if (
            !in_array(
                $estado,
                [
                    'borrador',
                    'publicada'
                ],
                true
            )
        ) {

            $estado =
                'borrador';
        }


        /*
         * Generamos el nuevo slug.
         */
        $baseSlug =
            $this->generateSlug(
                $titulo
            );


        /*
         * Generamos un slug único,
         * excluyendo la serie actual.
         */
        $slug =
            $this->seriesModel->generateUniqueSlug(
                $baseSlug,
                $id
            );


        /*
         * Conservamos la imagen actual.
         */
        $data = [

            'titulo' =>
                $titulo,

            'slug' =>
                $slug,

            'descripcion' =>
                (
                    $descripcion !== ''
                        ? $descripcion
                        : null
                ),

            'imagen' =>
                $serie['imagen'],

            'estado' =>
                $estado
        ];


        /*
         * Actualizamos la serie.
         */
        $updated =
            $this->seriesModel->update(
                $id,
                $data
            );


        /*
         * Verificamos el resultado.
         */
        if (
            $updated === false
        ) {

            $this->setFlash(
                'error',
                'No fue posible actualizar la serie.'
            );


            header(
                'Location: /incuyo/cyberblog/public/admin/series/edit/' . $id
            );


            exit;
        }


        /*
         * Mensaje de éxito.
         */
        $this->setFlash(
            'success',
            'Serie actualizada correctamente.'
        );


        /*
         * Volvemos al listado.
         */
        header(
            'Location: /incuyo/cyberblog/public/admin/series'
        );


        exit;
    }


    /**
     * Elimina una serie.
     *
     * Solo administradores pueden realizar
     * esta operación.
     */
    public function delete(
        int $id
    ): void
    {
        /*
         * Solo administradores.
         */
        RoleMiddleware::handle([
            'admin'
        ]);


        /*
         * Obtenemos la serie.
         */
        $serie =
            $this->seriesModel->getSeriesById(
                $id
            );


        /*
         * Si no existe,
         * devolvemos un error 404.
         */
        if (
            $serie === false
        ) {

            http_response_code(404);

            die(
                'La serie solicitada no existe.'
            );
        }


        /*
         * Eliminamos la serie.
         *
         * ON DELETE SET NULL evita que
         * los artículos asociados sean eliminados.
         */
        $deleted =
            $this->seriesModel->delete(
                $id
            );


        /*
         * Verificamos el resultado.
         */
        if (
            $deleted === false
        ) {

            $this->setFlash(
                'error',
                'No fue posible eliminar la serie.'
            );


            header(
                'Location: /incuyo/cyberblog/public/admin/series'
            );


            exit;
        }


        /*
         * Mensaje de éxito.
         */
        $this->setFlash(
            'success',
            'Serie eliminada correctamente.'
        );


        /*
         * Redirección.
         */
        header(
            'Location: /incuyo/cyberblog/public/admin/series'
        );


        exit;
    }


    /* =========================================================
     * UTILIDADES
     * ========================================================= */


    /**
     * Convierte un texto en un slug.
     *
     * Ejemplo:
     *
     * Introducción a Wazuh
     *
     * se convierte en:
     *
     * introduccion-a-wazuh
     */
    private function generateSlug(
        string $text
    ): string
    {
        /*
         * Convertimos caracteres especiales
         * a su representación ASCII.
         */
        $converted =
            iconv(
                'UTF-8',
                'ASCII//TRANSLIT',
                $text
            );


        /*
         * Si iconv funcionó,
         * utilizamos el texto convertido.
         */
        if (
            $converted !== false
        ) {

            $text =
                $converted;
        }


        /*
         * Convertimos todo a minúsculas.
         */
        $text =
            strtolower(
                $text
            );


        /*
         * Reemplazamos cualquier grupo
         * de caracteres que no sean letras
         * o números por un guion.
         */
        $text =
            preg_replace(
                '/[^a-z0-9]+/',
                '-',
                $text
            );


        /*
         * Eliminamos los guiones
         * del principio y del final.
         */
        return trim(
            $text ?? '',
            '-'
        );
    }
}
