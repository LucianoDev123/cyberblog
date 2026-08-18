<h2>Nuevo usuario</h2>

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
    action="/incuyo/cyberblog/public/admin/users"
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
        value="<?= htmlspecialchars($oldInput['nombre'] ?? '') ?>"
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
        value="<?= htmlspecialchars($oldInput['apellido'] ?? '') ?>"
        required
    >

    <p>
        <label for="username">
            Username
        </label>
    </p>

    <input
        type="text"
        id="username"
        name="username"
        value="<?= htmlspecialchars($oldInput['username'] ?? '') ?>"
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
        value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>"
        required
    >


    <p>
        <label for="password">
            Contraseña
        </label>
    </p>

    <input
        type="password"
        id="password"
        name="password"
        required
    >


    <p>
        <label for="rol">
            Rol
        </label>
    </p>

    <select
        id="rol"
        name="rol"
    >

        <option
            value="usuario"
            <?= ($oldInput['rol'] ?? '') === 'usuario' ? 'selected' : '' ?>
        >
            Usuario
        </option>

        <option
            value="editor"
            <?= ($oldInput['rol'] ?? '') === 'editor' ? 'selected' : '' ?>
        >
            Editor
        </option>

        <option
            value="admin"
            <?= ($oldInput['rol'] ?? '') === 'admin' ? 'selected' : '' ?>
        >
            Administrador
        </option>

    
    </select>


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
            <?= ($oldInput['estado'] ?? '') === '1' ? 'selected' : '' ?>
        >
            Activo
        </option>

        <option
            value="0"
            <?= ($oldInput['estado'] ?? '') === '0' ? 'selected' : '' ?>
        >
            Inactivo
        </option>

    </select>


    <br><br>


    <button type="submit">
        Crear usuario
    </button>

</form>