<?php

declare(strict_types=1);

$oldInput = $oldInput ?? [];
$userComments = $userComments ?? [];

$nombre = $oldInput['nombre'] ?? $user['nombre'];
$apellido = $oldInput['apellido'] ?? $user['apellido'];
$username = $oldInput['username'] ?? $user['username'];
$email = $oldInput['email'] ?? $user['email'];

/**
 * Escapa valores antes de mostrarlos en HTML.
 */
$escape = static function ($value): string {
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
};

?>

<section class="login-page">
    <div class="login-card">

        <!-- Encabezado de la cuenta -->
        <div class="login-icon">
            <span>&gt;_</span>
        </div>

        <div class="login-header">
            <span class="login-eyebrow">MI CUENTA</span>

            <h1>
                <?= $escape($user['nombre']) ?>
            </h1>

            <p>
                Gestioná la información de tu cuenta en CyberBlog.
            </p>
        </div>

        <!-- Mensajes del sistema -->
        <?php if (!empty($flashError)): ?>
            <div class="form-message error">
                <?= $escape($flashError) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($flashSuccess)): ?>
            <div class="form-message success">
                <?= $escape($flashSuccess) ?>
            </div>
        <?php endif; ?>

        <!-- Información del perfil -->
        <form
            action="/account/update"
            method="POST"
            class="account-form"
        >

            <input
                type="hidden"
                name="csrf_token"
                value="<?= $escape($csrfToken) ?>"
            >

            <div class="account-field">
                <label for="nombre">Nombre</label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?= $escape($nombre) ?>"
                    autocomplete="given-name"
                    required
                >
            </div>

            <div class="account-field">
                <label for="apellido">Apellido</label>

                <input
                    type="text"
                    id="apellido"
                    name="apellido"
                    value="<?= $escape($apellido) ?>"
                    autocomplete="family-name"
                    required
                >
            </div>

            <div class="account-field">
                <label for="username">Nombre de usuario</label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?= $escape($username) ?>"
                    autocomplete="username"
                    required
                >
            </div>

            <div class="account-field">
                <label for="email">Correo electrónico</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= $escape($email) ?>"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="account-info">
                <p>
                    <strong>Rol:</strong>
                    <?= $escape($user['rol']) ?>
                </p>

                <p>
                    <strong>Cuenta creada:</strong>
                    <?= $escape($user['created_at']) ?>
                </p>
            </div>

            <button type="submit" class="login-button">
                Guardar cambios
            </button>

        </form>

        <!-- Seguridad: cambio de contraseña -->
        <div class="account-section">

            <div class="account-section-header">
                <span class="login-eyebrow">SEGURIDAD</span>

                <h2>Cambiar contraseña</h2>

                <p>
                    Actualizá tu contraseña para mantener segura tu cuenta.
                </p>
            </div>

            <form
                action="/account/password"
                method="POST"
                class="account-form"
            >

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= $escape($csrfToken) ?>"
                >

                <div class="account-field">
                    <label for="current_password">
                        Contraseña actual
                    </label>

                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <div class="account-field">
                    <label for="new_password">
                        Nueva contraseña
                    </label>

                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <div class="account-field">
                    <label for="password_confirmation">
                        Confirmar nueva contraseña
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="login-button"
                >
                    Cambiar contraseña
                </button>

            </form>

        </div>

        <!-- Actividad del usuario -->
        <div class="account-section account-activity">

            <div class="account-section-header">
                <span class="login-eyebrow">PARTICIPACIÓN</span>

                <h2>Mi actividad</h2>

                <p>
                    Consultá tus comentarios y respuestas en los artículos
                    de CyberBlog.
                </p>
            </div>

            <?php if (empty($userComments)): ?>

                <div class="account-info">
                    <p>
                        Todavía no realizaste comentarios ni respuestas.
                    </p>

                    <p>
                        Cuando participes en una conversación, aparecerá aquí.
                    </p>
                </div>

                <a href="/" class="back-to-site">
                    Explorar artículos →
                </a>

            <?php else: ?>

                <div class="account-comments-summary">
                    <p>
                        Tenés
                        <strong><?= count($userComments) ?></strong>
                        <?= count($userComments) === 1
                            ? 'participación'
                            : 'participaciones' ?>
                        registradas.
                    </p>
                </div>

                <div class="account-comments-list">

                    <?php foreach ($userComments as $comment): ?>

                        <?php
                        $esRespuesta = !empty(
                            $comment['comentario_padre_id']
                        );

                        $estado = strtolower(
                            (string) ($comment['estado'] ?? '')
                        );

                        $estadoTexto = match ($estado) {
                            'aprobado' => 'Aprobado',
                            'pendiente' => 'Pendiente de moderación',
                            default => ucfirst($estado ?: 'Desconocido'),
                        };

                        $estadoClase = match ($estado) {
                            'aprobado' => 'approved',
                            'pendiente' => 'pending',
                            default => 'unknown',
                        };

                        $articuloUrl = '/';
                        $slug = trim(
                            (string) ($comment['articulo_slug'] ?? '')
                        );

                        if ($slug !== '') {
                            /*
                             * Ajustaremos esta ruta si el proyecto utiliza
                             * un formato de URL diferente para los artículos.
                             */
                            $articuloUrl = '/blog/' . rawurlencode($slug);
                        }

                        $fecha = $comment['created_at'] ?? '';
                        ?>

                        <article class="account-comment-card">

                            <div class="account-comment-header">

                                <span class="account-comment-type">
                                    <?= $esRespuesta
                                        ? '↳ Respuesta'
                                        : '▤ Comentario' ?>
                                </span>

                                <span
                                    class="account-comment-status <?= $escape($estadoClase) ?>"
                                >
                                    <?= $escape($estadoTexto) ?>
                                </span>

                            </div>

                            <h3 class="account-comment-article">
                                <a href="<?= $escape($articuloUrl) ?>#comment-<?= (int) $comment['id'] ?>">
                                    <?= $escape($comment['articulo_titulo']) ?>
                                </a>
                            </h3>

                            <div class="account-comment-content">
                                <?= nl2br($escape($comment['comentario'])) ?>
                            </div>

                            <div class="account-comment-footer">

                                <?php if ($fecha !== ''): ?>
                                    <time>
                                        <?= $escape($fecha) ?>
                                    </time>
                                <?php endif; ?>

                                <a
                                    href="<?= $escape($articuloUrl) ?>#comment-<?= (int) $comment['id'] ?>"
                                    class="account-comment-link"
                                >
                                    Ir a la conversación →
                                </a>

                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>

        <!-- Volver al sitio -->
        <div class="login-footer">
            <a href="/" class="back-to-site">
                ← Volver al sitio
            </a>
        </div>

    </div>
</section>