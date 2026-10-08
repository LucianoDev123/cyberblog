<?php
declare(strict_types=1);

$oldInput = $oldInput ?? [];

$nombre = $oldInput['nombre'] ?? $user['nombre'];
$apellido = $oldInput['apellido'] ?? $user['apellido'];
$username = $oldInput['username'] ?? $user['username'];
$email = $oldInput['email'] ?? $user['email'];
?>

<section class="login-page">
    <div class="login-card">

        <div class="login-icon">
            <span>&gt;_</span>
        </div>

        <div class="login-header">
            <span class="login-eyebrow">MI CUENTA</span>

            <h1>
                <?= htmlspecialchars($user['nombre'], ENT_QUOTES, 'UTF-8') ?>
            </h1>

            <p>
                Gestioná la información de tu cuenta en CyberBlog.
            </p>
        </div>

        <?php if (!empty($flashError)): ?>
            <div class="form-message error">
                <?= htmlspecialchars($flashError, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($flashSuccess)): ?>
            <div class="form-message success">
                <?= htmlspecialchars($flashSuccess, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form
            action="/account/update"
            method="POST"
            class="account-form"
        >

            <!-- Protección CSRF -->
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
            >

            <div class="account-field">
                <label for="nombre">Nombre</label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>"
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
                    value="<?= htmlspecialchars($apellido, ENT_QUOTES, 'UTF-8') ?>"
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
                    value="<?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>"
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
                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="email"
                    required
                >
            </div>

            <div class="account-info">
                <p>
                    <strong>Rol:</strong>
                    <?= htmlspecialchars($user['rol'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <p>
                    <strong>Cuenta creada:</strong>
                    <?= htmlspecialchars($user['created_at'], ENT_QUOTES, 'UTF-8') ?>
                </p>
            </div>

            <button type="submit" class="login-button">
                Guardar cambios
            </button>

        </form>

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
                value="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>"
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



        <div class="login-footer">
            <a href="/" class="back-to-site">
                ← Volver al sitio
            </a>
        </div>

    </div>
    
</section>