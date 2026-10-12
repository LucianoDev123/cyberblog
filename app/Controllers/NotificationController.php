<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(): void
    {
        $userId = $this->requireUser();
        $notificationModel = new Notification();

        $this->view('notifications/index', [
            'title' => 'Mis notificaciones',
            'pageCss' => 'notifications.css',
            'notifications' => $notificationModel->getForUser($userId),
            'unreadCount' => $notificationModel->countUnread($userId),
            'csrfToken' => $this->getCsrfToken(),
        ]);
    }

    public function read(int $id): void
    {
        $userId = $this->requireUser();
        $this->verifyCsrfToken();

        if ($id > 0) {
            (new Notification())->markRead($id, $userId);
        }

        $this->redirectToNotifications();
    }

    public function readAll(): void
    {
        $userId = $this->requireUser();
        $this->verifyCsrfToken();

        (new Notification())->markAllRead($userId);
        $this->redirectToNotifications();
    }

    private function requireUser(): int
    {
        if (
            !isset($_SESSION['usuario_id']) ||
            !is_numeric($_SESSION['usuario_id']) ||
            (int) $_SESSION['usuario_id'] <= 0
        ) {
            header('Location: /login');
            exit;
        }

        return (int) $_SESSION['usuario_id'];
    }

    private function redirectToNotifications(): void
    {
        header('Location: /notifications');
        exit;
    }
}
