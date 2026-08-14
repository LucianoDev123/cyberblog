<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Middleware\RoleMiddleware;

class ArticleController extends Controller
{
    public function index(): void
    {
        $articleModel = new Article();

        $articles = $articleModel->getAllArticles();

        $this->adminView('articles/index', [
            'title' => 'Administración de Artículos',
            'articles' => $articles
        ]);
    }
    public function create(): void
    {
        $this->adminView('articles/create', [
            'title' => 'Nuevo artículo'
        ]);
    }
    public function edit(int $id): void
    {
        // Creamos un objeto del modelo Article para poder acceder a la base de datos
        $articleModel = new Article();

        // Buscamos el artículo cuyo ID llegó desde la URL.
        // El método find() lo heredamos de Model.php.
        $article = $articleModel->find($id);

        // Enviamos el artículo a la vista.
        // adminView() carga el layout de administración y la vista articles/edit.php
        $this->adminView('articles/edit', [

            // Título que utilizará el layout
            'title' => 'Editar artículo',

            // Variable que recibirá la vista.
            // Dentro de edit.php podremos usar:
            // $article['titulo']
            // $article['resumen']
            // etc.
            'article' => $article
        ]);
    }

    public function update(int $id): void
    {
        // Creamos una instancia del modelo Article
        $articleModel = new Article();

        // Armamos un array con los datos que llegaron desde el formulario
        $data = [

            // Por ahora dejamos fija la categoría
            'categoria_id' => 1,

            // Nuevo título
            'titulo' => $_POST['titulo'],

            // Generamos nuevamente el slug a partir del título
            'slug' => strtolower(str_replace(' ', '-', $_POST['titulo'])),

            // Nuevo resumen
            'resumen' => $_POST['resumen'],

            // Nuevo contenido
            'contenido' => $_POST['contenido'],

            // Mantenemos el estado como borrador
            'estado' => 'borrador'
        ];

        // Llamamos al método update() del modelo
        $articleModel->update($id, $data);

        // Una vez actualizado, volvemos al listado
        header('Location: /incuyo/cyberblog/public/admin/articles');

        exit;
    }

    /**
     * Elimina definitivamente un artículo.
     *
     * Esta acción está reservada exclusivamente
     * para usuarios con rol admin.
     */
    public function delete(int $id): void
    {
        // Comprobamos que el usuario tenga permisos de administrador.
        RoleMiddleware::handle(['admin']);

        // Creamos una instancia del modelo Article.
        $articleModel = new Article();

        // Eliminamos el artículo de la base de datos.
        $articleModel->delete($id);

        // Volvemos al listado de artículos.
        header('Location: /incuyo/cyberblog/public/admin/articles');

        exit;
    }




    public function store(): void
    {
        $articleModel = new Article();

        $data = [
            'usuario_id'   => 1,
            'categoria_id' => 1,
            'titulo'       => $_POST['titulo'],
            'slug'         => strtolower(str_replace(' ', '-', $_POST['titulo'])),
            'resumen'      => $_POST['resumen'],
            'contenido'    => $_POST['contenido'],
            'estado'       => 'borrador'
        ];

        $articleModel->create($data);

        header('Location: /incuyo/cyberblog/public/admin/articles');
        exit;
    }
}