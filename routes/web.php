<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Rutas de la aplicación
|--------------------------------------------------------------------------
*/

$router->get('/', [HomeController::class, 'index']);

$router->get('/blog', [BlogController::class, 'index']);

$router->get('/blog/{slug}', [BlogController::class, 'show']);

$router->get('/category/{slug}', [CategoryController::class, 'show']);

/* ---------- PRUEBA ---------- */

$router->get('/categoria-prueba', function () {
    die('Categoria OK');
});