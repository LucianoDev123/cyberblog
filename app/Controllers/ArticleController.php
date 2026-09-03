<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Series;
use App\Middleware\RoleMiddleware;

class ArticleController extends Controller
{
    /**
     * Directorio físico donde se guardarán las imágenes.
     */
    private const UPLOAD_DIRECTORY =
        __DIR__ . '/../../public/uploads/articles/';


    /**
     * Tamaño máximo permitido: 5 MB.
     */
    private const MAX_IMAGE_SIZE =
        5 * 1024 * 1024;


    /**
     * Muestra el listado administrativo de artículos.
     */
    /**
     * Muestra el listado administrativo
     * de artículos con paginación.
     */
    public function index(): void
    {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $articleModel = new Article();

        /*
        * Cantidad de artículos por página.
        */
        $perPage = 15;

        /*
        * Obtenemos el número de página
        * enviado mediante GET.
        *
        * Ejemplo:
        *
        * /admin/articles?page=2
        */
        $requestedPage =
            filter_input(
                INPUT_GET,
                'page',
                FILTER_VALIDATE_INT
            );

        /*
        * Si el parámetro no existe o no es
        * un entero válido, comenzamos
        * desde la página 1.
        */
        $requestedPage =
            $requestedPage !== false &&
            $requestedPage !== null
                ? $requestedPage
                : 1;

        /*
        * Obtenemos la cantidad total
        * de artículos.
        */
        $totalArticles =
            $articleModel->countAllArticles();

        /*
        * Creamos el objeto de paginación.
        */
        $pagination = new \App\Core\Pagination(
            $totalArticles,
            $requestedPage,
            $perPage
        );

        /*
        * Obtenemos únicamente los artículos
        * correspondientes a la página actual.
        */
        $articles =
            $articleModel->getPaginatedArticles(
                $pagination->getPerPage(),
                $pagination->getOffset()
            );

        /*
        * Cargamos la vista.
        */
        $this->adminView('articles/index', [
            'title' =>
                'Administración de Artículos',

            'articles' =>
                $articles,

            'pagination' =>
                $pagination
        ]);
    }


    /**
     * Muestra el formulario para crear un artículo.
     */
    public function create(): void
    {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $categoryModel = new Category();

        $categories =
            $categoryModel->getAllCategories();


        $seriesModel = new Series();

        $series =
            $seriesModel->getAllSeries();


        $this->adminView('articles/create', [
            'title' => 'Nuevo artículo',
            'categories' => $categories,
            'series' => $series
        ]);
    }


