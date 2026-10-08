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
                NUEVA CUENTA
            </span>

            <h1>
                Crear cuenta
            </h1>

            <p>
                Regístrate para participar en CyberBlog.
            </p>

        </div>


        <form
            class="login-form"
            action="/register"
            method="POST"
        >

            <div class="form-group">

                <label for="nombre">
                    Nombre
                </label>

                <div class="input-wrapper">

                    <input
                        type="text"
                        id="nombre"
                        name="nombre"
                        placeholder="Tu nombre"
                        autocomplete="given-name"
                        required
                    >

                </div>

            </div>


            <div class="form-group">

                <label for="apellido">
                    Apellido
                </label>

                <div class="input-wrapper">

                    <input
                        type="text"
                        id="apellido"
                        name="apellido"
                        placeholder="Tu apellido"
                        autocomplete="family-name"
                        required
                    >

                </div>

            </div>


            <div class="form-group">

                <label for="username">
                    Nombre de usuario
                </label>

                <div class="input-wrapper">

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="usuario"
                        autocomplete="username"
                        required
                    >

                </div>

            </div>


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
                        placeholder="usuario@ejemplo.com"
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
                        placeholder="Mínimo 8 caracteres"
                        autocomplete="new-password"
                        required
                    >

                </div>

            </div>


            <div class="form-group">

                <label for="password_confirmation">
                    Confirmar contraseña
                </label>

                <div class="input-wrapper">

                    <span class="input-icon">
                        #
                    </span>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Repite tu contraseña"
                        autocomplete="new-password"
                        required
                    >

                </div>

            </div>


            <button
                type="submit"
                class="login-button"
            >
                <span>
                    Crear cuenta
                </span>

                <span>
                    →
                </span>
            </button>

        </form>


        <div class="login-footer">

            <p>
                ¿Ya tienes una cuenta?
                <a href="/login">
                    Iniciar sesión
                </a>
            </p>

            <a
                href="/"
                class="back-to-site"
            >
                ← Volver al sitio
            </a>

        </div>

    </div>

</section>