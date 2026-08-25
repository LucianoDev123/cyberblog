<?php

declare(strict_types=1);

?>

<h2>Nuevo artículo</h2>

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
    action="/incuyo/cyberblog/public/admin/articles"
    method="POST"
    enctype="multipart/form-data"
>

    <!-- Token CSRF -->
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($csrfToken) ?>"
    >


    <p>

        <label for="titulo">

            Título

        </label>

    </p>


    <input
        type="text"
        id="titulo"
        name="titulo"
        style="width: 400px;"
        required
    >


    <p>

        <label for="categoria_id">

            Categoría

        </label>

    </p>


    <select
        id="categoria_id"
        name="categoria_id"
        required
    >

        <option value="">

            Selecciona una categoría

        </option>


        <?php foreach ($categories as $category): ?>

            <option
                value="<?= (int) $category['id'] ?>"
            >

                <?= htmlspecialchars(
                    $category['nombre']
                ) ?>

            </option>

        <?php endforeach; ?>

    </select>


    <p>

        <label for="resumen">

            Resumen

        </label>

    </p>


    <textarea
        id="resumen"
        name="resumen"
        rows="4"
        cols="60"
        required
    ></textarea>


    <p>

        <label for="editor">

            Contenido

        </label>

    </p>


    <!-- ========================================= -->
    <!-- BARRA DE HERRAMIENTAS DEL EDITOR -->
    <!-- ========================================= -->

    <div
        id="editorToolbar"
        class="editor-toolbar"
    >

        <!-- DESHACER / REHACER -->

        <button
            type="button"
            data-command="undo"
            title="Deshacer"
        >

            ↶

        </button>


        <button
            type="button"
            data-command="redo"
            title="Rehacer"
        >

            ↷

        </button>


        <span class="toolbar-separator"></span>


        <!-- FORMATO DE TEXTO -->

        <button
            type="button"
            data-command="bold"
            title="Negrita"
        >

            <strong>B</strong>

        </button>


        <button
            type="button"
            data-command="italic"
            title="Cursiva"
        >

            <em>I</em>

        </button>


        <button
            type="button"
            data-command="underline"
            title="Subrayado"
        >

            <u>U</u>

        </button>


        <span class="toolbar-separator"></span>


        <!-- ENCABEZADOS -->

        <button
            type="button"
            data-format="p"
            title="Párrafo"
        >

            P

        </button>


        <button
            type="button"
            data-format="h2"
            title="Título H2"
        >

            H2

        </button>


        <button
            type="button"
            data-format="h3"
            title="Título H3"
        >

            H3

        </button>


        <span class="toolbar-separator"></span>


        <!-- LISTAS -->

        <button
            type="button"
            data-command="insertUnorderedList"
            title="Lista"
        >

            • Lista

        </button>


        <button
            type="button"
            data-command="insertOrderedList"
            title="Lista numerada"
        >

            1. Lista

        </button>


        <span class="toolbar-separator"></span>


        <!-- CITA -->

        <button
            type="button"
            data-format="blockquote"
            title="Cita"
        >

            ❝

        </button>


        <!-- CÓDIGO -->

        <button
            type="button"
            data-code="true"
            title="Bloque de código"
        >

            &lt;/&gt;

        </button>


        <span class="toolbar-separator"></span>


        <!-- ENLACE -->

        <button
            type="button"
            id="linkButton"
            title="Insertar enlace"
        >

            🔗

        </button>


        <!-- SEPARADOR -->

        <button
            type="button"
            id="horizontalRuleButton"
            title="Separador"
        >

            ―

        </button>


        <span class="toolbar-separator"></span>


        <!-- LIMPIAR FORMATO -->

        <button
            type="button"
            id="removeFormatButton"
            title="Limpiar formato"
        >

            ✖

        </button>

    </div>


    <!-- ========================================= -->
    <!-- EDITOR -->
    <!-- ========================================= -->

    <div
        id="editor"
        class="article-editor"
        contenteditable="true"
        data-placeholder="Escribe el contenido del artículo aquí. Puedes pegar imágenes directamente con Ctrl + V."
    ></div>


    <!-- ========================================= -->
    <!-- CONTENIDO REAL DEL FORMULARIO -->
    <!-- ========================================= -->

    <textarea
        id="contenido"
        name="contenido"
        hidden
    ></textarea>


    <p>

        💡 Puedes escribir normalmente, utilizar
        las herramientas de formato y pegar
        capturas de pantalla o imágenes directamente
        con <strong>Ctrl + V</strong>.

    </p>


    <p>

        <label for="imagen">

            Imagen destacada

        </label>

    </p>


    <input
        type="file"
        id="imagen"
        name="imagen"
        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
    >


    <p>

        <small>

            Formatos permitidos:
            JPG, PNG y WEBP.

            Tamaño máximo:
            5 MB.

        </small>

    </p>


    <p>

        <label for="estado">

            Estado

        </label>

    </p>


    <select
        id="estado"
        name="estado"
    >

        <option value="borrador">

            📝 Guardar como borrador

        </option>


        <option value="publicado">

            🌐 Publicar artículo

        </option>

    </select>


    <br><br>


    <button type="submit">

        Guardar artículo

    </button>

</form>


<script
    src="/incuyo/cyberblog/public/assets/js/editor.js"
></script>