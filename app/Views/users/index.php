<h2>Administración de Usuarios</h2>

<hr>

<table border="1" cellpadding="8">

    <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Estado</th>
        <th>Fecha de registro</th>
        <th>Acciones</th>
    </tr>

    <?php foreach ($users as $user): ?>

        <tr>

            <td>
                <?= $user['id'] ?>
            </td>

            <td>
                <?= htmlspecialchars(
                    $user['nombre'] . ' ' . $user['apellido']
                ) ?>
            </td>

            <td>
                <?= htmlspecialchars($user['email']) ?>
            </td>

            <td>
                <?= htmlspecialchars($user['rol']) ?>
            </td>

            <td>
                <?= htmlspecialchars($user['estado']) ?>
            </td>

            <td>
                    <?= htmlspecialchars($user['created_at']) ?>
            </td>

            <td>

                    <a href="/incuyo/cyberblog/public/admin/users/edit/<?= $user['id'] ?>">
                        ✏️ Editar
                    </a>

            </td>

        </tr>

    <?php endforeach; ?>

</table>