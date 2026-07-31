<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\BlogController;

/*
|--------------------------------------------------------------------------
| Rutas de la aplicación
|--------------------------------------------------------------------------
*/

$router->get('/', [HomeController::class, 'index']);

$router->get('/blog', [BlogController::class, 'index']);

$router->get('/blog/{slug}', [BlogController::class, 'show']);