<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Series;
use App\Models\User;
use App\Models\ArticleFollower;

class BlogController extends Controller
{
    public function index(): void
    {
        $articleModel = new Article();

        $articles = $articleModel->getPublishedArticles();

        $this->view('blog/index', [
            'articles' => $articles
        ]);
    }


    public function search(): void
    {
        $search = trim(
            $_GET['q'] ?? ''
        );

        $articles = [];

        if ($search !== '') {
            $articleModel = new Article();

            $articles = $articleModel
                ->searchPublishedArticles($search);
        }

        $this->view('blog/search', [
            'search' => $search,
            'articles' => $articles
        ]);
    }


    /**
     * Muestra un artículo y sus comentarios aprobados.
     *
     * El modelo devuelve también las relaciones entre
     * comentarios, los roles de los usuarios y el autor
     * del artículo para construir la vista.
     */
    public function show(string $slug): void
    {
        $articleModel = new Article();

        $article = $articleModel->getArticleBySlug($slug);

        if ($article === false) {
            http_response_code(404);
            die('Artículo no encontrado.');
        }


        /*
         * Estado del seguimiento del artículo.
         */
        $isAuthenticated =
            isset($_SESSION['usuario_id']) &&
            is_numeric($_SESSION['usuario_id']) &&
            (int) $_SESSION['usuario_id'] > 0;

        $followerModel = new ArticleFollower();
        $isFollowing = false;

        if ($isAuthenticated) {
            $isFollowing = $followerModel->isFollowing(
                (int) $_SESSION['usuario_id'],
                (int) $article['id']
            );
        }

        $followerCount = $followerModel->countFollowers(
            (int) $article['id']
        );


        /*
         * Obtenemos únicamente los comentarios aprobados.
         */
        $commentModel = new Comment();

        $comments = $commentModel->getApprovedByArticleId(
            (int) $article['id']
        );


        /*
         * Navegación de series.
         */
        $seriesNavigation = null;

        if (!empty($article['serie_id'])) {
            $seriesModel = new Series();

            $series = $seriesModel->getSeriesById(
                (int) $article['serie_id']
            );

            if (
                $series !== false &&
                ($series['estado'] ?? '') === 'publicada'
            ) {
                $seriesArticles = $seriesModel
                    ->getPublishedArticlesBySeriesId(
                        (int) $series['id']
                    );

                $currentIndex = null;

                foreach ($seriesArticles as $index => $seriesArticle) {
                    if (
                        (int) $seriesArticle['id'] ===
                        (int) $article['id']
                    ) {
                        $currentIndex = $index;
                        break;
                    }
                }

                if ($currentIndex !== null) {
                    $totalArticles = count($seriesArticles);

                    $previous = null;
                    $next = null;

                    if ($currentIndex > 0) {
                        $previous = $seriesArticles[
                            $currentIndex - 1
                        ];
                    }

                    if ($currentIndex < ($totalArticles - 1)) {
                        $next = $seriesArticles[
                            $currentIndex + 1
                        ];
                    }

                    $seriesNavigation = [
                        'serie' => $series,
                        'current' => $currentIndex + 1,
                        'total' => $totalArticles,
                        'previous' => $previous,
                        'next' => $next
                    ];
                }
            }
        }


        /*
         * Datos necesarios para la vista.
         *
         * getCsrfToken() genera el token si todavía
         * no existe en la sesión.
         *
         * getFlash() consume los mensajes para que
         * no vuelvan a mostrarse en visitas posteriores.
         */
        $this->view('blog/show', [
            'article' => $article,

            'comments' => $comments,

            'seriesNavigation' => $seriesNavigation,

            'pageCss' => 'article.css',

            'isAuthenticated' => $isAuthenticated,

            'isFollowing' => $isFollowing,

            'followerCount' => $followerCount,

            'csrfToken' => $this->getCsrfToken(),

            'flashError' => $this->getFlash('error'),

            'flashSuccess' => $this->getFlash('success')
        ]);
    }


