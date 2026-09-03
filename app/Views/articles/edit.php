<?php

declare(strict_types=1);

?>

<section class="article-page article-form-page">


    <!-- ========================================= -->
    <!-- ENCABEZADO -->
    <!-- ========================================= -->

    <div class="article-page-header">

        <div>

            <span class="article-eyebrow">

                ADMINISTRACIÓN

            </span>


            <h2>

                Editar artículo

            </h2>


            <p>

                Modifica el contenido, la información
                y la configuración de este artículo.

            </p>

        </div>


        <a
            href="/incuyo/cyberblog/public/admin/articles"
            class="admin-button admin-button-secondary"
        >

            ← Volver a artículos

        </a>

    </div>


    <!-- ========================================= -->
    <!-- FORMULARIO -->
    <!-- ========================================= -->

    <form
        action="/incuyo/cyberblog/public/admin/articles/update/<?= (int) $article['id'] ?>"
        method="POST"
        enctype="multipart/form-data"
        class="article-form"
    >


        <!-- ===================================== -->
        <!-- TOKEN CSRF -->
        <!-- ===================================== -->

        <input
            type="hidden"
            id="csrf_token"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $csrfToken,
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >


        <!-- ===================================== -->
        <!-- INFORMACIÓN DEL ARTÍCULO -->
        <!-- ===================================== -->

        <div class="article-form-card">

            <div class="article-form-section-header">

                <div class="article-form-section-number">
                    01
                </div>

                <div>
                    <h3>
                        Información del artículo
                    </h3>

                    <p>
                        Define los datos principales del contenido.
                    </p>
                </div>

            </div>

        <!-- ===================================== -->
        <!-- TÍTULO -->
        <!-- ===================================== -->

        <div class="admin-form-group">

            <label for="titulo">

                Título

            </label>


            <input
                type="text"
                id="titulo"
                name="titulo"
                value="<?= htmlspecialchars(
                    $article['titulo'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                required
            >

        </div>


        <!-- ===================================== -->
        <!-- CATEGORÍA -->
        <!-- ===================================== -->

        <div class="admin-form-group">

            <label for="categoria_id">

                Categoría

            </label>


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
                            $category['nombre'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>


        <!-- ===================================== -->
        <!-- SERIE -->
        <!-- ===================================== -->

        <div class="admin-form-group">

            <label for="serie_id">

                Serie

            </label>


            <select
                id="serie_id"
                name="serie_id"
            >

                <option value="">

                    Sin serie

                </option>


                <?php foreach ($series as $serie): ?>

                    <option
                        value="<?= (int) $serie['id'] ?>"

                        <?php if (
                            (int) $serie['id'] ===
                            (int) ($article['serie_id'] ?? 0)
                        ): ?>

                            selected

                        <?php endif; ?>
                    >

                        <?= htmlspecialchars(
                            $serie['titulo'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </option>

                <?php endforeach; ?>

            </select>


            <small>

                Puedes asociar este artículo a una serie
                o dejarlo sin serie.

            </small>

        </div>


        <!-- ===================================== -->
        <!-- RESUMEN -->
        <!-- ===================================== -->

        <div class="admin-form-group">

            <label for="resumen">

                Resumen

            </label>


            <textarea
                id="resumen"
                name="resumen"
                rows="4"
                required
            ><?= htmlspecialchars(
                $article['resumen'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?></textarea>

        </div>


        </div>


        <!-- ===================================== -->
        <!-- CONTENIDO -->
        <!-- ===================================== -->

        <div class="article-form-card">

            <div class="article-form-section-header">

                <div class="article-form-section-number">
                    02
                </div>

                <div>
                    <h3>
                        Contenido
                    </h3>

                    <p>
                        Escribe y da formato al contenido del artículo.
                    </p>
                </div>

            </div>


            <div class="admin-form-group">

                <label for="editor">
                    Contenido
                </label>


                <!-- BARRA DE HERRAMIENTAS -->

                <div
                    id="editorToolbar"
                    class="editor-toolbar"
                >

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


                    <button
                        type="button"
                        data-format="blockquote"
                        title="Cita"
                    >
                        ❝
                    </button>

                    <button
                        type="button"
                        data-code="true"
                        title="Bloque de código"
                    >
                        &lt;/&gt;
                    </button>

                    <span class="toolbar-separator"></span>


                    <button
                        type="button"
                        id="linkButton"
                        title="Insertar enlace"
                    >
                        🔗
                    </button>

                    <button
                        type="button"
                        id="horizontalRuleButton"
                        title="Separador"
                    >
                        —
                    </button>

                    <span class="toolbar-separator"></span>


                    <button
                        type="button"
                        id="removeFormatButton"
                        title="Limpiar formato"
                    >
                        ×
                    </button>

                </div>


                <!-- EDITOR -->

                <div
                    id="editor"
                    class="article-editor"
                    contenteditable="true"
                    data-placeholder="Escribe el contenido del artículo aquí. Puedes pegar imágenes directamente con Ctrl + V."
                ><?= $article['contenido'] ?></div>


                <!-- CONTENIDO REAL ENVIADO -->

                <textarea
                    id="contenido"
                    name="contenido"
                    hidden
                    required
                ><?= htmlspecialchars(
                    $article['contenido'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?></textarea>


                <small>
                    💡 Puedes utilizar las herramientas de formato
                    y editar el contenido existente directamente
                    dentro del editor.
                </small>

            </div>

        </div>


        <!-- ===================================== -->
        <!-- IMAGEN DESTACADA -->
        <!-- ===================================== -->

        <div class="article-form-card">

            <div class="article-form-section-header">

                <div class="article-form-section-number">
                    03
                </div>

                <div>
                    <h3>
                        Imagen destacada
                    </h3>

                    <p>
                        Añade o reemplaza la imagen de portada del artículo.
                    </p>
                </div>

            </div>


            <div class="admin-form-group">

                <label for="imagen">
                    Imagen destacada
                </label>


                <?php if (!empty($article['imagen'])): ?>

                    <div class="current-featured-image">

                        <p>
                            Imagen actual
                        </p>

                        <img
                            src="/incuyo/cyberblog/public/uploads/articles/<?= htmlspecialchars(
                                $article['imagen'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            alt="Imagen actual del artículo"
                            class="article-current-image"
                        >

                    </div>


                    <label
                        class="delete-featured-image-option"
                        for="eliminar_imagen"
                    >

                        <input
                            type="checkbox"
                            id="eliminar_imagen"
                            name="eliminar_imagen"
                            value="1"
                        >

                        <span>
                            Eliminar imagen destacada actual
                        </span>

                    </label>

                <?php endif; ?>


                <div class="article-image-upload">

                    <label
                        class="article-image-dropzone"
                        for="imagen"
                    >

                        <span class="article-image-upload-icon">
                            ↑
                        </span>

                        <strong>
                            <?= !empty($article['imagen'])
                                ? 'Selecciona una nueva imagen'
                                : 'Selecciona una imagen'
                            ?>
                        </strong>

                        <span>
                            JPG, PNG o WEBP · Máximo 5 MB
                        </span>

                        <span class="article-image-upload-button">
                            Seleccionar imagen
                        </span>

                    </label>


                    <input
                        type="file"
                        id="imagen"
                        name="imagen"
                        class="article-image-input"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    >

                    <div
                        id="selected-image-info"
                        class="selected-image-info"
                        hidden
                    >

                        <span
                            id="selected-image-name"
                            class="selected-image-name"
                        ></span>

                        <button
                            type="button"
                            id="remove-selected-image"
                            class="admin-button admin-button-secondary"
                        >
                            ✕ Quitar imagen seleccionada
                        </button>

                    </div>

                </div>


                <small>

                    <?php if (!empty($article['imagen'])): ?>

                        Si seleccionas una nueva imagen,
                        reemplazará automáticamente la actual.

                    <?php else: ?>

                        Puedes seleccionar una imagen destacada
                        para el artículo.

                    <?php endif; ?>

                    <br><br>

                    Formatos permitidos:
                    JPG, PNG y WEBP.

                    <br>

                    Tamaño máximo:
                    5 MB.

                </small>

            </div>

        </div>


        <!-- ===================================== -->
        <!-- PUBLICACIÓN -->
        <!-- ===================================== -->

        <div class="article-form-card">

            <div class="article-form-section-header">

                <div class="article-form-section-number">
                    04
                </div>

                <div>
                    <h3>
                        Publicación
                    </h3>

                    <p>
                        Decide si el artículo quedará como borrador
                        o será visible para los visitantes.
                    </p>
                </div>

            </div>


            <div class="admin-form-group">

                <label for="estado">
                    Estado
                </label>


                <select
                    id="estado"
                    name="estado"
                >

                    <option
                        value="borrador"
                        <?= (
                            ($article['estado'] ?? '') === 'borrador'
                        )
                            ? 'selected'
                            : ''
                        ?>
                    >
                        📝 Guardar como borrador
                    </option>


                    <option
                        value="publicado"
                        <?= (
                            ($article['estado'] ?? '') === 'publicado'
                        )
                            ? 'selected'
                            : ''
                        ?>
                    >
                        🌐 Publicar artículo
                    </option>

                </select>


                <small>
                    Los artículos publicados serán visibles
                    para los visitantes del blog.
                </small>

            </div>

        </div>


        <!-- ===================================== -->
        <!-- BOTONES -->
        <!-- ===================================== -->

        <div class="admin-form-actions">

            <a
                href="/incuyo/cyberblog/public/admin/articles"
                class="admin-button admin-button-secondary"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="admin-button"
            >
                Guardar cambios
            </button>

        </div>


    </form>


</section>


<script
    src="/incuyo/cyberblog/public/assets/js/editor.js"
></script>

<script>

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            const imageInput =
                document.getElementById('imagen');

            const selectedImageInfo =
                document.getElementById('selected-image-info');

            const selectedImageName =
                document.getElementById('selected-image-name');

            const removeSelectedImage =
                document.getElementById('remove-selected-image');

            const deleteCurrentImage =
                document.getElementById('eliminar_imagen');


            if (!imageInput || !selectedImageInfo ||
                !selectedImageName || !removeSelectedImage) {
                return;
            }


            imageInput.addEventListener(
                'change',
                function () {

                    if (!imageInput.files || imageInput.files.length === 0) {

                        selectedImageInfo.hidden = true;
                        selectedImageName.textContent = '';

                        return;
                    }


                    const file = imageInput.files[0];

                    selectedImageName.textContent =
                        'Imagen seleccionada: ' + file.name;

                    selectedImageInfo.hidden = false;


                    /*
                     * Si se selecciona una nueva imagen,
                     * no tiene sentido mantener marcada
                     * la eliminación de la imagen actual.
                     * La nueva imagen la reemplazará.
                     */
                    if (deleteCurrentImage) {
                        deleteCurrentImage.checked = false;
                    }
                }
            );


            removeSelectedImage.addEventListener(
                'click',
                function () {

                    imageInput.value = '';

                    selectedImageInfo.hidden = true;
                    selectedImageName.textContent = '';

                }
            );

        }
    );

</script>