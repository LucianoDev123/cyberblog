<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Article;
use App\Models\ArticleFollower;
use App\Models\User;

class ArticleFollowerController extends Controller
{
    /**
     * Registra el seguimiento de un artículo publicado.
     */
    public function follow(string $slug): void
    {
        $this->handleFollow($slug, true);
    }

    /**
     * Elimina el seguimiento de un artículo.
     */
    public function unfollow(string $slug): void
    {
        $this->handleFollow($slug, false);
    }

    /**
     * Procesa las operaciones de seguimiento.
     */
    private function handleFollow(
        string $slug,
        bool $follow
    ): void {
        // Validar el token CSRF.
        $this->verifyCsrfToken();

        // Verificar la sesión.
        if (
            !isset($_SESSION['usuario_id']) ||
            !is_numeric($_SESSION['usuario_id']) ||
            (int) $_SESSION['usuario_id'] <= 0
        ) {
            $this->setFlash(
                'error',
                'Debés iniciar sesión para seguir artículos.'
            );

            header('Location: /login');
            exit;
        }

        $userId = (int) $_SESSION['usuario_id'];

        // Comprobar que la cuenta siga activa.
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

        // Buscar el artículo por su slug.
        $articleModel = new Article();
        $article = $articleModel->getArticleBySlug($slug);

        if ($article === false) {
            http_response_code(404);
            die('Artículo no encontrado.');
        }

        $articleId = (int) $article['id'];

        // Ejecutar la operación.
        $followerModel = new ArticleFollower();

        if ($follow) {
            $success = $followerModel->follow(
                $userId,
                $articleId
            );

            $message = $success
                ? 'Ahora seguís este artículo.'
                : 'No se pudo seguir el artículo. Intentá nuevamente.';
        } else {
            $success = $followerModel->unfollow(
                $userId,
                $articleId
            );

            $message = $success
                ? 'Dejaste de seguir este artículo.'
                : 'No se pudo dejar de seguir el artículo. Intentá nuevamente.';
        }

        $this->setFlash(
            $success ? 'success' : 'error',
            $message
        );

        // Volver al artículo.
        header(
            'Location: /blog/' . rawurlencode($slug)
        );

        exit;
    }
}