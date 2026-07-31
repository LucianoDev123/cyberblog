<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data);

        $viewFile = dirname(__DIR__) . "/Views/{$view}.php";

        if (!file_exists($viewFile)) {
            die("La vista '{$view}' no existe.");
        }

        require $viewFile;
    }
}