<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Middleware\RoleMiddleware;

class ArticleController extends Controller
{
    /**
     * Muestra el listado de artículos.
     */
    public function index(): void
    {
        // Solo los administradores y editores autorizados
        // pueden acceder a la administración de artículos.
        RoleMiddleware::handle(['admin', 'editor']);

        // Creamos una instancia del modelo.
        $articleModel = new Article();

        // Obtenemos todos los artículos.
        $articles = $articleModel->getAllArticles();

        // Enviamos los artículos a la vista.
        $this->adminView('articles/index', [
            'title' => 'Administración de Artículos',
            'articles' => $articles
        ]);
    }


    /**
     * Muestra el formulario para crear un artículo.
     */
    public function create(): void
    {
        // Solo administradores y editores pueden crear artículos.
        RoleMiddleware::handle(['admin', 'editor']);

        // Cargamos la vista.
        $this->adminView('articles/create', [
            'title' => 'Nuevo artículo'
        ]);
    }


    /**
     * Muestra el formulario para editar un artículo.
     */
    public function edit(int $id): void
    {
        // Solo administradores y editores pueden editar artículos.
        RoleMiddleware::handle(['admin', 'editor']);

        // Creamos una instancia del modelo.
        $articleModel = new Article();

        // Buscamos el artículo.
        $article = $articleModel->find($id);

        // Comprobamos que exista.
        if ($article === false) {

            http_response_code(404);

            die('Artículo no encontrado');
        }

        // Cargamos la vista de edición.
        $this->adminView('articles/edit', [
            'title' => 'Editar artículo',
            'article' => $article
        ]);
    }


    /**
     * Actualiza un artículo existente.
     */
    public function update(int $id): void
    {
        // Solo administradores y editores pueden actualizar artículos.
        RoleMiddleware::handle(['admin', 'editor']);

        // Validamos el token CSRF.
        $this->verifyCsrfToken();

        // Creamos una instancia del modelo.
        $articleModel = new Article();

        // Armamos los datos recibidos.
        $data = [

            // Por ahora mantenemos la categoría fija.
            'categoria_id' => 1,

            // Nuevo título.
            'titulo' => $_POST['titulo'] ?? '',

            // Generamos el slug a partir del título.
            'slug' => strtolower(
                str_replace(
                    ' ',
                    '-',
                    $_POST['titulo'] ?? ''
                )
            ),

            // Nuevo resumen.
            'resumen' => $_POST['resumen'] ?? '',

            // Nuevo contenido.
            'contenido' => $_POST['contenido'] ?? '',

            // Por ahora mantenemos los artículos como borrador.
            'estado' => 'borrador'
        ];

        // Actualizamos el artículo.
        $articleModel->update($id, $data);

        // Volvemos al listado.
        header(
            'Location: /incuyo/cyberblog/public/admin/articles'
        );

        exit;
    }


    /**
     * Elimina definitivamente un artículo.
     *
     * Esta operación requiere:
     *
     * 1. Usuario autenticado.
     * 2. Rol administrador.
     * 3. Petición POST.
     * 4. Token CSRF válido.
     */
    public function delete(int $id): void
    {
        // Solo los administradores pueden eliminar artículos.
        RoleMiddleware::handle(['admin']);

        // Validamos el token CSRF.
        $this->verifyCsrfToken();

        // Creamos una instancia del modelo.
        $articleModel = new Article();

        // Eliminamos definitivamente el artículo.
        $articleModel->delete($id);

        // Volvemos al listado.
        header(
            'Location: /incuyo/cyberblog/public/admin/articles'
        );

        exit;
    }


    /**
     * Crea un nuevo artículo.
     */
    public function store(): void
    {
        // Solo administradores y editores pueden crear artículos.
        RoleMiddleware::handle(['admin', 'editor']);

        // Validamos el token CSRF.
        $this->verifyCsrfToken();

        // Creamos una instancia del modelo.
        $articleModel = new Article();

        // Armamos los datos del nuevo artículo.
        $data = [
            'usuario_id'   => $_SESSION['usuario_id'],
            'categoria_id' => 1,

            'titulo' => $_POST['titulo'] ?? '',

            'slug' => strtolower(
                str_replace(
                    ' ',
                    '-',
                    $_POST['titulo'] ?? ''
                )
            ),

            'resumen' => $_POST['resumen'] ?? '',

            'contenido' => $_POST['contenido'] ?? '',

            'estado' => 'borrador'
        ];

        // Creamos el artículo.
        $articleModel->create($data);

        // Volvemos al listado.
        header(
            'Location: /incuyo/cyberblog/public/admin/articles'
        );

        exit;
    }
}