<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Series;
use App\Models\User;

class DashboardController extends Controller
{
    /**
     * Muestra el Dashboard administrativo.
     *
     * Obtiene las estadísticas principales
     * del sistema y los artículos más recientes.
     */
    public function index(): void
    {
        /*
         * Inicializamos los modelos necesarios.
         */
        $articleModel = new Article();
        $categoryModel = new Category();
        $seriesModel = new Series();
        $userModel = new User();


        /*
         * Obtenemos las estadísticas de artículos.
         */
        $totalArticles =
            $articleModel->countAllArticles();

        $publishedArticles =
            $articleModel->countPublishedArticles();

        $draftArticles =
            $articleModel->countDraftArticles();


        /*
         * Obtenemos las estadísticas
         * del resto del sistema.
         */
        $totalCategories =
            $categoryModel->countAllCategories();

        $totalSeries =
            $seriesModel->countAllSeries();

        $totalUsers =
            $userModel->countAllUsers();


        /*
         * Obtenemos los artículos más recientes.
         */
        $recentArticles =
            $articleModel->getRecentArticles(5);


        /*
         * Enviamos todos los datos a la vista.
         */
        $this->adminView(
            'dashboard/index',
            [
                'title' =>
                    'Dashboard',

                'totalArticles' =>
                    $totalArticles,

                'publishedArticles' =>
                    $publishedArticles,

                'draftArticles' =>
                    $draftArticles,

                'totalCategories' =>
                    $totalCategories,

                'totalSeries' =>
                    $totalSeries,

                'totalUsers' =>
                    $totalUsers,

                'recentArticles' =>
                    $recentArticles
            ]
        );
    }
}