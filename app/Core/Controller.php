<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    /**
     * Carga una vista del FrontOffice.
     */
    protected function view(string $view, array $data = []): void
    {
        // Convierte las claves del array $data
        // en variables disponibles dentro de la vista.
        extract($data);

        // Cargamos el encabezado general.
        require dirname(__DIR__) . '/Views/layouts/header.php';

        // Cargamos la vista solicitada.
        require dirname(__DIR__) . "/Views/{$view}.php";

        // Cargamos el pie de página.
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }


    /**
     * Carga una vista del BackOffice.
     */
    protected function adminView(string $view, array $data = []): void
    {
        // Obtenemos el mensaje de error guardado en la sesión.
        $flashError = $this->getFlash('error');

        // Obtenemos el mensaje de éxito guardado en la sesión.
        $flashSuccess = $this->getFlash('success');

        // Generamos/obtenemos el token CSRF.
        $csrfToken = $this->getCsrfToken();

        // Convertimos los datos recibidos en variables.
        extract($data);

        // Comenzamos a capturar la salida de la vista.
        ob_start();

        // Cargamos la vista solicitada.
        require dirname(__DIR__) . "/Views/{$view}.php";

        // Guardamos el contenido generado.
        $content = ob_get_clean();

        // Cargamos el layout administrativo.
        require dirname(__DIR__) . '/Views/layouts/admin.php';
    }


    /**
     * Guarda un mensaje temporal en la sesión.
     */
    protected function setFlash(string $type, string $message): void
    {
        // Guardamos el mensaje dentro de la sesión.
        $_SESSION['flash'][$type] = $message;
    }


    /**
     * Obtiene un mensaje temporal de la sesión.
     *
     * Después de obtenerlo, lo elimina.
     */
    protected function getFlash(string $type): ?string
    {
        // Si no existe el mensaje, devolvemos null.
        if (!isset($_SESSION['flash'][$type])) {
            return null;
        }

        // Guardamos temporalmente el mensaje.
        $message = $_SESSION['flash'][$type];

        // Eliminamos el mensaje.
        unset($_SESSION['flash'][$type]);

        // Devolvemos el mensaje.
        return $message;
    }


    /**
     * Guarda temporalmente los datos no sensibles
     * enviados por un formulario.
     */
    protected function setOldInput(array $data): void
    {
        // Guardamos los datos en la sesión.
        $_SESSION['old_input'] = $data;
    }


    /**
     * Recupera los datos temporales del formulario.
     *
     * Después de recuperarlos, los elimina.
     */
    protected function getOldInput(): array
    {
        // Si no existen datos anteriores,
        // devolvemos un array vacío.
        if (!isset($_SESSION['old_input'])) {
            return [];
        }

        // Guardamos temporalmente los datos.
        $data = $_SESSION['old_input'];

        // Eliminamos los datos de la sesión.
        unset($_SESSION['old_input']);

        // Devolvemos los datos.
        return $data;
    }


    /**
     * Genera y devuelve el token CSRF de la sesión.
     */
    protected function getCsrfToken(): string
    {
        // Si todavía no existe un token,
        // generamos uno criptográficamente seguro.
        if (
            !isset($_SESSION['csrf_token']) ||
            !is_string($_SESSION['csrf_token'])
        ) {
            $_SESSION['csrf_token'] = bin2hex(
                random_bytes(32)
            );
        }

        // Devolvemos el token.
        return $_SESSION['csrf_token'];
    }


    /**
     * Comprueba que el token CSRF recibido sea válido.
     */
    protected function verifyCsrfToken(): void
    {
        // Obtenemos el token enviado por POST.
        $submittedToken = $_POST['csrf_token'] ?? '';

        // Obtenemos el token almacenado en la sesión.
        $sessionToken = $_SESSION['csrf_token'] ?? '';

        // Comprobamos que ambos sean strings.
        if (
            !is_string($submittedToken) ||
            !is_string($sessionToken)
        ) {
            http_response_code(403);

            die('403 - Token CSRF inválido.');
        }

        // Comparamos los tokens de forma segura.
        if (
            $submittedToken === '' ||
            $sessionToken === '' ||
            !hash_equals($sessionToken, $submittedToken)
        ) {
            http_response_code(403);

            die('403 - Token CSRF inválido.');
        }
    }
}