    /**
     * Procesa el envío de un comentario o una respuesta.
     *
     * Los comentarios nuevos siempre quedan pendientes
     * de moderación.
     *
     * Si se recibe comentario_padre_id, se crea una respuesta
     * al comentario indicado.
     */
    public function storeComment(string $slug): void
    {
        /*
         * Primero validamos el token CSRF.
         */
        $this->verifyCsrfToken();


        /*
         * El usuario debe estar autenticado.
         */
        if (
            !isset($_SESSION['usuario_id']) ||
            !is_numeric($_SESSION['usuario_id']) ||
            (int) $_SESSION['usuario_id'] <= 0
        ) {
            $this->setFlash(
                'error',
                'Debés iniciar sesión para comentar.'
            );

            header(
                'Location: /blog/' . rawurlencode($slug)
            );

            exit;
        }

        $userId = (int) $_SESSION['usuario_id'];


        /*
         * Buscamos el artículo por su slug.
         */
        $articleModel = new Article();

        $article = $articleModel->getArticleBySlug($slug);

        if ($article === false) {
            http_response_code(404);
            die('Artículo no encontrado.');
        }


        /*
         * Comprobación adicional del estado.
         */
        if (
            isset($article['estado']) &&
            $article['estado'] !== 'publicado'
        ) {
            http_response_code(404);
            die('Artículo no encontrado.');
        }


        /*
         * Validamos el identificador del comentario padre.
         *
         * Si no se envía, se crea un comentario principal.
         * Si se envía, debe ser un identificador entero positivo.
         */
        $parentRaw = $_POST['comentario_padre_id'] ?? '';

        $parentId = null;

        if ($parentRaw !== '' && $parentRaw !== null) {
            if (
                (!is_string($parentRaw) && !is_int($parentRaw)) ||
                !ctype_digit((string) $parentRaw) ||
                (int) $parentRaw <= 0
            ) {
                $this->setFlash(
                    'error',
                    'El comentario al que intentás responder no es válido.'
                );

                header(
                    'Location: /blog/' . rawurlencode($slug)
                );

                exit;
            }

            $parentId = (int) $parentRaw;
        }


        /*
         * Si es una respuesta, comprobamos que el padre exista,
         * pertenezca al mismo artículo y esté aprobado.
         *
         * Esto impide responder a comentarios de otros artículos
         * o a comentarios que todavía esperan moderación.
         */
        if ($parentId !== null) {
            $commentModel = new Comment();

            if (
                !$commentModel->canReplyTo(
                    $parentId,
                    (int) $article['id']
                )
            ) {
                $this->setFlash(
                    'error',
                    'No podés responder a ese comentario. Puede que ya no exista o que no esté aprobado.'
                );

                header(
                    'Location: /blog/' . rawurlencode($slug)
                );

                exit;
            }
        }


        /*
         * El contenido debe ser una cadena.
         * No aceptamos arrays u otros tipos enviados
         * manipulando la petición HTTP.
         */
        $comment = $_POST['comentario'] ?? '';

        if (!is_string($comment)) {
            $this->setFlash(
                'error',
                'El comentario enviado no es válido.'
            );

            header(
                'Location: /blog/' . rawurlencode($slug)
            );

            exit;
        }

        $comment = trim($comment);


        /*
         * No permitimos comentarios vacíos.
         */
        if ($comment === '') {
            $this->setFlash(
                'error',
                'El comentario no puede estar vacío.'
            );

            header(
                'Location: /blog/' . rawurlencode($slug)
            );

            exit;
        }


        /*
         * Comprobamos que el contenido sea UTF-8 válido.
         */
        if (preg_match('//u', $comment) !== 1) {
            $this->setFlash(
                'error',
                'El comentario contiene caracteres no válidos.'
            );

            header(
                'Location: /blog/' . rawurlencode($slug)
            );

            exit;
        }


        /*
         * Limitamos el comentario a 2000 caracteres.
         *
         * Utilizamos una expresión regular para contar
         * caracteres Unicode sin depender de mbstring.
         */
        $maxLength = 2000;

        $characterCount = preg_match_all(
            '/./us',
            $comment
        );

        if (
            $characterCount === false ||
            $characterCount > $maxLength
        ) {
            $this->setFlash(
                'error',
                'El comentario no puede superar los ' .
                $maxLength .
                ' caracteres.'
            );

            header(
                'Location: /blog/' . rawurlencode($slug)
            );

            exit;
        }


        /*
         * Obtenemos el usuario desde la base de datos.
         *
         * No confiamos en el nombre ni en el email
         * enviados por el navegador.
         */
        $userModel = new User();

        $user = $userModel->findById($userId);

        if (
            $user === false ||
            (int) ($user['estado'] ?? 0) !== 1
        ) {
            unset(
                $_SESSION['usuario_id'],
                $_SESSION['usuario_nombre'],
                $_SESSION['usuario_rol']
            );

            $this->setFlash(
                'error',
                'Tu sesión ya no es válida. Iniciá sesión nuevamente.'
            );

            header('Location: /login');

            exit;
        }


        /*
         * Construimos los datos del autor utilizando
         * la información almacenada en la base de datos.
         */
        $author = trim(
            ($user['nombre'] ?? '') .
            ' ' .
            ($user['apellido'] ?? '')
        );

        $email = trim(
            (string) ($user['email'] ?? '')
        );


        /*
         * Validamos los datos de la cuenta.
         */
        if (
            $author === '' ||
            !filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            $this->setFlash(
                'error',
                'No se pudieron obtener los datos de tu cuenta.'
            );

            header(
                'Location: /blog/' . rawurlencode($slug)
            );

            exit;
        }


        /*
         * Creamos el comentario o la respuesta.
         *
         * El modelo establece estado = 'pendiente'.
         * El padre es null cuando se trata de un comentario
         * principal.
         */
        $commentModel = new Comment();

        $created = $commentModel->create(
            (int) $article['id'],
            $userId,
            $author,
            $email,
            $comment,
            $parentId
        );


        if (!$created) {
            $this->setFlash(
                'error',
                'No se pudo enviar el comentario. Intentá nuevamente.'
            );

            header(
                'Location: /blog/' . rawurlencode($slug)
            );

            exit;
        }


        /*
         * Confirmamos el envío sin publicar el contenido
         * inmediatamente.
         */
        if ($parentId !== null) {
            $successMessage =
                'Tu respuesta fue enviada y quedó pendiente de moderación.';
        } else {
            $successMessage =
                'Tu comentario fue enviado y quedó pendiente de moderación.';
        }

        $this->setFlash(
            'success',
            $successMessage
        );


        /*
         * Patrón Post/Redirect/Get:
         * evita reenviar el formulario al actualizar.
         */
        header(
            'Location: /blog/' . rawurlencode($slug)
        );

        exit;
    }
}