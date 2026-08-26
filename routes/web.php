<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use App\Controllers\BlogController;
use App\Controllers\CategoryController;
use App\Controllers\DashboardController;
use App\Controllers\ArticleController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\UploadController;
use App\Controllers\SeriesController;


/*
|--------------------------------------------------------------------------
| Rutas de la aplicación
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| FrontOffice
|--------------------------------------------------------------------------
*/

$router->get('/', [
    HomeController::class,
    'index'
]);


$router->get('/blog', [
    BlogController::class,
    'index'
]);


/*
 * Buscador de artículos.
 */
$router->get('/blog/search', [
    BlogController::class,
    'search'
]);


/*
 * Vista individual de un artículo.
 *
 * Esta ruta debe permanecer después de /blog/search
 * para evitar que "search" sea interpretado como un slug.
 */
$router->get('/blog/{slug}', [
    BlogController::class,
    'show'
]);


$router->get('/category/{slug}', [
    CategoryController::class,
    'show'
]);


/*
|--------------------------------------------------------------------------
| Administración
|--------------------------------------------------------------------------
*/

$router->get('/admin', [
    DashboardController::class,
    'index'
]);


/*
|--------------------------------------------------------------------------
| Artículos
|--------------------------------------------------------------------------
*/


/*
 * Listado de artículos.
 */
$router->get('/admin/articles', [
    ArticleController::class,
    'index'
]);


/*
 * Formulario para crear un artículo.
 */
$router->get('/admin/articles/create', [
    ArticleController::class,
    'create'
]);


/*
 * Procesar creación de artículo.
 */
$router->post('/admin/articles', [
    ArticleController::class,
    'store'
]);


/*
 * Formulario para editar un artículo.
 */
$router->get('/admin/articles/edit/{id}', [
    ArticleController::class,
    'edit'
]);


/*
 * Procesar edición de artículo.
 */
$router->post('/admin/articles/update/{id}', [
    ArticleController::class,
    'update'
]);


/*
 * Eliminar artículo.
 *
 * Usamos POST porque es una operación destructiva.
 */
$router->post('/admin/articles/delete/{id}', [
    ArticleController::class,
    'delete'
]);


/*
|--------------------------------------------------------------------------
| Series
|--------------------------------------------------------------------------
*/


/*
 * Listado de series.
 */
$router->get('/admin/series', [
    SeriesController::class,
    'index'
]);


/*
 * Formulario para crear una nueva serie.
 */
$router->get('/admin/series/create', [
    SeriesController::class,
    'create'
]);


/*
 * Procesar creación de una nueva serie.
 */
$router->post('/admin/series', [
    SeriesController::class,
    'store'
]);


/*
 * Formulario para editar una serie.
 */
$router->get('/admin/series/edit/{id}', [
    SeriesController::class,
    'edit'
]);


/*
 * Procesar edición de una serie.
 */
$router->post('/admin/series/update/{id}', [
    SeriesController::class,
    'update'
]);


/*
 * Eliminar una serie.
 *
 * Se utiliza POST porque es una operación destructiva.
 *
 * La relación ON DELETE SET NULL garantiza que
 * los artículos asociados no sean eliminados.
 */
$router->post('/admin/series/delete/{id}', [
    SeriesController::class,
    'delete'
]);


/*
|--------------------------------------------------------------------------
| Subida de imágenes desde el editor
|--------------------------------------------------------------------------
*/

$router->post('/admin/upload/image', [
    UploadController::class,
    'uploadImage'
]);


/*
|--------------------------------------------------------------------------
| Usuarios
|--------------------------------------------------------------------------
*/


/*
 * Listado de usuarios.
 */
$router->get('/admin/users', [
    UserController::class,
    'index'
]);


/*
 * Formulario para crear usuario.
 */
$router->get('/admin/users/create', [
    UserController::class,
    'create'
]);


/*
 * Procesar creación de usuario.
 */
$router->post('/admin/users', [
    UserController::class,
    'store'
]);


/*
 * Formulario para editar usuario.
 */
$router->get('/admin/users/edit/{id}', [
    UserController::class,
    'edit'
]);


/*
 * Procesar edición de usuario.
 */
$router->post('/admin/users/update/{id}', [
    UserController::class,
    'update'
]);


/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/


/*
 * Formulario de login.
 */
$router->get('/login', [
    AuthController::class,
    'login'
]);


/*
 * Procesar login.
 */
$router->post('/login', [
    AuthController::class,
    'authenticate'
]);


/*
 * Cerrar sesión.
 */
$router->get('/logout', [
    AuthController::class,
    'logout'
]);