<h2>Administración de Artículos</h2>

<p>
    <a href="/incuyo/cyberblog/public/admin/articles/create">
        ➕ Nuevo artículo
    </a>
</p>

<hr>

<table border="1" cellpadding="8">

<tr>
    <th>ID</th>
    <th>Título</th>
    <th>Autor</th>
    <th>Categoría</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>

<?php foreach ($articles as $article): ?>

<tr>

    <td>
        <?= (int) $article['id'] ?>
    </td>

    <td>
        <?= htmlspecialchars($article['titulo']) ?>
    </td>

    <td>
        <?= htmlspecialchars($article['autor']) ?>
    </td>

    <td>
        <?= htmlspecialchars($article['categoria']) ?>
    </td>

    <td>
        <?= htmlspecialchars($article['estado']) ?>
    </td>

    <td>

        <!-- Todos los usuarios autorizados pueden editar artículos -->
        <a
            href="/incuyo/cyberblog/public/admin/articles/edit/<?= (int) $article['id'] ?>"
        >
             Editar
        </a>


        <?php if ($_SESSION['usuario_rol'] === 'admin'): ?>

            |


            <!--
                Solo los administradores pueden eliminar artículos.

                Utilizamos POST porque eliminar es una
                operación destructiva.
            -->
            <form
                action="/incuyo/cyberblog/public/admin/articles/delete/<?= (int) $article['id'] ?>"
                method="POST"
                style="display:inline;"
                onsubmit="return confirm('¿Estás seguro de que quieres eliminar este artículo?');"
            >

                <!-- Token de protección CSRF -->
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($csrfToken) ?>"
                >

                <button type="submit">
                     Eliminar
                </button>

            </form>

        <?php endif; ?>

    </td>

</tr>

<?php endforeach; ?>

</table>