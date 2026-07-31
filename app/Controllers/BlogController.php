<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;

class BlogController extends Controller
{
    public function index(): void
    {
        $articleModel = new Article();

        $articles = $articleModel->getPublishedArticles();

        $this->view('blog/index', [
            'articles' => $articles
        ]);
    }

    public function show(string $slug): void
    {
        $articleModel = new Article();

        $article = $articleModel->getArticleBySlug($slug);

        if ($article === false) {

            http_response_code(404);

            die('Artículo no encontrado.');
        }

        $this->view('blog/show', [
            'article' => $article
        ]);
    }
}