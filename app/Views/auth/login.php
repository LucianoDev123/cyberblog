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
                CYBERBLOG // AUTH
            </span>

            <h1>
                Iniciar sesión
            </h1>

            <p>
                Accedé a tu cuenta para continuar en CyberBlog.
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
            class="login-form"
            action="/login"
            method="POST"
        >

            <div class="form-group">

                <label for="email">
                    Correo electrónico
                </label>

                <div class="input-wrapper">

                    <span
                        class="input-icon"
                        aria-hidden="true"
                    >
                        @
                    </span>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="usuario@empresa.com"
                        autocomplete="email"
                        required
                    >

                </div>

            </div>


            <div class="form-group">

                <div class="password-label-row">

                    <label for="password">
                        Contraseña
                    </label>

                    <a
                        href="/forgot-password"
                        class="forgot-password-link"
                    >
                        ¿Olvidaste tu contraseña?
                    </a>

                </div>


                <div class="input-wrapper">

                    <span
                        class="input-icon"
                        aria-hidden="true"
                    >
                        #
                    </span>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Introducí tu contraseña"
                        autocomplete="current-password"
                        required
                    >

                    <button
                        type="button"
                        class="password-toggle"
                        id="password-toggle"
                        aria-label="Mostrar contraseña"
                        aria-pressed="false"
                    >
                        <span id="password-toggle-icon">
                            ◉
                        </span>
                    </button>

                </div>

            </div>


            <button
                type="submit"
                class="login-button"
            >

                <span>
                    Iniciar sesión
                </span>

                <span
                    class="login-button-arrow"
                    aria-hidden="true"
                >
                    →
                </span>

            </button>

        </form>


        <div class="login-register">

            <span>
                ¿Todavía no tenés una cuenta?
            </span>

            <a href="/register">
                Crear cuenta
            </a>

        </div>


        <div class="login-footer">

            <a
                href="/"
                class="back-to-site"
            >
                ← Volver al sitio
            </a>

        </div>

    </div>

</section>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const passwordInput =
        document.getElementById('password');

    const toggleButton =
        document.getElementById('password-toggle');

    const toggleIcon =
        document.getElementById('password-toggle-icon');


    if (
        !passwordInput ||
        !toggleButton ||
        !toggleIcon
    ) {
        return;
    }


    toggleButton.addEventListener('click', function () {

        const isPassword =
            passwordInput.type === 'password';


        passwordInput.type =
            isPassword ? 'text' : 'password';


        toggleButton.setAttribute(
            'aria-label',
            isPassword
                ? 'Ocultar contraseña'
                : 'Mostrar contraseña'
        );


        toggleButton.setAttribute(
            'aria-pressed',
            isPassword ? 'true' : 'false'
        );


        toggleIcon.textContent =
            isPassword ? '◉' : '◌';

    });

});
</script>