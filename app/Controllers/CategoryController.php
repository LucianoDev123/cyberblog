<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;
use App\Middleware\RoleMiddleware;

class CategoryController extends Controller
{
    /**
     * Modelo de categorías.
     */
    private Category $categoryModel;


    /**
     * Inicializa el modelo.
     */
    public function __construct()
    {
        $this->categoryModel =
            new Category();
    }


    /* =========================================================
     * PARTE ADMINISTRATIVA
     * ========================================================= */


    /**
     * Muestra el listado administrativo
     * de categorías.
     */
    public function index(): void
    {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $categories =
            $this->categoryModel
                ->getAllCategories();

        $this->adminView(
            'categories/index',
            [
                'title' =>
                    'Administración de Categorías',

                'categories' =>
                    $categories
            ]
        );
    }


    /**
     * Muestra el formulario para crear
     * una nueva categoría.
     */
    public function create(): void
    {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $oldInput =
            $this->getOldInput();

        $this->adminView(
            'categories/create',
            [
                'title' =>
                    'Nueva Categoría',

                'oldInput' =>
                    $oldInput
            ]
        );
    }


    /**
     * Guarda una nueva categoría.
     */
    public function store(): void
    {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $this->verifyCsrfToken();


        $nombre =
            trim(
                $_POST['nombre'] ?? ''
            );

        $descripcion =
            trim(
                $_POST['descripcion'] ?? ''
            );


        /*
         * Guardamos los datos introducidos
         * para poder recuperarlos si ocurre
         * un error de validación.
         */
        $oldInput = [
            'nombre' =>
                $nombre,

            'descripcion' =>
                $descripcion
        ];


        /*
         * Validamos el nombre.
         */
        if ($nombre === '') {

            $this->setOldInput(
                $oldInput
            );

            $this->setFlash(
                'error',
                'El nombre de la categoría es obligatorio.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/categories/create'
            );

            exit;
        }


        /*
         * Validamos la longitud.
         */
        if (strlen($nombre) > 100) {

            $this->setOldInput(
                $oldInput
            );

            $this->setFlash(
                'error',
                'El nombre de la categoría no puede superar los 100 caracteres.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/categories/create'
            );

            exit;
        }


        /*
         * Generamos el slug.
         */
        $baseSlug =
            $this->generateSlug(
                $nombre
            );

        $slug =
            $this->categoryModel
                ->generateUniqueSlug(
                    $baseSlug
                );


        /*
         * Creamos la categoría.
         */
        $created =
            $this->categoryModel->create([
                'nombre' =>
                    $nombre,

                'slug' =>
                    $slug,

                'descripcion' =>
                    (
                        $descripcion !== ''
                            ? $descripcion
                            : null
                    )
            ]);


        if (!$created) {

            $this->setOldInput(
                $oldInput
            );

            $this->setFlash(
                'error',
                'No fue posible crear la categoría.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/categories/create'
            );

            exit;
        }


        $this->setFlash(
            'success',
            'Categoría creada correctamente.'
        );

        header(
            'Location: /incuyo/cyberblog/public/admin/categories'
        );

        exit;
    }


    /**
     * Muestra el formulario para editar
     * una categoría existente.
     */
    public function edit(
        int $id
    ): void {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $category =
            $this->categoryModel
                ->getCategoryById(
                    $id
                );


        if ($category === false) {

            http_response_code(404);

            die(
                'Categoría no encontrada.'
            );
        }


        $oldInput =
            $this->getOldInput();


        $this->adminView(
            'categories/edit',
            [
                'title' =>
                    'Editar Categoría',

                'category' =>
                    $category,

                'oldInput' =>
                    $oldInput
            ]
        );
    }


