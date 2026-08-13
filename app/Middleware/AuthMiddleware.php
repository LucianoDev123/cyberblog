<?php

declare(strict_types=1);

namespace App\Middleware;

class AuthMiddleware
{
    /**
     * Comprueba si existe un usuario autenticado.
     */
    public static function handle(): void
    {
        // Comprobamos si existe el ID del usuario en la sesión.
        if (!isset($_SESSION['usuario_id'])) {

            // Si no existe, el usuario no está autenticado.
            // Lo enviamos al formulario de login.
            header('Location: /incuyo/cyberblog/public/login');

            // Detenemos la ejecución.
            exit;
        }
    }
}