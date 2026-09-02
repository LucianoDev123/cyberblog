<?php

declare(strict_types=1);

/*
 * Versión del CSS.
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
        href="/incuyo/cyberblog/public/assets/css/style.css?v=<?= htmlspecialchars(
            $cssVersion,
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >

</head>


<body>

<header class="site-header">

    <div class="container header-container">


        <!-- ================================================= -->
        <!-- LOGO -->
        <!-- ================================================= -->

        <a
            href="/incuyo/cyberblog/public/"
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
                href="/incuyo/cyberblog/public/"
                class="nav-link"
            >

                Inicio

            </a>


            <!-- Blog -->

            <a
                href="/incuyo/cyberblog/public/blog"
                class="nav-link"
            >

                Blog

            </a>


            <!-- Series -->

            <a
                href="/incuyo/cyberblog/public/series"
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
                href="/incuyo/cyberblog/public/blog/search"
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


            <!-- Panel administrativo -->

            <a
                href="/incuyo/cyberblog/public/admin"
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


        </nav>

    </div>

</header>


<main class="site-main">