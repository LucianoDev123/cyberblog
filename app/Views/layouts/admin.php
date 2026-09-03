<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars(
            $title ?? 'Panel de Administración',
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </title>

    <!--
        Estilos generales del FrontOffice.
    -->
    <link
        rel="stylesheet"
        href="/incuyo/cyberblog/public/assets/css/style.css"
    >

    <!--
        Estilos específicos del panel
        de administración.
    -->
    <link
        rel="stylesheet"
        href="/incuyo/cyberblog/public/assets/css/admin.css"
    >

    <!--
        Estilos específicos del Dashboard.
    -->
    <link
        rel="stylesheet"
        href="/incuyo/cyberblog/public/assets/css/dashboard.css"
    >

</head>

<body class="admin-body">

    <div class="admin-layout">

        <aside class="admin-sidebar">

            <div class="admin-logo">

                <a
                    href="/incuyo/cyberblog/public/admin"
                    class="admin-logo-link"
                >

                    <span class="admin-logo-symbol">
                        >_
                    </span>

                    <span class="admin-logo-text">
                        CYBER<span>BLOG</span>
                    </span>

                </a>

            </div>

            <nav class="admin-navigation">

                <div class="admin-navigation-title">
                    PANEL
                </div>

                <a
                    href="/incuyo/cyberblog/public/admin"
                    class="admin-nav-link"
                >
                    <span class="admin-nav-icon">
                        ⌂
                    </span>
                    <span>
                        Dashboard
                    </span>
                </a>

                <a
                    href="/incuyo/cyberblog/public/admin/articles"
                    class="admin-nav-link"
                >
                    <span class="admin-nav-icon">
                        ▤
                    </span>
                    <span>
                        Artículos
                    </span>
                </a>

                <a
                    href="/incuyo/cyberblog/public/admin/series"
                    class="admin-nav-link"
                >
                    <span class="admin-nav-icon">
                        ≡
                    </span>
                    <span>
                        Series
                    </span>
                </a>

                <a
                    href="/incuyo/cyberblog/public/admin/categories"
                    class="admin-nav-link"
                >
                    <span class="admin-nav-icon">
                        ▦
                    </span>
                    <span>
                        Categorías
                    </span>
                </a>

                <a
                    href="/incuyo/cyberblog/public/admin/users"
                    class="admin-nav-link"
                >
                    <span class="admin-nav-icon">
                        ◉
                    </span>
                    <span>
                        Usuarios
                    </span>
                </a>

                <div class="admin-navigation-separator"></div>

                <div class="admin-navigation-title">
                    SITIO
                </div>

                <a
                    href="/incuyo/cyberblog/public/"
                    class="admin-nav-link"
                >
                    <span class="admin-nav-icon">
                        ↗
                    </span>
                    <span>
                        Ver Blog
                    </span>
                </a>

                <a
                    href="/incuyo/cyberblog/public/logout"
                    class="admin-nav-link admin-nav-logout"
                >
                    <span class="admin-nav-icon">
                        ⏻
                    </span>
                    <span>
                        Cerrar sesión
                    </span>
                </a>

            </nav>

            <div class="admin-sidebar-footer">
                <span class="admin-status-dot"></span>
                <span>
                    Sistema operativo
                </span>
            </div>

        </aside>

        <div class="admin-main">

            <header class="admin-header">

                <div class="admin-header-left">

                    <h1 class="admin-page-title">

                        <?= htmlspecialchars(
                            $title ?? 'Panel de Administración',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </h1>

                </div>

                <div class="admin-header-right">

                    <a
                        href="/incuyo/cyberblog/public/"
                        class="admin-header-link"
                        title="Ver Blog"
                    >
                        ↗
                    </a>

                    <a
                        href="/incuyo/cyberblog/public/logout"
                        class="admin-header-link admin-header-logout"
                        title="Cerrar sesión"
                    >
                        ⏻
                    </a>

                </div>

            </header>

            <main class="admin-content">

                <?php if (!empty($flashError)): ?>

                    <div
                        class="admin-alert admin-alert-error"
                    >
                        <?= htmlspecialchars(
                            $flashError,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                <?php endif; ?>

                <?php if (!empty($flashSuccess)): ?>

                    <div
                        class="admin-alert admin-alert-success"
                    >
                        <?= htmlspecialchars(
                            $flashSuccess,
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </div>

                <?php endif; ?>

                <?= $content ?>

            </main>

            <footer class="admin-footer">

                <span>
                    © <?= date('Y') ?>
                    CyberBlog
                </span>

                <span class="admin-footer-version">
                    Panel de Administración
                </span>

            </footer>

        </div>

    </div>

    <script
        src="/incuyo/cyberblog/public/assets/js/editor.js"
    ></script>

</body>

</html>