<?php

declare(strict_types=1);
?>

<section class="login-page">
    <div class="login-card">

        <div class="login-icon">
            <span>&gt;_</span>
        </div>

        <div class="login-header">
            <span class="login-eyebrow">
                RESTABLECIMIENTO DE CUENTA
            </span>

            <h1>Restablecé tu contraseña</h1>

            <p>
                Ingresá el correo asociado a tu cuenta,
                el código que recibiste y elegí una nueva contraseña.
            </p>
        </div>


        <?php if (!empty($flashError)): ?>

            <div class="form-message error">
                <?= htmlspecialchars(
                    $flashError,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </div>

        <?php endif; ?>


        <?php if (!empty($flashSuccess)): ?>

            <div class="form-message success">
                <?= htmlspecialchars(
                    $flashSuccess,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </div>

        <?php endif; ?>


        <form
            action="/reset-password"
            method="POST"
            class="account-form"
        >

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(
                    $csrfToken,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >


            <div class="account-field">

                <label for="email">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    placeholder="usuario@ejemplo.com"
                    required
                >

            </div>


            <div class="account-field">

                <label for="token">
                    Código de recuperación
                </label>

                <input
                    type="text"
                    id="token"
                    name="token"
                    autocomplete="one-time-code"
                    placeholder="XXXX-XXXX-XXXX"
                    maxlength="14"
                    minlength="12"
                    pattern="[A-Za-z0-9]{4}-?[A-Za-z0-9]{4}-?[A-Za-z0-9]{4}"
                    required
                >

                <small class="account-field-help">
                    El código tiene una validez de 15 minutos.
                </small>

            </div>


            <div class="account-field">

                <label for="password">
                    Nueva contraseña
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="new-password"
                    placeholder="Ingresá tu nueva contraseña"
                    minlength="8"
                    required
                >

            </div>


            <div class="account-field">

                <label for="password_confirmation">
                    Confirmar contraseña
                </label>

                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    autocomplete="new-password"
                    placeholder="Repetí tu nueva contraseña"
                    minlength="8"
                    required
                >

            </div>


            <button
                type="submit"
                class="login-button"
            >
                Restablecer contraseña
            </button>

        </form>


        <div class="login-footer">

            <a
                href="/login"
                class="back-to-site"
            >
                ← Volver al inicio de sesión
            </a>

        </div>

    </div>
</section>