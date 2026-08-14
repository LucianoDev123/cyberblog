<?php

declare(strict_types=1);

namespace App\Core;

// Importamos nuestro Middleware de autenticación.
// Esto permite que Router pueda llamar a AuthMiddleware::handle().
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;


class Router
{
    /*
     * Aquí almacenamos todas las rutas registradas.
     *
     * La estructura será aproximadamente:
     *
     * [
     *     'GET' => [
     *         '/' => [HomeController::class, 'index'],
     *         '/blog' => [BlogController::class, 'index'],
     *     ],
     *
     *     'POST' => [
     *         '/login' => [AuthController::class, 'authenticate'],
     *     ]
     * ]
     */
    private array $routes = [];


    /*
     * Registra una ruta GET.
     *
     * Ejemplo:
     *
     * $router->get('/blog', [BlogController::class, 'index']);
     */
    public function get(string $uri, callable|array $action): void
    {
        // Guardamos la ruta dentro del grupo GET.
        $this->routes['GET'][$uri] = $action;
    }


    /*
     * Registra una ruta POST.
     *
     * Ejemplo:
     *
     * $router->post('/login', [AuthController::class, 'authenticate']);
     */
    public function post(string $uri, callable|array $action): void
    {
        // Guardamos la ruta dentro del grupo POST.
        $this->routes['POST'][$uri] = $action;
    }


    /*
     * Este método es el encargado de procesar
     * la petición HTTP actual.
     */
    public function dispatch(): void
    {
        /*
         * Obtenemos el método HTTP utilizado.
         *
         * Puede ser:
         *
         * GET
         * POST
         */
        $requestMethod = $_SERVER['REQUEST_METHOD'];


        /*
         * Obtenemos la URL solicitada.
         *
         * Por ejemplo:
         *
         * /incuyo/cyberblog/public/blog/mi-primer-articulo
         *
         * parse_url(..., PHP_URL_PATH)
         * se queda solamente con la parte de la ruta.
         */
        $requestUri = parse_url(
            $_SERVER['REQUEST_URI'],
            PHP_URL_PATH
        );


        /*
         * Esta es la carpeta donde está instalado
         * nuestro proyecto dentro de XAMPP.
         */
        $basePath = '/incuyo/cyberblog/public';


        /*
         * Si la URL comienza con nuestro basePath,
         * lo eliminamos.
         *
         * Ejemplo:
         *
         * /incuyo/cyberblog/public/blog
         *
         * se convierte en:
         *
         * /blog
         */
        if (str_starts_with($requestUri, $basePath)) {

            $requestUri = substr(
                $requestUri,
                strlen($basePath)
            );
        }


        /*
         * Si después de quitar el basePath
         * no quedó ninguna ruta,
         * significa que estamos en la página principal.
         */
        if ($requestUri === '') {

            $requestUri = '/';
        }


        /*
         * ==================================================
         * MIDDLEWARE DE AUTENTICACIÓN
         * ==================================================
         *
         * Comprobamos si la ruta solicitada comienza
         * con /admin.
         *
         * Ejemplos:
         *
         * /admin
         * /admin/articles
         * /admin/articles/create
         * /admin/articles/edit/2
         *
         * Todas serán protegidas.
         */
        // Comprobamos si la ruta pertenece al BackOffice.
        if (str_starts_with($requestUri, '/admin')) {

        // Primero comprobamos que el usuario haya iniciado sesión.
        AuthMiddleware::handle();

            // Después comprobamos que tenga un rol permitido.
            // Por ahora permitimos administradores y editores.
        RoleMiddleware::handle(['admin', 'editor']);
        }


        /*
         * Obtenemos las rutas correspondientes
         * al método HTTP actual.
         *
         * Por ejemplo, si la petición es GET:
         *
         * $routes = $this->routes['GET'];
         */
        $routes = $this->routes[$requestMethod] ?? [];


        /*
         * Recorremos todas las rutas registradas
         * para encontrar cuál coincide con la URL.
         */
        foreach ($routes as $route => $action) {


            /*
             * Convertimos parámetros dinámicos
             * de nuestras rutas en expresiones regulares.
             *
             * Por ejemplo:
             *
             * /blog/{slug}
             *
             * se convierte en:
             *
             * /blog/([^/]+)
             */
            $pattern = preg_replace(
                '/\{[^\/]+\}/',
                '([^/]+)',
                $route
            );


            /*
             * Agregamos los delimitadores de la expresión
             * regular y hacemos que coincida con toda la URL.
             */
            $pattern = '#^' . $pattern . '$#';


            /*
             * Comprobamos si la URL solicitada coincide
             * con esta ruta.
             */
            if (!preg_match(
                $pattern,
                $requestUri,
                $matches
            )) {

                // Si no coincide, probamos la siguiente ruta.
                continue;
            }


            /*
             * $matches contiene:
             *
             * [0] => coincidencia completa
             * [1] => primer parámetro
             * [2] => segundo parámetro
             * etc.
             *
             * Eliminamos la coincidencia completa.
             */
            array_shift($matches);


            /*
             * Comprobamos si la acción es directamente
             * una función.
             */
            if (is_callable($action)) {

                call_user_func_array(
                    $action,
                    $matches
                );

                return;
            }


            /*
             * Si llegamos aquí significa que nuestra ruta
             * utiliza un Controller.
             *
             * Ejemplo:
             *
             * [
             *     BlogController::class,
             *     'show'
             * ]
             */
            [$controller, $method] = $action;


            /*
             * Creamos una instancia del Controller.
             *
             * Por ejemplo:
             *
             * new BlogController();
             */
            $controller = new $controller();


            /*
             * Ejecutamos el método del Controller.
             *
             * Si la URL era:
             *
             * /blog/mi-primer-articulo
             *
             * terminaríamos ejecutando:
             *
             * BlogController->show(
             *     'mi-primer-articulo'
             * );
             */
            call_user_func_array(
                [$controller, $method],
                $matches
            );


            /*
             * Ya encontramos y ejecutamos
             * la ruta correspondiente.
             *
             * No necesitamos seguir recorriendo las demás.
             */
            return;
        }


        /*
         * Si llegamos hasta aquí significa que
         * ninguna ruta coincidió con la URL.
         */
        http_response_code(404);

        die('404 - Página no encontrada');
    }
}