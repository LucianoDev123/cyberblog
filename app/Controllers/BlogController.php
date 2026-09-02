<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Series;

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


    public function search(): void
    {
        $search = trim(
            $_GET['q'] ?? ''
        );

        $articles = [];

        if ($search !== '') {

            $articleModel = new Article();

            $articles = $articleModel
                ->searchPublishedArticles($search);
        }

        $this->view('blog/search', [
            'search' => $search,
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


        /*
         * Por defecto el artículo no tendrá
         * navegación de serie.
         */
        $seriesNavigation = null;


        /*
         * Si el artículo pertenece a una serie,
         * obtenemos la serie y sus artículos
         * publicados en orden cronológico.
         */
        if (
            !empty($article['serie_id'])
        ) {

            $seriesModel = new Series();

            $series = $seriesModel->getSeriesById(
                (int) $article['serie_id']
            );


            /*
             * Una serie debe seguir publicada
             * para mostrar su navegación pública.
             */
            if (
                $series !== false &&
                ($series['estado'] ?? '') === 'publicada'
            ) {

                $seriesArticles =
                    $seriesModel
                        ->getPublishedArticlesBySeriesId(
                            (int) $series['id']
                        );


                /*
                 * Buscamos la posición del artículo
                 * actual dentro de la serie.
                 */
                $currentIndex = null;

                foreach (
                    $seriesArticles
                    as $index => $seriesArticle
                ) {

                    if (
                        (int) $seriesArticle['id'] ===
                        (int) $article['id']
                    ) {

                        $currentIndex = $index;

                        break;
                    }
                }


                /*
                 * Solo construimos la navegación
                 * si el artículo actual pertenece
                 * realmente al listado publicado.
                 */
                if (
                    $currentIndex !== null
                ) {

                    $totalArticles =
                        count($seriesArticles);

                    $previous = null;
                    $next = null;


                    /*
                     * Artículo anterior.
                     */
                    if (
                        $currentIndex > 0
                    ) {

                        $previous =
                            $seriesArticles[
                                $currentIndex - 1
                            ];
                    }


                    /*
                     * Artículo siguiente.
                     */
                    if (
                        $currentIndex <
                        ($totalArticles - 1)
                    ) {

                        $next =
                            $seriesArticles[
                                $currentIndex + 1
                            ];
                    }


                    $seriesNavigation = [
                        'serie' => $series,

                        'current' =>
                            $currentIndex + 1,

                        'total' =>
                            $totalArticles,

                        'previous' =>
                            $previous,

                        'next' =>
                            $next
                    ];
                }
            }
        }


        $this->view('blog/show', [
            'article' =>
                $article,

            'seriesNavigation' =>
                $seriesNavigation
        ]);
    }
}