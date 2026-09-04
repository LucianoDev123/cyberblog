<?php

declare(strict_types=1);

/*
 * Normalizamos los datos recibidos desde el controlador.
 *
 * El campo password_confirmation solamente se utiliza
 * para validar la coincidencia de contraseñas.
 * Nunca debe guardarse en la base de datos.
 */
$oldInput = $oldInput ?? [];

$flashError = $flashError ?? null;
$flashSuccess = $flashSuccess ?? null;

$csrfToken = $csrfToken ?? '';
?>

<div class="users-admin-page">

    <!-- Encabezado de la página. -->
    <div class="users-page-header">

        <div class="users-page-heading">

            <span class="users-page-eyebrow">
                Administración
            </span>

            <h1 class="users-page-title">
                Nuevo usuario
            </h1>

            <p class="users-page-description">
                Creá una nueva cuenta para acceder a CyberBlog.
            </p>

        </div>

        <a
            href="/incuyo/cyberblog/public/admin/users"
            class="users-secondary-button"
        >
            ← Volver a usuarios
        </a>

    </div>


    <!-- Mensaje de error enviado por el controlador. -->
    <?php if ($flashError !== null): ?>

        <div class="users-form-alert users-form-alert-error">

            <strong>
                ❌
            </strong>

            <span>
                <?= htmlspecialchars(
                    (string) $flashError,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </span>

        </div>

    <?php endif; ?>


    <!-- Mensaje de éxito, si existiera. -->
    <?php if ($flashSuccess !== null): ?>

        <div class="users-form-alert users-form-alert-success">

            <strong>
                ✅
            </strong>

            <span>
                <?= htmlspecialchars(
                    (string) $flashSuccess,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </span>

        </div>

    <?php endif; ?>


    <!-- Formulario de creación de usuario. -->
    <div class="users-form-card">

        <div class="users-form-card-header">

            <div>

                <h2 class="users-form-title">
                    Datos de la cuenta
                </h2>

                <p class="users-form-description">
                    Completá la información del nuevo usuario.
                </p>

            </div>

        </div>


        <form
            action="/incuyo/cyberblog/public/admin/users"
            method="POST"
            class="users-form"
            id="create-user-form"
        >

            <!-- Token de protección CSRF. -->
            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(
                    (string) $csrfToken,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >


            <!-- Nombre. -->
            <div class="users-form-field">

                <label
                    for="nombre"
                    class="users-form-label"
                >
                    Nombre
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="users-form-input"
                    value="<?= htmlspecialchars(
                        (string) ($oldInput['nombre'] ?? ''),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >

            </div>


            <!-- Apellido. -->
            <div class="users-form-field">

                <label
                    for="apellido"
                    class="users-form-label"
                >
                    Apellido
                </label>

                <input
                    type="text"
                    id="apellido"
                    name="apellido"
                    class="users-form-input"
                    value="<?= htmlspecialchars(
                        (string) ($oldInput['apellido'] ?? ''),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >

            </div>


            <!-- Nombre de usuario. -->
            <div class="users-form-field">

                <label
                    for="username"
                    class="users-form-label"
                >
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    class="users-form-input"
                    value="<?= htmlspecialchars(
                        (string) ($oldInput['username'] ?? ''),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >

            </div>


            <!-- Correo electrónico. -->
            <div class="users-form-field">

                <label
                    for="email"
                    class="users-form-label"
                >
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    class="users-form-input"
                    value="<?= htmlspecialchars(
                        (string) ($oldInput['email'] ?? ''),
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    required
                >

            </div>


            <!-- Contraseña. -->
            <div class="users-form-field">

                <label
                    for="password"
                    class="users-form-label"
                >
                    Contraseña
                </label>

                <div class="users-password-wrapper">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="users-form-input users-password-input"
                        minlength="8"
                        required
                    >

                    <!--
                     * Este botón solamente cambia la visibilidad
                     * de los campos de contraseña.
                     *
                     * No modifica ni almacena la contraseña.
                     -->
                    <button
                        type="button"
                        class="users-password-toggle"
                        data-password-toggle
                        aria-label="Mostrar contraseñas"
                        aria-pressed="false"
                    >
                        <span
                            class="users-password-toggle-icon"
                            aria-hidden="true"
                        >
                            👁
                        </span>

                        <span
                            class="users-password-toggle-text"
                        >
                            Mostrar
                        </span>
                    </button>

                </div>

                <small class="users-form-help">
                    La contraseña debe tener al menos 8 caracteres.
                </small>

            </div>


            <!-- Confirmación de contraseña. -->
            <div class="users-form-field">

                <label
                    for="password_confirmation"
                    class="users-form-label"
                >
                    Confirmar contraseña
                </label>

                <div class="users-password-wrapper">

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="users-form-input users-password-input"
                        minlength="8"
                        required
                    >

                </div>

                <!--
                 * Este mensaje se actualiza mediante JavaScript.
                 * La validación definitiva también se realizará
                 * en UserController::store().
                 -->
                <small
                    id="password-match-message"
                    class="users-password-match-message"
                    aria-live="polite"
                ></small>

            </div>


            <!-- Rol. -->
            <div class="users-form-field">

                <label
                    for="rol"
                    class="users-form-label"
                >
                    Rol
                </label>

                <select
                    id="rol"
                    name="rol"
                    class="users-form-input users-form-select"
                >

                    <option
                        value="usuario"
                        <?= ($oldInput['rol'] ?? '') === 'usuario'
                            ? 'selected'
                            : '' ?>
                    >
                        Usuario
                    </option>

                    <option
                        value="editor"
                        <?= ($oldInput['rol'] ?? '') === 'editor'
                            ? 'selected'
                            : '' ?>
                    >
                        Editor
                    </option>

                    <option
                        value="admin"
                        <?= ($oldInput['rol'] ?? '') === 'admin'
                            ? 'selected'
                            : '' ?>
                    >
                        Administrador
                    </option>

                </select>

            </div>


            <!-- Estado. -->
            <div class="users-form-field">

                <label
                    for="estado"
                    class="users-form-label"
                >
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                    class="users-form-input users-form-select"
                >

                    <option
                        value="1"
                        <?= ($oldInput['estado'] ?? '') === '1'
                            ? 'selected'
                            : '' ?>
                    >
                        Activo
                    </option>

                    <option
                        value="0"
                        <?= ($oldInput['estado'] ?? '') === '0'
                            ? 'selected'
                            : '' ?>
                    >
                        Inactivo
                    </option>

                </select>

            </div>


            <!-- Acciones del formulario. -->
            <div class="users-form-actions">

                <a
                    href="/incuyo/cyberblog/public/admin/users"
                    class="users-secondary-button"
                >
                    Cancelar
                </a>

                <button
                    type="submit"
                    class="users-primary-button"
                    id="create-user-submit"
                >
                    Crear usuario
                </button>

            </div>

        </form>

    </div>

</div>


<script>
    /*
     * Esperamos a que el documento esté cargado.
     * Así nos aseguramos de que todos los elementos
     * del formulario existan antes de utilizarlos.
     */
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('create-user-form');

        const passwordInput =
            document.getElementById('password');

        const confirmationInput =
            document.getElementById('password_confirmation');

        const matchMessage =
            document.getElementById('password-match-message');

        const toggleButton =
            document.querySelector('[data-password-toggle]');

        const toggleText =
            document.querySelector('.users-password-toggle-text');

        const toggleIcon =
            document.querySelector('.users-password-toggle-icon');


        /*
         * Actualiza el mensaje visual de coincidencia.
         *
         * No muestra las contraseñas ni las imprime
         * en la consola del navegador.
         */
        function validatePasswordMatch() {

            const password =
                passwordInput.value;

            const confirmation =
                confirmationInput.value;


            /*
             * Si todavía no se escribió la confirmación,
             * no mostramos un mensaje de error.
             */
            if (confirmation === '') {

                matchMessage.textContent = '';

                matchMessage.className =
                    'users-password-match-message';

                confirmationInput.setCustomValidity('');

                return;
            }


            if (password === confirmation) {

                matchMessage.textContent =
                    '✓ Las contraseñas coinciden.';

                matchMessage.className =
                    'users-password-match-message users-password-match-success';

                confirmationInput.setCustomValidity('');

            } else {

                matchMessage.textContent =
                    '✕ Las contraseñas no coinciden.';

                matchMessage.className =
                    'users-password-match-message users-password-match-error';

                /*
                 * setCustomValidity() impide el envío normal
                 * del formulario mientras no coincidan.
                 */
                confirmationInput.setCustomValidity(
                    'Las contraseñas no coinciden.'
                );

            }

        }


        /*
         * Muestra u oculta ambas contraseñas.
         */
        toggleButton.addEventListener('click', function () {

            const shouldShow =
                passwordInput.type === 'password';


            passwordInput.type =
                shouldShow ? 'text' : 'password';

            confirmationInput.type =
                shouldShow ? 'text' : 'password';


            toggleText.textContent =
                shouldShow ? 'Ocultar' : 'Mostrar';

            toggleIcon.textContent =
                shouldShow ? '🙈' : '👁';

            toggleButton.setAttribute(
                'aria-label',
                shouldShow
                    ? 'Ocultar contraseñas'
                    : 'Mostrar contraseñas'
            );

            toggleButton.setAttribute(
                'aria-pressed',
                shouldShow ? 'true' : 'false'
            );

        });


        /*
         * Validamos mientras el usuario escribe.
         */
        passwordInput.addEventListener(
            'input',
            validatePasswordMatch
        );

        confirmationInput.addEventListener(
            'input',
            validatePasswordMatch
        );


        /*
         * Validación adicional justo antes del envío.
         */
        form.addEventListener('submit', function (event) {

            validatePasswordMatch();

            if (!form.checkValidity()) {

                event.preventDefault();

            }

        });

    });
</script>