    /**
     * Guarda un nuevo artículo.
     */
    public function store(): void
    {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $titulo =
            trim($_POST['titulo'] ?? '');

        $resumen =
            trim($_POST['resumen'] ?? '');

        $contenido =
            trim($_POST['contenido'] ?? '');

        $estado =
            $_POST['estado'] ?? 'borrador';

        $categoriaId =
            (int) ($_POST['categoria_id'] ?? 0);

        $serieId =
            (int) ($_POST['serie_id'] ?? 0);


        /*
         * Validamos los campos obligatorios.
         */
        if (
            $titulo === '' ||
            $resumen === '' ||
            $contenido === '' ||
            $categoriaId <= 0
        ) {
            $this->setFlash(
                'error',
                'Todos los campos obligatorios deben completarse.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/articles/create'
            );

            exit;
        }


        /*
         * Validamos que la categoría exista.
         */
        $categoryModel = new Category();

        $category =
            $categoryModel->getCategoryById(
                $categoriaId
            );

        if ($category === false) {

            $this->setFlash(
                'error',
                'La categoría seleccionada no existe.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/articles/create'
            );

            exit;
        }


        /*
         * Si se seleccionó una serie,
         * verificamos que exista.
         */
        if ($serieId > 0) {

            $seriesModel =
                new Series();

            $serie =
                $seriesModel->getSeriesById(
                    $serieId
                );

            if ($serie === false) {

                $this->setFlash(
                    'error',
                    'La serie seleccionada no existe.'
                );

                header(
                    'Location: /incuyo/cyberblog/public/admin/articles/create'
                );

                exit;
            }
        }


        /*
         * Si no se seleccionó ninguna serie,
         * almacenamos NULL.
         */
        $serieId =
            $serieId > 0
                ? $serieId
                : null;


        /*
         * Validamos el estado.
         */
        $allowedStates = [
            'borrador',
            'publicado'
        ];

        if (
            !in_array(
                $estado,
                $allowedStates,
                true
            )
        ) {
            $estado = 'borrador';
        }


        /*
         * Generamos el slug base.
         */
        $baseSlug = strtolower(
            trim(
                preg_replace(
                    '/[^a-zA-Z0-9]+/',
                    '-',
                    $titulo
                ),
                '-'
            )
        );


        /*
         * Procesamos la imagen.
         */
        $imageName =
            $this->uploadImage();


        /*
         * Si ocurrió un error en la subida,
         * detenemos el proceso.
         */
        if ($imageName === false) {

            header(
                'Location: /incuyo/cyberblog/public/admin/articles/create'
            );

            exit;
        }


        /*
         * Creamos el modelo.
         */
        $articleModel = new Article();


        /*
         * Generamos un slug único.
         */
        $slug =
            $articleModel->generateUniqueSlug(
                $baseSlug
            );


        /*
         * Creamos el artículo.
         */
        $data = [
            'usuario_id' =>
                $_SESSION['usuario_id'],

            'categoria_id' =>
                $categoriaId,

            'serie_id' =>
                $serieId,

            'titulo' =>
                $titulo,

            'slug' =>
                $slug,

            'resumen' =>
                $resumen,

            'contenido' =>
                $contenido,

            'imagen' =>
                $imageName,

            'estado' =>
                $estado
        ];

        $articleModel->create($data);


        /*
         * Mensaje de éxito.
         */
        $this->setFlash(
            'success',
            'Artículo creado correctamente.'
        );


        header(
            'Location: /incuyo/cyberblog/public/admin/articles'
        );

        exit;
    }


    /**
     * Muestra el formulario para editar un artículo.
     */
    public function edit(int $id): void
    {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $articleModel =
            new Article();

        $article =
            $articleModel->find($id);


        if ($article === false) {

            http_response_code(404);

            die('Artículo no encontrado.');
        }


        /*
         * Obtenemos las categorías.
         */
        $categoryModel =
            new Category();

        $categories =
            $categoryModel->getAllCategories();


        /*
         * Obtenemos las series.
         */
        $seriesModel =
            new Series();

        $series =
            $seriesModel->getAllSeries();


        $this->adminView('articles/edit', [
            'title' => 'Editar artículo',
            'article' => $article,
            'categories' => $categories,
            'series' => $series
        ]);
    }


    /**
     * Actualiza un artículo existente.
     */
    public function update(int $id): void
    {
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);

        $articleModel =
            new Article();

        $article =
            $articleModel->find($id);


        if ($article === false) {

            http_response_code(404);

            die('Artículo no encontrado.');
        }


        $titulo =
            trim($_POST['titulo'] ?? '');

        $resumen =
            trim($_POST['resumen'] ?? '');

        $contenido =
            trim($_POST['contenido'] ?? '');

        $estado =
            $_POST['estado'] ?? 'borrador';

        $categoriaId =
            (int) ($_POST['categoria_id'] ?? 0);

        $serieId =
            (int) ($_POST['serie_id'] ?? 0);


        /*
         * Validamos los campos obligatorios.
         */
        if (
            $titulo === '' ||
            $resumen === '' ||
            $contenido === '' ||
            $categoriaId <= 0
        ) {
            $this->setFlash(
                'error',
                'Todos los campos obligatorios deben completarse.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/articles/edit/' . $id
            );

            exit;
        }


