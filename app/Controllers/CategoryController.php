<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(string $slug): void
    {
        $categoryModel = new Category();

        $category = $categoryModel->getCategoryBySlug($slug);

        if ($category === false) {

            http_response_code(404);

            die('Categoría no encontrada.');
        }

        $articles = $categoryModel->getArticlesByCategory($slug);

        $this->view('blog/category', [
            'category' => $category,
            'articles' => $articles
        ]);
    }
}