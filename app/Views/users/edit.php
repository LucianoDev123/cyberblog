<h2>Editar usuario</h2>

<hr>

<form
    action="/incuyo/cyberblog/public/admin/users/update/<?= $user['id'] ?>"
    method="POST"
>

    <p>
        <label for="nombre">
            Nombre
        </label>
    </p>

    <input
        type="text"
        id="nombre"
        name="nombre"
        value="<?= htmlspecialchars($user['nombre']) ?>"
        required
    >

    <p>
        <label for="apellido">
            Apellido
        </label>
    </p>

    <input
        type="text"
        id="apellido"
        name="apellido"
        value="<?= htmlspecialchars($user['apellido']) ?>"
        required
    >

    <p>
        <label for="email">
            Email
        </label>
    </p>

    <input
        type="email"
        id="email"
        name="email"
        value="<?= htmlspecialchars($user['email']) ?>"
        required
    >

    <p>
        <label for="rol">
            Rol
        </label>
    </p>

   <?php if ($user['id'] === $_SESSION['usuario_id']): ?>

    <p>
        <strong>Rol</strong>
    </p>

    <p>
        No puedes modificar tu propio rol.
    </p>

    <input
        type="hidden"
        name="rol"
        value="<?= htmlspecialchars($user['rol']) ?>"
    >

<?php else: ?>

    <p>
        <label for="rol">
            Rol
        </label>
    </p>

    <select id="rol" name="rol">

        <option
            value="admin"
            <?= $user['rol'] === 'admin' ? 'selected' : '' ?>
        >
            Administrador
        </option>

        <option
            value="editor"
            <?= $user['rol'] === 'editor' ? 'selected' : '' ?>
        >
            Editor
        </option>

        <option
            value="usuario"
            <?= $user['rol'] === 'usuario' ? 'selected' : '' ?>
        >
            Usuario
        </option>

    </select>

<?php endif; ?>

    <p>
        <label for="estado">
            Estado
        </label>
    </p>

    <select id="estado" name="estado">

        <option
            value="activo"
            <?= $user['estado'] === 'activo' ? 'selected' : '' ?>
        >
            Activo
        </option>

        <option
            value="inactivo"
            <?= $user['estado'] === 'inactivo' ? 'selected' : '' ?>
        >
            Inactivo
        </option>

    </select>

    <br><br>

    <button type="submit">
        Guardar cambios
    </button>

</form>