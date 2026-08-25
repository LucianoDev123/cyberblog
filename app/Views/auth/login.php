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
                ÁREA RESTRINGIDA
            </span>

            <h1>
                Panel de acceso
            </h1>

            <p>
                Inicia sesión para acceder al panel de administración de CyberBlog.
            </p>

        </div>


        <form
            class="login-form"
            action="/incuyo/cyberblog/public/login"
            method="POST"
        >

            <div class="form-group">

                <label for="email">
                    Correo electrónico
                </label>

                <div class="input-wrapper">

                    <span class="input-icon">
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

                <label for="password">
                    Contraseña
                </label>

                <div class="input-wrapper">

                    <span class="input-icon">
                        #
                    </span>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Introduce tu contraseña"
                        autocomplete="current-password"
                        required
                    >

                </div>

            </div>


            <button
                type="submit"
                class="login-button"
            >
                <span>
                    Iniciar sesión
                </span>

                <span>
                    →
                </span>
            </button>

        </form>


        <div class="login-footer">

            <a
                href="/incuyo/cyberblog/public/"
                class="back-to-site"
            >
                ← Volver al sitio
            </a>

        </div>

    </div>

</section>