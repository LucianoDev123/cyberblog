<?php

declare(strict_types=1);

?>

<h2>Editar artículo</h2>

<hr>

<form
    action="/incuyo/cyberblog/public/admin/articles/update/<?= (int) $article['id'] ?>"
    method="POST"
>

    <!-- Token de protección CSRF -->
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($csrfToken) ?>"
    >

    <p>
        Título
    </p>

    <input
        type="text"
        name="titulo"
        value="<?= htmlspecialchars($article['titulo']) ?>"
        style="width:400px;"
        required
    >

    <p>
        Resumen
    </p>

    <textarea
        name="resumen"
        rows="4"
        cols="60"
        required
    ><?= htmlspecialchars($article['resumen']) ?></textarea>

    <p>
        Contenido
    </p>

    <textarea
        name="contenido"
        rows="12"
        cols="60"
        required
    ><?= htmlspecialchars($article['contenido']) ?></textarea>

    <br><br>

    <button type="submit">
        Guardar cambios
    </button>

</form>