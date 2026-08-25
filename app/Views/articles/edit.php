<?php

declare(strict_types=1);

?>

<h2>Editar artículo</h2>

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
    action="/incuyo/cyberblog/public/admin/articles/update/<?= (int) $article['id'] ?>"
    method="POST"
    enctype="multipart/form-data"
>

    <!-- Token CSRF -->
    <input
        type="hidden"
        id="csrf_token"
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
        value="<?= htmlspecialchars($article['titulo']) ?>"
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

        <?php foreach ($categories as $category): ?>

            <option
                value="<?= (int) $category['id'] ?>"

                <?php if (
                    (int) $category['id'] ===
                    (int) $article['categoria_id']
                ): ?>

                    selected

                <?php endif; ?>
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
    ><?= htmlspecialchars($article['resumen']) ?></textarea>


    <p>

        <label for="contenido-editor">

            Contenido

        </label>

    </p>


    <!--
        Editor visual.

        Mostramos directamente el HTML existente
        para que las imágenes inline ya cargadas
        se mantengan en su posición.
    -->
    <div
        id="contenido-editor"
        class="content-editor"
        contenteditable="true"
        data-placeholder="Escribe el contenido del artículo aquí. Puedes pegar texto e imágenes directamente con Ctrl + V."
    ><?= $article['contenido'] ?></div>


    <!--
        Campo oculto enviado al servidor.
    -->
    <textarea
        id="contenido"
        name="contenido"
        hidden
        required
    ><?= htmlspecialchars($article['contenido']) ?></textarea>


    <p>

        <small>

            💡 Puedes editar el texto existente y pegar
            nuevas imágenes directamente con Ctrl + V.

            Las imágenes permanecerán en el orden
            exacto en el que las insertes.

        </small>

    </p>


    <p>

        <label for="imagen">

            Cambiar imagen destacada

        </label>

    </p>


    <?php if (
        !empty($article['imagen'])
    ): ?>

        <p>

            Imagen actual:

        </p>

        <img
            src="/incuyo/cyberblog/public/uploads/articles/<?= htmlspecialchars($article['imagen']) ?>"
            alt="Imagen actual del artículo"
            style="
                display: block;
                max-width: 300px;
                height: auto;
                margin-bottom: 15px;
            "
        >

    <?php else: ?>

        <p>

            <small>

                Este artículo no tiene una imagen destacada.

            </small>

        </p>

    <?php endif; ?>


    <input
        type="file"
        id="imagen"
        name="imagen"
        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
    >


    <p>

        <small>

            Si no seleccionas una nueva imagen,
            se conservará la imagen actual.

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

        <option
            value="borrador"

            <?= $article['estado'] === 'borrador'
                ? 'selected'
                : ''
            ?>
        >

            📝 Guardar como borrador

        </option>


        <option
            value="publicado"

            <?= $article['estado'] === 'publicado'
                ? 'selected'
                : ''
            ?>
        >

            🌐 Publicar artículo

        </option>

    </select>


    <br><br>


    <button type="submit">

        Guardar cambios

    </button>

</form>


<script
    src="/incuyo/cyberblog/public/assets/js/editor.js"
></script>