<?php

declare(strict_types=1);

?>

<h2>Editar usuario</h2>

<hr>


<?php if ($flashError !== null): ?>

    <p>
        <strong>
            ❌ <?= htmlspecialchars($flashError) ?>
        </strong>
    </p>

<?php endif; ?>


<?php if ($flashSuccess !== null): ?>

    <p>
        <strong>
            ✅ <?= htmlspecialchars($flashSuccess) ?>
        </strong>
    </p>

<?php endif; ?>


<form
    action="/incuyo/cyberblog/public/admin/users/update/<?= (int) $user['id'] ?>"
    method="POST"
>

    <!-- Token de protección CSRF -->
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($csrfToken) ?>"
    >


    <!-- =====================================================
         NOMBRE
         ===================================================== -->

    <p>
        <label for="nombre">
            Nombre
        </label>
    </p>

    <input
        type="text"
        id="nombre"
        name="nombre"
        value="<?= htmlspecialchars(
            $oldInput['nombre'] ?? $user['nombre']
        ) ?>"
        required
    >


    <!-- =====================================================
         APELLIDO
         ===================================================== -->

    <p>
        <label for="apellido">
            Apellido
        </label>
    </p>

    <input
        type="text"
        id="apellido"
        name="apellido"
        value="<?= htmlspecialchars(
            $oldInput['apellido'] ?? $user['apellido']
        ) ?>"
        required
    >


    <!-- =====================================================
         USERNAME
         ===================================================== -->

    <p>
        <label for="username">
            Username
        </label>
    </p>

    <input
        type="text"
        id="username"
        name="username"
        value="<?= htmlspecialchars($user['username']) ?>"
        readonly
    >


    <!-- =====================================================
         EMAIL
         ===================================================== -->

    <p>
        <label for="email">
            Email
        </label>
    </p>

    <input
        type="email"
        id="email"
        name="email"
        value="<?= htmlspecialchars(
            $oldInput['email'] ?? $user['email']
        ) ?>"
        required
    >


    <!-- =====================================================
         ROL
         ===================================================== -->

    <p>
        <label for="rol">
            Rol
        </label>
    </p>

    <select
        id="rol"
        name="rol"
        <?= $user['id'] === $_SESSION['usuario_id'] ? 'disabled' : '' ?>
    >

        <option
            value="usuario"
            <?= (
                ($oldInput['rol'] ?? $user['rol']) === 'usuario'
            ) ? 'selected' : '' ?>
        >
            Usuario
        </option>

        <option
            value="editor"
            <?= (
                ($oldInput['rol'] ?? $user['rol']) === 'editor'
            ) ? 'selected' : '' ?>
        >
            Editor
        </option>

        <option
            value="admin"
            <?= (
                ($oldInput['rol'] ?? $user['rol']) === 'admin'
            ) ? 'selected' : '' ?>
        >
            Administrador
        </option>

    </select>


    <?php if ($user['id'] === $_SESSION['usuario_id']): ?>

        <p>
            <small>
                No puedes modificar tu propio rol.
            </small>
        </p>

        <input
            type="hidden"
            name="rol"
            value="<?= htmlspecialchars($user['rol']) ?>"
        >

    <?php endif; ?>


    <!-- =====================================================
         ESTADO
         ===================================================== -->

    <p>
        <label for="estado">
            Estado
        </label>
    </p>

    <select
        id="estado"
        name="estado"
    >

        <option
            value="1"
            <?= (
                ($oldInput['estado'] ?? (string) $user['estado']) === '1'
            ) ? 'selected' : '' ?>
        >
            Activo
        </option>

        <option
            value="0"
            <?= (
                ($oldInput['estado'] ?? (string) $user['estado']) === '0'
            ) ? 'selected' : '' ?>
        >
            Inactivo
        </option>

    </select>


    <br><br>


    <button type="submit">
        Guardar cambios
    </button>

</form>