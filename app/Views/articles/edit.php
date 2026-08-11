<h2>Editar artículo</h2>

<hr>

<form
    action="/incuyo/cyberblog/public/admin/articles/update/<?= $article['id'] ?>"
    method="POST"
>

    <p>Título</p>

    <input
        type="text"
        name="titulo"
        value="<?= htmlspecialchars($article['titulo']) ?>"
        style="width:400px;"
    >

    <p>Resumen</p>

    <textarea
        name="resumen"
        rows="4"
        cols="60"
    ><?= htmlspecialchars($article['resumen']) ?></textarea>

    <p>Contenido</p>

    <textarea
        name="contenido"
        rows="12"
        cols="60"
    ><?= htmlspecialchars($article['contenido']) ?></textarea>

    <br><br>

    <button type="submit">
        Guardar cambios
    </button>

</form>