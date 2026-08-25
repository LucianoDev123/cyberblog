<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Muestra los artículos de una categoría.
     */
    public function show(string $slug): void
    {
        $categoryModel =
            new Category();


        /*
         * Buscamos la categoría.
         */
        $category =
            $categoryModel->getCategoryBySlug(
                $slug
            );


        if ($category === false) {

            http_response_code(404);

            die(
                'Categoría no encontrada.'
            );
        }


        /*
         * Obtenemos los artículos publicados
         * pertenecientes a la categoría.
         */
        $articles =
            $categoryModel->getArticlesByCategory(
                $slug
            );


        /*
         * Mostramos la vista.
         */
        $this->view('blog/category', [
            'title' =>
                'Categoría: '
                . $category['nombre'],

            'category' =>
                $category,

            'articles' =>
                $articles
        ]);
    }
}