        /*
         * Validamos que la categoría exista.
         */
        $categoryModel =
            new Category();

        $category =
            $categoryModel->getCategoryById(
                $categoriaId
            );

        if ($category === false) {

            $this->setFlash(
                'error',
                'La categoría seleccionada no existe.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/articles/edit/' . $id
            );

            exit;
        }


        /*
         * Si se seleccionó una serie,
         * verificamos que exista.
         */
        if ($serieId > 0) {

            $seriesModel =
                new Series();

            $serie =
                $seriesModel->getSeriesById(
                    $serieId
                );

            if ($serie === false) {

                $this->setFlash(
                    'error',
                    'La serie seleccionada no existe.'
                );

                header(
                    'Location: /incuyo/cyberblog/public/admin/articles/edit/' . $id
                );

                exit;
            }
        }


        /*
         * Si no se seleccionó ninguna serie,
         * guardamos NULL.
         */
        $serieId =
            $serieId > 0
                ? $serieId
                : null;


        /*
         * Validamos el estado.
         */
        $allowedStates = [
            'borrador',
            'publicado'
        ];

        if (
            !in_array(
                $estado,
                $allowedStates,
                true
            )
        ) {
            $estado = 'borrador';
        }


        /*
         * Generamos el slug base.
         */
        $baseSlug = strtolower(
            trim(
                preg_replace(
                    '/[^a-zA-Z0-9]+/',
                    '-',
                    $titulo
                ),
                '-'
            )
        );


        /*
         * Generamos un slug único excluyendo
         * el propio artículo.
         */
        $slug =
            $articleModel->generateUniqueSlug(
                $baseSlug,
                $id
            );


        /*
         * Conservamos la imagen actual
         * por defecto.
         */
        $imageName =
            $article['imagen'] ?? null;


        /*
         * Comprobamos si el usuario solicitó
         * eliminar la imagen actual.
         */
        $deleteCurrentImage =
            ($_POST['eliminar_imagen'] ?? '') === '1';


        /*
         * Comprobamos si se seleccionó
         * una nueva imagen.
         */
        $hasNewImage =
            isset($_FILES['imagen']) &&
            $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE;


        /*
         * PRIORIDAD:
         *
         * 1. Nueva imagen.
         * 2. Eliminar imagen.
         * 3. Conservar imagen actual.
         */


        /*
         * Si hay una nueva imagen,
         * la procesamos.
         */
        if ($hasNewImage) {

            $uploadedImage =
                $this->uploadImage();


            if ($uploadedImage === false) {

                header(
                    'Location: /incuyo/cyberblog/public/admin/articles/edit/' . $id
                );

                exit;
            }


            /*
             * La nueva imagen se guardó
             * correctamente.
             *
             * Ahora eliminamos la anterior.
             */
            $this->deleteImageFile(
                $imageName
            );


            /*
             * Guardamos el nuevo nombre.
             */
            $imageName =
                $uploadedImage;
        }


        /*
         * Si no se subió una nueva imagen
         * pero el usuario pidió eliminarla.
         */
        elseif ($deleteCurrentImage) {

            $this->deleteImageFile(
                $imageName
            );


            /*
             * El campo imagen queda en NULL
             * en la base de datos.
             */
            $imageName = null;
        }


        /*
         * Actualizamos el artículo.
         */
        $data = [
            'categoria_id' =>
                $categoriaId,

            'serie_id' =>
                $serieId,

            'titulo' =>
                $titulo,

            'slug' =>
                $slug,

            'resumen' =>
                $resumen,

            'contenido' =>
                $contenido,

            'imagen' =>
                $imageName,

            'estado' =>
                $estado
        ];

        $articleModel->update(
            $id,
            $data
        );


        $this->setFlash(
            'success',
            'Artículo actualizado correctamente.'
        );


        header(
            'Location: /incuyo/cyberblog/public/admin/articles'
        );

