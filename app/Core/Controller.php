<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        require dirname(__DIR__) . '/Views/layouts/header.php';

        require dirname(__DIR__) . "/Views/{$view}.php";

        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }

    protected function adminView(string $view, array $data = []): void
    {
        extract($data);

        ob_start();

        require dirname(__DIR__) . "/Views/{$view}.php";

        $content = ob_get_clean();

        require dirname(__DIR__) . '/Views/layouts/admin.php';
    }
}