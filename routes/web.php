<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\CategoryController;
use App\Controllers\DashboardController;
use App\Controllers\ArticleController;

/*
|--------------------------------------------------------------------------
| Rutas de la aplicación
|--------------------------------------------------------------------------
*/

$router->get('/', [HomeController::class, 'index']);

$router->get('/blog', [BlogController::class, 'index']);

$router->get('/blog/{slug}', [BlogController::class, 'show']);

$router->get('/category/{slug}', [CategoryController::class, 'show']);

$router->get('/admin', [DashboardController::class, 'index']);

$router->get('/admin/articles', [ArticleController::class, 'index']);

$router->post('/admin/articles', [ArticleController::class, 'store']);

$router->get('/admin/articles/create', [ArticleController::class, 'create']);

$router->get('/admin/articles/edit/{id}', [ArticleController::class, 'edit']);

// Recibe el formulario de edición y envía el ID del artículo al controlador
$router->post('/admin/articles/update/{id}', [ArticleController::class, 'update']);

// Ruta para eliminar un artículo (por ahora solo recibirá el ID)
$router->get('/admin/articles/delete/{id}', [ArticleController::class, 'delete']);

/* ---------- PRUEBA ---------- */

$router->get('/categoria-prueba', function () {
    die('Categoria OK');
});