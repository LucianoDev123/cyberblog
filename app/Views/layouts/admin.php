<?php

// Declaramos que este archivo utilizará tipado estricto.
declare(strict_types=1);


/*
|--------------------------------------------------------------------------
| VERSIÓN DEL ARCHIVO CSS
|--------------------------------------------------------------------------
|
| filemtime() obtiene la fecha y hora de la última modificación
| del archivo style.css.
|
| Esa fecha se utiliza como parámetro "v" dentro de la URL.
|
| Ejemplo:
|
| style.css?v=1756220000
|
| Si modificamos style.css, la fecha cambia.
| Al cambiar la URL, el navegador descarga la versión nueva
| del CSS en lugar de utilizar una versión guardada en caché.
|
*/


// Definimos la ruta física del archivo CSS dentro del servidor.
$cssFile = __DIR__ . '/../../../public/assets/css/style.css';


// Verificamos que el archivo CSS realmente exista.
if (file_exists($cssFile)) {

    // Obtenemos la fecha de última modificación del archivo.
    $cssVersion = filemtime($cssFile);

} else {

    /*
    |--------------------------------------------------------------------------
    | VALOR DE RESPALDO
    |--------------------------------------------------------------------------
    |
    | Si por algún motivo PHP no encuentra el archivo,
    | utilizamos una versión fija.
    |
    */

    $cssVersion = '1';
}

?>
<!DOCTYPE html>

<html lang="es">

<head>

    <!--
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN BÁSICA DEL DOCUMENTO
    |--------------------------------------------------------------------------
    -->

    <!-- Indicamos que utilizaremos codificación UTF-8. -->
    <meta charset="UTF-8">


    <!--
        Permitimos que el diseño se adapte correctamente
        a dispositivos móviles y pantallas pequeñas.
    -->
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >


    <!--
        Descripción general del sitio.
        Puede ser utilizada por motores de búsqueda.
    -->
    <meta
        name="description"
        content="CyberBlog - Noticias, investigación y conocimiento sobre ciberseguridad."
    >


    <!--
        Mostramos el título definido por cada controlador.
        Si no existe la variable $title, utilizamos CyberBlog.
    -->
    <title>
        <?= htmlspecialchars($title ?? 'CyberBlog') ?>
    </title>


    <!--
    |--------------------------------------------------------------------------
    | TIPOGRAFÍAS
    |--------------------------------------------------------------------------
    -->


    <!--
        Creamos una conexión previa con Google Fonts.
        Esto ayuda a mejorar la carga de las tipografías.
    -->
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >


    <!--
        Creamos una conexión previa con el servidor
        que entrega los archivos de las fuentes.
    -->
    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >


    <!--
        Cargamos las dos tipografías principales:

        JetBrains Mono:
        Se utiliza para elementos con estilo técnico
        o similares a una terminal.

        Inter:
        Se utiliza como fuente principal del sitio.
    -->
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!--
    |--------------------------------------------------------------------------
    | ESTILOS GLOBALES
    |--------------------------------------------------------------------------
    -->


    <!--
        Cargamos el archivo CSS principal del sitio.

        Agregamos ?v= seguido de la fecha de última modificación.

        De esta forma evitamos que el navegador continúe
        mostrando una versión antigua almacenada en caché.
    -->
    <link
        rel="stylesheet"
        href="/incuyo/cyberblog/public/assets/css/style.css?v=<?= $cssVersion ?>"
    >

</head>


<body>


<!--
|--------------------------------------------------------------------------
| HEADER PRINCIPAL
|--------------------------------------------------------------------------
-->

<header class="site-header">

    <!--
        Contenedor principal del encabezado.
    -->
    <div class="container header-container">


        <!--
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        -->


        <!--
            El logo funciona también como enlace
            hacia la página principal.
        -->
        <a
            href="/incuyo/cyberblog/public/"
            class="brand"
        >

            <!--
                Icono visual con estética de terminal.
            -->
            <span class="brand-mark">

                &gt;_

            </span>


            <!--
                Nombre principal del sitio.
            -->
            <span class="brand-text">

                CYBER<span>BLOG</span>

            </span>

        </a>


        <!--
        |--------------------------------------------------------------------------
        | NAVEGACIÓN PRINCIPAL
        |--------------------------------------------------------------------------
        -->


        <!--
            aria-label permite identificar la navegación
            para tecnologías de asistencia.
        -->
        <nav
            class="main-nav"
            aria-label="Navegación principal"
        >


            <!--
                Enlace hacia la página principal.
            -->
            <a
                href="/incuyo/cyberblog/public/"
                class="nav-link"
            >
                Inicio
            </a>


            <!--
                Enlace hacia el listado de artículos.
            -->
            <a
                href="/incuyo/cyberblog/public/blog"
                class="nav-link"
            >
                Blog
            </a>


            <!--
                Enlace hacia el buscador interno.

                El usuario puede acceder a la búsqueda
                mediante la interfaz sin escribir
                manualmente la URL.
            -->
            <a
                href="/incuyo/cyberblog/public/blog/search"
                class="nav-link nav-link-search"
            >

                <!--
                    Icono decorativo del buscador.
                -->
                <span
                    class="nav-search-icon"
                    aria-hidden="true"
                >

                    ⌕

                </span>


                <!--
                    Texto visible del enlace.
                -->
                Buscar

            </a>


            <!--
                Enlace hacia el panel administrativo.
            -->
            <a
                href="/incuyo/cyberblog/public/admin"
                class="nav-link nav-link-admin"
            >

                <!--
                    Icono visual del panel administrativo.
                -->
                <span
                    class="nav-admin-icon"
                    aria-hidden="true"
                >

                    ⌘

                </span>


                <!--
                    Texto visible del botón administrativo.
                -->
                Panel admin

            </a>

        </nav>

    </div>

</header>


<!--
|--------------------------------------------------------------------------
| CONTENIDO PRINCIPAL
|--------------------------------------------------------------------------
|
| Esta etiqueta envuelve el contenido específico
| de cada página de CyberBlog.
|
-->

<main class="site-main">