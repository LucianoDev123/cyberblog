<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $viewsPath = dirname(__DIR__) . '/Views/';

        $header = $viewsPath . 'layouts/header.php';
        $footer = $viewsPath . 'layouts/footer.php';
        $content = $viewsPath . $view . '.php';

        if (!file_exists($content)) {
            http_response_code(500);
            die("La vista '{$view}' no existe.");
        }

        if (file_exists($header)) {
            require $header;
        }

        require $content;

        if (file_exists($footer)) {
            require $footer;
        }
    }
}