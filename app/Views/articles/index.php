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

    <td><?= $article['id'] ?></td>

    <td><?= htmlspecialchars($article['titulo']) ?></td>

    <td><?= htmlspecialchars($article['autor']) ?></td>

    <td><?= htmlspecialchars($article['categoria']) ?></td>

    <td><?= htmlspecialchars($article['estado']) ?></td>

    <td>
        <a href="/incuyo/cyberblog/public/admin/articles/edit/<?= $article['id'] ?>">
            ✏️ Editar
        </a>
    </td>

</tr>

<?php endforeach; ?>

</table>