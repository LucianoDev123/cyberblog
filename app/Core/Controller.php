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
        extract($data);

        require dirname(__DIR__) . '/Views/layouts/header.php';
        require dirname(__DIR__) . "/Views/{$view}.php";
        require dirname(__DIR__) . '/Views/layouts/footer.php';
    }


    /**
     * Carga una vista del BackOffice.
     *
     * Además de la vista administrativa general,
     * determina automáticamente si existe un CSS
     * específico para el módulo.
     *
     * Ejemplo:
     *
     * dashboard/index
     *      ↓
     * dashboard.css
     *
     * articles/index
     *      ↓
     * articles.css
     */
    protected function adminView(
        string $view,
        array $data = []
    ): void {
        $flashError =
            $this->getFlash('error');

        $flashSuccess =
            $this->getFlash('success');

        $csrfToken =
            $this->getCsrfToken();


        /*
         * Determinamos el módulo administrativo
         * a partir de la vista.
         *
         * Ejemplo:
         *
         * articles/index
         *
         * módulo = articles
         */
        $viewParts =
            explode(
                '/',
                trim($view, '/')
            );

        $adminModule =
            $viewParts[0] ?? '';


        /*
         * Solamente permitimos módulos conocidos.
         *
         * Esto evita utilizar directamente
         * cualquier valor recibido como nombre
         * de archivo CSS.
         */
        $adminCssModules = [
            'dashboard',
            'articles',
            'categories',
            'series',
            'users'
        ];


        if (
            !in_array(
                $adminModule,
                $adminCssModules,
                true
            )
        ) {
            $adminModule = null;
        }


        /*
         * Extraemos los datos enviados
         * por el Controller.
         */
        extract($data);


        /*
         * Renderizamos primero el contenido
         * de la vista administrativa.
         */
        ob_start();

        require dirname(__DIR__)
            . "/Views/{$view}.php";

        $content =
            ob_get_clean();


        /*
         * Finalmente cargamos el layout
         * general del BackOffice.
         */
        require dirname(__DIR__)
            . '/Views/layouts/admin.php';
    }


    protected function setFlash(
        string $type,
        string $message
    ): void {
        $_SESSION['flash'][$type] =
            $message;
    }


    protected function getFlash(
        string $type
    ): ?string {
        if (
            !isset(
                $_SESSION['flash'][$type]
            )
        ) {
            return null;
        }

        $message =
            $_SESSION['flash'][$type];

        unset(
            $_SESSION['flash'][$type]
        );

        return $message;
    }


    protected function setOldInput(
        array $data
    ): void {
        $_SESSION['old_input'] =
            $data;
    }


    protected function getOldInput(): array
    {
        if (
            !isset(
                $_SESSION['old_input']
            )
        ) {
            return [];
        }

        $data =
            $_SESSION['old_input'];

        unset(
            $_SESSION['old_input']
        );

        return $data;
    }


    protected function getCsrfToken(): string
    {
        if (
            !isset(
                $_SESSION['csrf_token']
            )
            ||
            !is_string(
                $_SESSION['csrf_token']
            )
        ) {
            $_SESSION['csrf_token'] =
                bin2hex(
                    random_bytes(32)
                );
        }

        return $_SESSION['csrf_token'];
    }


    protected function verifyCsrfToken(): void
    {
        $submittedToken =
            $_POST['csrf_token'] ?? '';

        $sessionToken =
            $_SESSION['csrf_token'] ?? '';


        if (
            !is_string($submittedToken)
            ||
            !is_string($sessionToken)
        ) {
            http_response_code(403);

            die(
                '403 - Token CSRF inválido.'
            );
        }


        if (
            $submittedToken === ''
            ||
            $sessionToken === ''
            ||
            !hash_equals(
                $sessionToken,
                $submittedToken
            )
        ) {
            http_response_code(403);

            die(
                '403 - Token CSRF inválido.'
            );
        }
    }
}