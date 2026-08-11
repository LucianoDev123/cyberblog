<h2>Nuevo artículo</h2>

<hr>

<form
    action="/incuyo/cyberblog/public/admin/articles"
    method="POST"
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

<button>

Guardar artículo

</button>

</form>