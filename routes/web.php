<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\CategoryController;
use App\Controllers\DashboardController;
use App\Controllers\ArticleController;
use App\Controllers\AuthController;
use App\Middleware\RoleMiddleware;
use App\Controllers\UserController;

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

// Administración de usuarios
$router->get('/admin/users', [UserController::class, 'index']);

// Muestra el formulario para editar un usuario.
$router->get('/admin/users/edit/{id}', [UserController::class, 'edit']);

$router->post('/admin/articles', [ArticleController::class, 'store']);

$router->get('/admin/articles/create', [ArticleController::class, 'create']);

$router->get('/admin/articles/edit/{id}', [ArticleController::class, 'edit']);

// Recibe el formulario de edición de usuarios.
$router->post(
    '/admin/users/update/{id}',
    [UserController::class, 'update']
);

// Recibe el formulario de edición y envía el ID del artículo al controlador
$router->post('/admin/articles/update/{id}', [ArticleController::class, 'update']);

// Ruta para eliminar un artículo (por ahora solo recibirá el ID)
$router->get('/admin/articles/delete/{id}', [ArticleController::class, 'delete']);

// Muestra el formulario de inicio de sesión
$router->get('/login', [AuthController::class, 'login']);

// Recibe las credenciales enviadas por el formulario de login
$router->post('/login', [AuthController::class, 'authenticate']);

// Cierra la sesión del usuario.
$router->get('/logout', [AuthController::class, 'logout']);

/* ---------- PRUEBA ---------- */

$router->get('/categoria-prueba', function () {
    die('Categoria OK');
});