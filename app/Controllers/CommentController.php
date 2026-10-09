<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\RoleMiddleware;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Muestra los comentarios pendientes y publicados.
     *
     * Solo los administradores pueden acceder.
     */
    public function index(): void
    {
        RoleMiddleware::handle(['admin']);

        $commentModel = new Comment();

        $comments = $commentModel->getPending();
        $approvedComments = $commentModel->getApproved();

        $this->adminView(
            'comments/index',
            [
                'title' => 'Moderación de comentarios',
                'comments' => $comments,
                'approvedComments' => $approvedComments,
            ]
        );
    }

    /**
     * Aprueba un comentario pendiente.
     */
    public function approve(int $id): void
    {
        RoleMiddleware::handle(['admin']);

        $this->verifyCsrfToken();

        if ($id <= 0) {
            $this->setFlash(
                'error',
                'El identificador del comentario no es válido.'
            );

            header('Location: /admin/comments');
            exit;
        }

        $commentModel = new Comment();

        $approved = $commentModel->approve($id);

        if (!$approved) {
            $this->setFlash(
                'error',
                'El comentario no existe, ya fue moderado o no pudo aprobarse.'
            );

            header('Location: /admin/comments');
            exit;
        }

        $this->setFlash(
            'success',
            'El comentario fue aprobado y ya puede mostrarse públicamente.'
        );

        header('Location: /admin/comments');
        exit;
    }

    /**
     * Elimina un comentario y todos sus descendientes.
     */
    public function delete(int $id): void
    {
        RoleMiddleware::handle(['admin']);

        $this->verifyCsrfToken();

        if ($id <= 0) {
            $this->setFlash(
                'error',
                'El identificador del comentario no es válido.'
            );

            header('Location: /admin/comments');
            exit;
        }

        $commentModel = new Comment();

        try {
            $deleted = $commentModel->delete($id);
        } catch (\Throwable $exception) {
            error_log(
                'Error al eliminar una conversación de comentarios: ' .
                $exception->getMessage()
            );

            $this->setFlash(
                'error',
                'No se pudo eliminar la conversación. Intentá nuevamente.'
            );

            header('Location: /admin/comments');
            exit;
        }

        if (!$deleted) {
            $this->setFlash(
                'error',
                'El comentario no existe o no pudo eliminarse.'
            );

            header('Location: /admin/comments');
            exit;
        }

        $this->setFlash(
            'success',
            'El comentario y todas las respuestas de su conversación fueron eliminados correctamente.'
        );

        header('Location: /admin/comments');
        exit;
    }
}