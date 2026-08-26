<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Series;
use App\Middleware\RoleMiddleware;


/**
 * Controlador encargado de administrar las series
 * de artículos desde el panel administrativo.
 *
 * Este controlador corresponde únicamente al BackOffice.
 *
 * La visualización pública de las series se implementará
 * posteriormente mediante rutas y métodos públicos
 * independientes.
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
         * incluyendo borradores y publicadas,
         * ya que estamos dentro del panel
         * administrativo.
         */
        $series = $this->seriesModel->getAllSeries();


        /*
         * Cargamos la vista utilizando
         * el layout administrativo.
         */
        $this->adminView(
            'series/index',
            [
                'title' => 'Administración de Series',
                'series' => $series
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
         * Cargamos el formulario utilizando
         * el layout administrativo.
         */
        $this->adminView(
            'series/create',
            [
                'title' => 'Nueva Serie'
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
         * los datos enviados.
         */
        $titulo = trim(
            $_POST['titulo'] ?? ''
        );

        $descripcion = trim(
            $_POST['descripcion'] ?? ''
        );

        $estado = $_POST['estado']
            ?? 'borrador';


        /*
         * Validamos que el título
         * no esté vacío.
         */
        if ($titulo === '') {

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
         * Validamos el estado recibido.
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
            $estado = 'borrador';
        }


        /*
         * Generamos el slug base.
         */
        $baseSlug = $this->generateSlug(
            $titulo
        );


        /*
         * Generamos un slug único.
         */
        $slug = $this->seriesModel->generateUniqueSlug(
            $baseSlug
        );


        /*
         * Por ahora la imagen queda
         * sin implementar.
         */
        $data = [
            'titulo' => $titulo,
            'slug' => $slug,
            'descripcion' => (
                $descripcion !== ''
                    ? $descripcion
                    : null
            ),
            'imagen' => null,
            'estado' => $estado
        ];


        /*
         * Creamos la serie.
         */
        $created = $this->seriesModel->create(
            $data
        );


        /*
         * Verificamos que la creación
         * se haya realizado correctamente.
         */
        if ($created === false) {

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
         * Informamos que la serie
         * fue creada correctamente.
         */
        $this->setFlash(
            'success',
            'Serie creada correctamente.'
        );


        /*
         * Redirigimos al listado
         * administrativo.
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
         * Obtenemos la serie.
         */
        $serie = $this->seriesModel->getSeriesById(
            $id
        );


        /*
         * Si la serie no existe,
         * mostramos un error 404.
         */
        if ($serie === false) {

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
                'title' => 'Editar Serie',
                'serie' => $serie
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
        $serie = $this->seriesModel->getSeriesById(
            $id
        );


        if ($serie === false) {

            http_response_code(404);

            die(
                'La serie solicitada no existe.'
            );
        }


        /*
         * Obtenemos los datos enviados.
         */
        $titulo = trim(
            $_POST['titulo'] ?? ''
        );

        $descripcion = trim(
            $_POST['descripcion'] ?? ''
        );

        $estado = $_POST['estado']
            ?? 'borrador';


        /*
         * Validamos el título.
         */
        if ($titulo === '') {

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
            $estado = 'borrador';
        }


        /*
         * Generamos nuevamente el slug.
         */
        $baseSlug = $this->generateSlug(
            $titulo
        );


        /*
         * Generamos un slug único
         * excluyendo la serie actual.
         */
        $slug = $this->seriesModel->generateUniqueSlug(
            $baseSlug,
            $id
        );


        /*
         * Conservamos la imagen actual.
         *
         * La subida de imágenes para
         * Series será implementada
         * posteriormente.
         */
        $data = [
            'titulo' => $titulo,
            'slug' => $slug,
            'descripcion' => (
                $descripcion !== ''
                    ? $descripcion
                    : null
            ),
            'imagen' => $serie['imagen'],
            'estado' => $estado
        ];


        /*
         * Actualizamos la serie.
         */
        $updated = $this->seriesModel->update(
            $id,
            $data
        );


        /*
         * Verificamos el resultado.
         */
        if ($updated === false) {

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
         * Informamos que la actualización
         * fue correcta.
         */
        $this->setFlash(
            'success',
            'Serie actualizada correctamente.'
        );


        /*
         * Redirigimos al listado.
         */
        header(
            'Location: /incuyo/cyberblog/public/admin/series'
        );

        exit;
    }


    /**
     * Elimina una serie.
     *
     * Solo los administradores pueden
     * realizar esta operación.
     */
    public function delete(
        int $id
    ): void
    {
        /*
         * Solo los administradores
         * pueden eliminar series.
         */
        RoleMiddleware::handle([
            'admin'
        ]);


        /*
         * Obtenemos la serie.
         */
        $serie = $this->seriesModel->getSeriesById(
            $id
        );


        /*
         * Si no existe,
         * devolvemos un 404.
         */
        if ($serie === false) {

            http_response_code(404);

            die(
                'La serie solicitada no existe.'
            );
        }


        /*
         * Eliminamos la serie.
         *
         * Debido a ON DELETE SET NULL,
         * los artículos asociados conservarán
         * sus datos, pero quedarán
         * sin serie asignada.
         */
        $deleted = $this->seriesModel->delete(
            $id
        );


        /*
         * Verificamos el resultado.
         */
        if ($deleted === false) {

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
         * Informamos que la eliminación
         * fue correcta.
         */
        $this->setFlash(
            'success',
            'Serie eliminada correctamente.'
        );


        /*
         * Redirigimos al listado.
         */
        header(
            'Location: /incuyo/cyberblog/public/admin/series'
        );

        exit;
    }


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
        $converted = iconv(
            'UTF-8',
            'ASCII//TRANSLIT',
            $text
        );


        /*
         * Si iconv falla, conservamos
         * el texto original.
         */
        if ($converted !== false) {
            $text = $converted;
        }


        /*
         * Convertimos a minúsculas.
         */
        $text = strtolower(
            $text
        );


        /*
         * Reemplazamos grupos de caracteres
         * no alfanuméricos por un guion.
         */
        $text = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            $text
        );


        /*
         * Eliminamos los guiones iniciales
         * y finales.
         */
        return trim(
            $text ?? '',
            '-'
        );
    }
}