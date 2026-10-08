<?php

declare(strict_types=1);

/*
 * =========================================================
 * CSS GLOBAL
 * =========================================================
 *
 * Utilizamos la fecha de modificación del archivo
 * para evitar problemas de caché del navegador.
 */

$cssPath =
    dirname(__DIR__, 2) .
    '/../public/assets/css/style.css';

$cssVersion =
    file_exists($cssPath)
        ? (string) filemtime($cssPath)
        : (string) time();


/*
 * =========================================================
 * CSS ESPECÍFICO DE PÁGINA
 * =========================================================
 *
 * Algunas páginas necesitan estilos propios que no deben
 * cargarse en todo el sitio.
 *
 * Ejemplo:
 *
 * $pageCss = 'article.css';
 *
 * El valor debe ser solamente el nombre del archivo CSS.
 */

$pageCss = $pageCss ?? null;

$pageCssPath = null;
$pageCssVersion = null;

if (
    is_string($pageCss) &&
    $pageCss !== ''
) {

    /*
     * Permitimos únicamente nombres de archivo CSS simples.
     *
     * Esto evita que una variable de vista pueda utilizar
     * rutas arbitrarias.
     */
    if (
        preg_match(
            '/^[a-zA-Z0-9_-]+\.css$/',
            $pageCss
        )
    ) {

        $pageCssPath =
            dirname(__DIR__, 2) .
            '/../public/assets/css/' .
            $pageCss;


        if (file_exists($pageCssPath)) {

            $pageCssVersion =
                (string) filemtime(
                    $pageCssPath
                );

        } else {

            /*
             * Si el archivo solicitado no existe,
             * no cargamos ninguna hoja específica.
             */
            $pageCss = null;
            $pageCssPath = null;
            $pageCssVersion = null;
        }

    } else {

        /*
         * Valor inválido: no se carga ningún CSS
         * específico.
         */
        $pageCss = null;
    }
}


/*
 * =========================================================
 * ESTADO DE AUTENTICACIÓN
 * =========================================================
 *
 * Comprobamos si existe un ID de usuario
 * almacenado en la sesión.
 */

$isAuthenticated =
    isset($_SESSION['usuario_id']);


/*
 * =========================================================
 * ROL DEL USUARIO
 * =========================================================
 *
 * Si no existe una sesión, el valor será null.
 */

$userRole =
    $_SESSION['usuario_rol'] ?? null;

?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="CyberBlog - Noticias, investigación y conocimiento sobre ciberseguridad."
    >

    <title>
        <?= htmlspecialchars(
            $title ?? 'CyberBlog',
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </title>


    <!-- ===================================================== -->
    <!-- TIPOGRAFÍAS -->
    <!-- ===================================================== -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- ===================================================== -->
    <!-- ESTILOS GLOBALES -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="/assets/css/style.css?v=<?= htmlspecialchars(
            $cssVersion,
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >

    <link
        rel="stylesheet"
        href="/assets/css/security-analyzer.css"
    >


    <?php if (
        $pageCss !== null &&
        $pageCssVersion !== null
    ): ?>

        <!-- ================================================= -->
        <!-- ESTILO ESPECÍFICO DE LA PÁGINA -->
        <!-- ================================================= -->

        <link
            rel="stylesheet"
            href="/assets/css/<?= htmlspecialchars(
                $pageCss,
                ENT_QUOTES,
                'UTF-8'
            ) ?>?v=<?= htmlspecialchars(
                $pageCssVersion,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >

    <?php endif; ?>


</head>


<body>

<header class="site-header">

    <div class="container header-container">


        <!-- ================================================= -->
        <!-- LOGO -->
        <!-- ================================================= -->

        <a
            href="/"
            class="brand"
        >

            <span class="brand-mark">
                &gt;_
            </span>


            <span class="brand-text">

                CYBER<span>BLOG</span>

            </span>

        </a>


        <!-- ================================================= -->
        <!-- NAVEGACIÓN -->
        <!-- ================================================= -->

        <nav
            class="main-nav"
            aria-label="Navegación principal"
        >


            <!-- Inicio -->

            <a
                href="/"
                class="nav-link"
            >

                Inicio

            </a>


            <!-- Blog -->

            <a
                href="/blog"
                class="nav-link"
            >

                Blog

            </a>


            <!-- Series -->

            <a
                href="/series"
                class="nav-link"
            >

                Series

            </a>


            <!--
                Enlace al buscador interno del blog.

                De esta forma el usuario puede acceder
                a la funcionalidad de búsqueda sin tener
                que escribir manualmente la URL.
            -->

            <a
                href="/blog/search"
                class="nav-link nav-link-search"
            >

                <span
                    class="nav-search-icon"
                    aria-hidden="true"
                >
                    ⌕
                </span>

                Buscar

            </a>


            <?php if (!$isAuthenticated): ?>

                <!-- ========================================= -->
                <!-- USUARIO NO AUTENTICADO -->
                <!-- ========================================= -->


                <!-- Inicio de sesión -->

                <a
                    href="/login"
                    class="nav-link"
                >

                    Iniciar sesión

                </a>


                <!-- Registro -->

                <a
                    href="/register"
                    class="nav-link"
                >

                    Registrarse

                </a>


            <?php elseif (
                $userRole === 'admin' ||
                $userRole === 'editor'
            ): ?>

                <!-- ========================================= -->
                <!-- ADMINISTRADOR / EDITOR -->
                <!-- ========================================= -->


                <!-- Panel administrativo -->

                <a
                    href="/admin"
                    class="nav-link nav-link-admin"
                >

                    <span
                        class="nav-admin-icon"
                        aria-hidden="true"
                    >
                        ⌘
                    </span>

                    Panel admin

                </a>


                <!-- Mi cuenta -->

                <a
                    href="/account"
                    class="nav-link"
                >

                    Mi cuenta

                </a>


                <!-- Cerrar sesión -->

                <a
                    href="/logout"
                    class="nav-link"
                >

                    Cerrar sesión

                </a>


            <?php else: ?>

                <!-- ========================================= -->
                <!-- USUARIO NORMAL -->
                <!-- ========================================= -->


                <!-- Mi cuenta -->

                <a
                    href="/account"
                    class="nav-link"
                >

                    Mi cuenta

                </a>


                <!-- Cerrar sesión -->

                <a
                    href="/logout"
                    class="nav-link"
                >

                    Cerrar sesión

                </a>


            <?php endif; ?>


        </nav>

    </div>

</header>


<main class="site-main">