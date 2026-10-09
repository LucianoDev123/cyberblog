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
use App\Controllers\SecurityAnalyzerController;
use App\Controllers\AccountController;
use App\Controllers\CommentController;
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
 * Esta ruta debe permanecer después
 * de /blog/search para evitar que
 * "search" sea interpretado como slug.
 */
$router->get('/blog/{slug}', [
    BlogController::class,
    'show'
]);

/*
 * Procesar el envío de un comentario.
 *
 * Los comentarios quedan pendientes de moderación.
 * La ruta debe utilizar POST porque modifica datos.
 */
$router->post('/blog/{slug}/comments', [
    BlogController::class,
    'storeComment'
]);

/*
 * Vista individual de una categoría.
 */
$router->get('/category/{slug}', [
    CategoryController::class,
    'show'
]);


/*
|--------------------------------------------------------------------------
| Series públicas
|--------------------------------------------------------------------------
*/


/*
 * Listado público de series.
 *
 * URL:
 *
 * /series
 */
$router->get('/series', [
    SeriesController::class,
    'publicIndex'
]);


/*
 * Vista pública individual de una serie.
 *
 * Ejemplo:
 *
 * /series/introduccion-a-wazuh
 *
 * IMPORTANTE:
 *
 * Esta ruta debe estar después de /series
 * para que "/series" no sea interpretado
 * como un slug.
 */
$router->get('/series/{slug}', [
    SeriesController::class,
    'publicShow'
]);


/*
|--------------------------------------------------------------------------
| Security Analyzer
|--------------------------------------------------------------------------
*/

$router->post('/api/security-analyzer', [
    SecurityAnalyzerController::class,
    'analyze'
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
| Moderación de comentarios
|--------------------------------------------------------------------------
*/

/*
 * Listado de comentarios pendientes.
 */
$router->get('/admin/comments', [
    CommentController::class,
    'index'
]);

/*
 * Aprobar un comentario.
 */
$router->post('/admin/comments/approve/{id}', [
    CommentController::class,
    'approve'
]);

/*
 * Eliminar un comentario.
 */
$router->post('/admin/comments/delete/{id}', [
    CommentController::class,
    'delete'
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
| Categorías administrativas
|--------------------------------------------------------------------------
*/


/*
 * Listado de categorías.
 */
$router->get('/admin/categories', [
    CategoryController::class,
    'index'
]);


/*
 * Formulario para crear una categoría.
 */
$router->get('/admin/categories/create', [
    CategoryController::class,
    'create'
]);


/*
 * Procesar creación de una categoría.
 */
$router->post('/admin/categories', [
    CategoryController::class,
    'store'
]);


/*
 * Formulario para editar una categoría.
 */
$router->get('/admin/categories/edit/{id}', [
    CategoryController::class,
    'edit'
]);


/*
 * Procesar edición de una categoría.
 */
$router->post('/admin/categories/update/{id}', [
    CategoryController::class,
    'update'
]);


/*
 * Eliminar una categoría.
 *
 * Se utiliza POST porque es una operación destructiva.
 */
$router->post('/admin/categories/delete/{id}', [
    CategoryController::class,
    'delete'
]);


/*
|--------------------------------------------------------------------------
| Series administrativas
|--------------------------------------------------------------------------
*/


/*
 * Listado administrativo de series.
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
 * La relación ON DELETE SET NULL garantiza
 * que los artículos asociados no sean eliminados.
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
 * Eliminar usuario.
 *
 * Se utiliza POST porque es una operación destructiva.
 */
$router->post('/admin/users/delete/{id}', [
    UserController::class,
    'delete'
]);


/*
|--------------------------------------------------------------------------
| Autenticación
|--------------------------------------------------------------------------
*/


/*
 * Formulario de registro.
 */
$router->get('/register', [
    AuthController::class,
    'register'
]);


/*
 * Procesar registro.
 */
$router->post('/register', [
    AuthController::class,
    'storeRegister'
]);


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


/*
|--------------------------------------------------------------------------
| Cuenta personal
|--------------------------------------------------------------------------
*/


$router->get('/account', [
    AccountController::class,
    'index'
]);


$router->post('/account/update', [
    AccountController::class,
    'update'
]);


$router->post('/account/password', [
    AccountController::class,
    'changePassword'
]);


/*
|--------------------------------------------------------------------------
| Recuperación de contraseña
|--------------------------------------------------------------------------
*/


/*
 * Formulario para solicitar la recuperación.
 */
$router->get('/forgot-password', [
    AuthController::class,
    'forgotPassword'
]);


/*
 * Procesar solicitud de recuperación.
 *
 * Genera y envía el código mediante SMTP.
 */
$router->post('/forgot-password', [
    AuthController::class,
    'processForgotPassword'
]);


/*
 * Formulario para introducir:
 *
 * - correo electrónico;
 * - código de recuperación;
 * - nueva contraseña;
 * - confirmación.
 */
$router->get('/reset-password', [
    AuthController::class,
    'resetPassword'
]);


/*
 * Procesar el restablecimiento de contraseña.
 */
$router->post('/reset-password', [
    AuthController::class,
    'processResetPassword'
]);