        exit;
    }


    /**
     * Elimina definitivamente un artículo.
     *
     * Solo los administradores pueden hacerlo.
     */
    public function delete(int $id): void
    {
        RoleMiddleware::handle([
            'admin'
        ]);

        $articleModel =
            new Article();

        $article =
            $articleModel->find($id);


        if ($article === false) {

            http_response_code(404);

            die('Artículo no encontrado.');
        }


        /*
         * Eliminamos la imagen física
         * si existe.
         */
        $imageName =
            $article['imagen'] ?? null;


        $this->deleteImageFile(
            $imageName
        );


        /*
         * Eliminamos el registro.
         */
        $articleModel->delete($id);


        $this->setFlash(
            'success',
            'Artículo eliminado correctamente.'
        );


        header(
            'Location: /incuyo/cyberblog/public/admin/articles'
        );

        exit;
    }


    /**
     * Elimina físicamente una imagen
     * del directorio de artículos.
     */
    private function deleteImageFile(
        ?string $imageName
    ): void {
        if (
            $imageName === null ||
            $imageName === ''
        ) {
            return;
        }


        /*
         * Utilizamos basename para evitar
         * problemas con rutas almacenadas.
         */
        $imageName =
            basename($imageName);


        $filePath =
            self::UPLOAD_DIRECTORY
            . $imageName;


        if (
            file_exists($filePath) &&
            is_file($filePath)
        ) {
            unlink($filePath);
        }
    }


    /**
     * Procesa y guarda una imagen enviada
     * desde un formulario.
     *
     * @return string|null|false
     */
    private function uploadImage(): string|null|false
    {
        /*
         * Si no existe el campo,
         * no se envió ningún archivo.
         */
        if (!isset($_FILES['imagen'])) {
            return null;
        }


        $file =
            $_FILES['imagen'];


        /*
         * No se seleccionó ningún archivo.
         */
        if (
            $file['error'] === UPLOAD_ERR_NO_FILE
        ) {
            return null;
        }


        /*
         * Error durante la subida.
         */
        if (
            $file['error'] !== UPLOAD_ERR_OK
        ) {
            $this->setFlash(
                'error',
                'Ocurrió un error al subir la imagen.'
            );

            return false;
        }


        /*
         * Validamos el tamaño.
         */
        if (
            $file['size'] > self::MAX_IMAGE_SIZE
        ) {
            $this->setFlash(
                'error',
                'La imagen no puede superar los 5 MB.'
            );

            return false;
        }


        /*
         * Detectamos el tipo MIME real.
         */
        $finfo =
            new \finfo(FILEINFO_MIME_TYPE);

        $mimeType =
            $finfo->file(
                $file['tmp_name']
            );


        /*
         * Tipos permitidos.
         */
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp'
        ];


        if (
            !array_key_exists(
                $mimeType,
                $allowedTypes
            )
        ) {
            $this->setFlash(
                'error',
                'Formato de imagen no permitido. '
                . 'Solo se permiten JPG, PNG y WEBP.'
            );

            return false;
        }


        /*
         * Generamos un nombre aleatorio.
         */
        $fileName =
            bin2hex(
                random_bytes(16)
            )
            . '.'
            . $allowedTypes[$mimeType];


        /*
         * Creamos el directorio
         * si no existe.
         */
        if (
            !is_dir(
                self::UPLOAD_DIRECTORY
            )
        ) {
            mkdir(
                self::UPLOAD_DIRECTORY,
                0755,
                true
            );
        }


        /*
         * Ruta final.
         */
        $destination =
            self::UPLOAD_DIRECTORY
            . $fileName;


        /*
         * Movemos el archivo.
         */
        if (
            !move_uploaded_file(
                $file['tmp_name'],
                $destination
            )
        ) {
            $this->setFlash(
                'error',
                'No fue posible guardar la imagen.'
            );

            return false;
        }


        return $fileName;
    }
}