    /**
     * Actualiza una categoría existente.
     */
    public function update(
        int $id
    ): void {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $this->verifyCsrfToken();


        $category =
            $this->categoryModel
                ->getCategoryById(
                    $id
                );


        if ($category === false) {

            http_response_code(404);

            die(
                'Categoría no encontrada.'
            );
        }


        $nombre =
            trim(
                $_POST['nombre'] ?? ''
            );

        $descripcion =
            trim(
                $_POST['descripcion'] ?? ''
            );


        $oldInput = [
            'nombre' =>
                $nombre,

            'descripcion' =>
                $descripcion
        ];


        /*
         * Validamos el nombre.
         */
        if ($nombre === '') {

            $this->setOldInput(
                $oldInput
            );

            $this->setFlash(
                'error',
                'El nombre de la categoría es obligatorio.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/categories/edit/{$id}"
            );

            exit;
        }


        /*
         * Validamos la longitud.
         */
        if (strlen($nombre) > 100) {

            $this->setOldInput(
                $oldInput
            );

            $this->setFlash(
                'error',
                'El nombre de la categoría no puede superar los 100 caracteres.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/categories/edit/{$id}"
            );

            exit;
        }


        /*
         * Generamos un slug único,
         * excluyendo la categoría actual.
         */
        $baseSlug =
            $this->generateSlug(
                $nombre
            );

        $slug =
            $this->categoryModel
                ->generateUniqueSlug(
                    $baseSlug,
                    $id
                );


        /*
         * Actualizamos la categoría.
         */
        $updated =
            $this->categoryModel->update(
                $id,
                [
                    'nombre' =>
                        $nombre,

                    'slug' =>
                        $slug,

                    'descripcion' =>
                        (
                            $descripcion !== ''
                                ? $descripcion
                                : null
                        )
                ]
            );


        if (!$updated) {

            $this->setOldInput(
                $oldInput
            );

            $this->setFlash(
                'error',
                'No fue posible actualizar la categoría.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/categories/edit/{$id}"
            );

            exit;
        }


        $this->setFlash(
            'success',
            'Categoría actualizada correctamente.'
        );

        header(
            'Location: /incuyo/cyberblog/public/admin/categories'
        );

        exit;
    }


    /**
     * Elimina una categoría.
     *
     * No se permite eliminar categorías
     * que tengan artículos asociados.
     */
    public function delete(
        int $id
    ): void {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $this->verifyCsrfToken();


        $category =
            $this->categoryModel
                ->getCategoryById(
                    $id
                );


        if ($category === false) {

            http_response_code(404);

            die(
                'Categoría no encontrada.'
            );
        }


        /*
         * Comprobamos si existen artículos
         * asociados a la categoría.
         */
        $totalArticles =
            $this->categoryModel
                ->countArticles(
                    $id
                );


        if ($totalArticles > 0) {

            $this->setFlash(
                'error',
                'No se puede eliminar la categoría porque tiene '
                . $totalArticles
                . ' '
                . (
                    $totalArticles === 1
                        ? 'artículo'
                        : 'artículos'
                )
                . ' asociado'
                . (
                    $totalArticles === 1
                        ? ''
                        : 's'
                )
                . '.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/categories'
            );

            exit;
        }


        /*
         * Eliminamos la categoría.
         */
        $deleted =
            $this->categoryModel
                ->delete(
                    $id
                );


        if (!$deleted) {

            $this->setFlash(
                'error',
                'No fue posible eliminar la categoría.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/categories'
            );

            exit;
        }


        $this->setFlash(
            'success',
            'Categoría eliminada correctamente.'
        );

        header(
            'Location: /incuyo/cyberblog/public/admin/categories'
        );

        exit;
    }


    /* =========================================================
     * PARTE PÚBLICA
     * ========================================================= */


    /**
     * Muestra los artículos de una categoría.
     */
    public function show(
        string $slug
    ): void {
        $category =
            $this->categoryModel
                ->getCategoryBySlug(
                    $slug
                );


        if ($category === false) {

            http_response_code(404);

            die(
                'Categoría no encontrada.'
            );
        }


        $articles =
            $this->categoryModel
                ->getArticlesByCategory(
                    $slug
                );


        $this->view(
            'blog/category',
            [
                'title' =>
                    'Categoría: '
                    . $category['nombre'],

                'category' =>
                    $category,

                'articles' =>
                    $articles
            ]
        );
    }


    /* =========================================================
     * UTILIDADES
     * ========================================================= */


    /**
     * Genera un slug a partir de un texto.
     */
    private function generateSlug(
        string $text
    ): string {
        $text =
            trim(
                $text
            );


        /*
         * Intentamos convertir caracteres
         * acentuados a ASCII.
         */
        $converted =
            iconv(
                'UTF-8',
                'ASCII//TRANSLIT//IGNORE',
                $text
            );


        if ($converted !== false) {
            $text =
                $converted;
        }


        /*
         * Convertimos a minúsculas.
         */
        $text =
            strtolower(
                $text
            );


        /*
         * Reemplazamos cualquier grupo
         * de caracteres no alfanuméricos
         * por un guion.
         */
        $text =
            preg_replace(
                '/[^a-z0-9]+/',
                '-',
                $text
            )
            ?? '';


        /*
         * Eliminamos guiones sobrantes
         * al principio y al final.
         */
        $text =
            trim(
                $text,
                '-'
            );


        /*
         * Si por alguna razón el resultado
         * queda vacío, utilizamos un valor
         * seguro.
         */
        if ($text === '') {
            $text = 'categoria';
        }


        return $text;
    }
}