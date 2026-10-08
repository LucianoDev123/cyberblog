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
                RECUPERACIÓN DE CUENTA
            </span>

            <h1>
                Recuperá tu contraseña
            </h1>

            <p>
                Ingresá el correo electrónico asociado a tu cuenta
                y te enviaremos las instrucciones para restablecer
                tu contraseña.
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
            action="/forgot-password"
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

            <button
                type="submit"
                class="login-button"
            >
                Enviar instrucciones
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