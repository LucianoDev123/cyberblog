<?php

declare(strict_types=1);

namespace App\Middleware;

class RoleMiddleware
{
    /**
     * Comprueba si el usuario tiene uno de los roles permitidos.
     */
    public static function handle(array $allowedRoles): void
    {
        // Obtenemos el rol almacenado en la sesión.
        $userRole = $_SESSION['usuario_rol'] ?? null;

        // Comprobamos si el rol del usuario está dentro
        // de los roles permitidos.
        if (!in_array($userRole, $allowedRoles, true)) {

            // Si no tiene permisos, devolvemos código HTTP 403.
            http_response_code(403);

            // Mostramos un mensaje de acceso denegado.
            die('403 - Acceso denegado');
        }
    }
}