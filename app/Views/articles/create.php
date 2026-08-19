<?php

declare(strict_types=1);

?>

<h2>Nuevo artículo</h2>

<hr>

<form
    action="/incuyo/cyberblog/public/admin/articles"
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
        style="width:400px;"
    >

    <p>
        Resumen
    </p>

    <textarea
        name="resumen"
        rows="4"
        cols="60"
    ></textarea>

    <p>
        Contenido
    </p>

    <textarea
        name="contenido"
        rows="12"
        cols="60"
    ></textarea>

    <br><br>

    <button type="submit">
        Guardar artículo
    </button>

</form>