<?php
declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| CSS global
|--------------------------------------------------------------------------
*/
$cssPath = dirname(__DIR__, 2) . '/../public/assets/css/style.css';
$cssVersion = file_exists($cssPath) ? (string) filemtime($cssPath) : (string) time();

/*
|--------------------------------------------------------------------------
| CSS específico de página
|--------------------------------------------------------------------------
*/
$pageCss = $pageCss ?? null;
$pageCssPath = null;
$pageCssVersion = null;

if (is_string($pageCss) && $pageCss !== '') {
    // Solo permitimos nombres de archivo CSS simples.
    if (preg_match('/^[a-zA-Z0-9_-]+\.css$/', $pageCss)) {
        $pageCssPath = dirname(__DIR__, 2) . '/../public/assets/css/' . $pageCss;

        if (file_exists($pageCssPath)) {
            $pageCssVersion = (string) filemtime($pageCssPath);
        } else {
            $pageCss = null;
            $pageCssPath = null;
            $pageCssVersion = null;
        }
    } else {
        $pageCss = null;
    }
}

/*
|--------------------------------------------------------------------------
| Estado de autenticación y rol
|--------------------------------------------------------------------------
*/
$isAuthenticated = isset($_SESSION['usuario_id']);
$userRole = $_SESSION['usuario_rol'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CyberBlog - Noticias, investigación y conocimiento sobre ciberseguridad.">

    <title><?= htmlspecialchars($title ?? 'CyberBlog', ENT_QUOTES, 'UTF-8') ?></title>

    <!-- Tipografías -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Estilos globales -->
    <link
        rel="stylesheet"
        href="/assets/css/style.css?v=<?= htmlspecialchars($cssVersion, ENT_QUOTES, 'UTF-8') ?>"
    >
    <link rel="stylesheet" href="/assets/css/security-analyzer.css">
    <link rel="stylesheet" href="/assets/css/navbar.css">

    <?php if ($pageCss !== null && $pageCssVersion !== null): ?>
        <link
            rel="stylesheet"
            href="/assets/css/<?= htmlspecialchars($pageCss, ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($pageCssVersion, ENT_QUOTES, 'UTF-8') ?>"
        >
    <?php endif; ?>
</head>

<body>
<header class="site-header">
    <div class="container header-container">
        <!-- Logo -->
        <a href="/" class="brand">
            <span class="brand-mark">&gt;_</span>
            <span class="brand-text">CYBER<span>BLOG</span></span>
        </a>

        <!-- Navegación principal -->
        <nav class="main-nav" aria-label="Navegación principal">
            <a href="/" class="nav-link">Inicio</a>
            <a href="/blog" class="nav-link">Blog</a>
            <a href="/series" class="nav-link">Series</a>

            <a href="/blog/search" class="nav-link nav-link-search">
                <span class="nav-search-icon" aria-hidden="true">⌕</span>
                Buscar
            </a>

            <?php if (!$isAuthenticated): ?>
                <!-- Usuario no autenticado -->
                <a href="/login" class="nav-link">Iniciar sesión</a>
            <?php else: ?>
                <!-- Notificaciones para cualquier usuario autenticado -->
                <?php
                $notificationUnreadCount = 0;

                try {
                    $notificationUnreadCount = (new \App\Models\Notification())
                        ->countUnread((int) $_SESSION['usuario_id']);
                } catch (\Throwable $exception) {
                    error_log(
                        'Error al consultar notificaciones: ' .
                        $exception->getMessage()
                    );
                }
                ?>

                <a
                    href="/notifications"
                    class="nav-link nav-link-notifications"
                    aria-label="Mis notificaciones"
                >
                    <span aria-hidden="true">♧</span>
                    Notificaciones
                    <?php if ($notificationUnreadCount > 0): ?>
                        <span class="notification-badge">
                            <?= $notificationUnreadCount > 99 ? '99+' : (int) $notificationUnreadCount ?>
                        </span>
                    <?php endif; ?>
                </a>

                <?php if ($userRole === 'admin' || $userRole === 'editor'): ?>
                    <!-- Panel administrativo para admin/editor -->
                    <a href="/admin" class="nav-link nav-link-admin">
                        <span class="nav-admin-icon" aria-hidden="true">⌘</span>
                        Panel admin
                    </a>
                <?php endif; ?>

                <!-- Enlaces comunes a usuarios autenticados -->
                <a href="/account" class="nav-link">Mi cuenta</a>
                <a href="/logout" class="nav-link">Cerrar sesión</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<main class="site-main">
