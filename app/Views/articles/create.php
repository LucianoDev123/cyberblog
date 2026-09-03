<?php

declare(strict_types=1);

?>

<section class="article-create-page">

    <!-- ========================================= -->
    <!-- ENCABEZADO -->
    <!-- ========================================= -->

    <header class="article-create-header">

        <div class="article-create-header-content">

            <span class="article-create-eyebrow">
                ADMINISTRACIÓN
            </span>

            <h2>
                Nuevo artículo
            </h2>

            <p>
                Crea un nuevo artículo y organízalo
                dentro de una categoría o una serie temática.
            </p>

        </div>

        <a
            href="/incuyo/cyberblog/public/admin/articles"
            class="article-create-back"
        >
            <span aria-hidden="true">
                ←
            </span>

            Volver a artículos
        </a>

    </header>


    <!-- ========================================= -->
    <!-- FORMULARIO -->
    <!-- ========================================= -->

    <form
        action="/incuyo/cyberblog/public/admin/articles"
        method="POST"
        enctype="multipart/form-data"
        class="article-create-form"
    >

        <!-- ========================================= -->
        <!-- TOKEN CSRF -->
        <!-- ========================================= -->

        <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $csrfToken ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >


        <!-- ========================================= -->
        <!-- INFORMACIÓN BÁSICA -->
        <!-- ========================================= -->

        <section class="article-form-section">

            <div class="article-form-section-header">

                <span class="article-form-section-number">
                    01
                </span>

                <div>

                    <h3>
                        Información del artículo
                    </h3>

                    <p>
                        Define los datos principales del contenido.
                    </p>

                </div>

            </div>


            <!-- TÍTULO -->

            <div class="article-form-group">

                <label for="titulo">
                    Título del artículo
                </label>

                <input
                    type="text"
                    id="titulo"
                    name="titulo"
                    required
                    maxlength="255"
                    placeholder="Ejemplo: Instalación y configuración de Wazuh"
                >

                <small>
                    El título se utilizará para generar
                    automáticamente la URL del artículo.
                </small>

            </div>


            <!-- CATEGORÍA -->

            <div class="article-form-group">

                <label for="categoria_id">
                    Categoría
                </label>

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
                                $category['nombre'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <small>
                    Selecciona la categoría principal
                    del artículo.
                </small>

            </div>


            <!-- SERIE -->

            <div class="article-form-group">

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
                        >

                            <?= htmlspecialchars(
                                $serie['titulo'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>

                            <?php if (
                                ($serie['estado'] ?? '') === 'borrador'
                            ): ?>

                                — Borrador

                            <?php endif; ?>

                        </option>

                    <?php endforeach; ?>

                </select>

                <small>
                    Opcional. Puedes asociar este artículo
                    a una colección de artículos relacionados.
                </small>

            </div>


            <!-- RESUMEN -->

            <div class="article-form-group">

                <label for="resumen">
                    Resumen
                </label>

                <textarea
                    id="resumen"
                    name="resumen"
                    rows="5"
                    required
                    placeholder="Escribe un resumen breve del artículo..."
                ></textarea>

                <small>
                    El resumen se mostrará en las tarjetas
                    y vistas previas del artículo.
                </small>

            </div>

        </section>


        <!-- ========================================= -->
        <!-- CONTENIDO -->
        <!-- ========================================= -->

        <section class="article-form-section">

            <div class="article-form-section-header">

                <span class="article-form-section-number">
                    02
                </span>

                <div>

                    <h3>
                        Contenido
                    </h3>

                    <p>
                        Escribe y da formato al contenido del artículo.
                    </p>

                </div>

            </div>


            <div class="article-form-group">

                <label for="editor">
                    Contenido
                </label>


                <!-- BARRA DE HERRAMIENTAS -->

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


                    <!-- FORMATO -->

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
                        ×
                    </button>

                </div>


                <!-- EDITOR -->

                <div
                    id="editor"
                    class="article-editor"
                    contenteditable="true"
                    data-placeholder="Escribe el contenido del artículo aquí. Puedes pegar imágenes directamente con Ctrl + V."
                ></div>


                <!-- CONTENIDO REAL -->

                <textarea
                    id="contenido"
                    name="contenido"
                    hidden
                ></textarea>


                <small class="article-editor-help">
                    💡 Puedes utilizar las herramientas
                    de formato y pegar imágenes directamente
                    dentro del editor.
                </small>

            </div>

        </section>


        <!-- ========================================= -->
        <!-- IMAGEN DESTACADA -->
        <!-- ========================================= -->

        <section class="article-form-section">

            <div class="article-form-section-header">

                <span class="article-form-section-number">
                    03
                </span>

                <div>

                    <h3>
                        Imagen destacada
                    </h3>

                    <p>
                        Añade una imagen de portada para representar
                        visualmente el artículo.
                    </p>

                </div>

            </div>


            <div class="article-form-group">

                <div class="article-image-upload">

                    <div
                        id="articleImageEmpty"
                        class="article-image-empty"
                    >

                        <div class="article-image-icon">
                            ↑
                        </div>

                        <strong>
                            Selecciona una imagen
                        </strong>

                        <span>
                            JPG, PNG o WEBP · Máximo 5 MB
                        </span>

                        <label
                            for="imagen"
                            class="article-image-select"
                        >
                            Seleccionar imagen
                        </label>

                    </div>


                    <div
                        id="articleImagePreview"
                        class="article-image-preview"
                        hidden
                    >

                        <div class="article-image-preview-media">

                            <img
                                id="articleImagePreviewImg"
                                src=""
                                alt="Vista previa de la imagen destacada"
                            >

                        </div>

                        <div class="article-image-preview-info">

                            <div>

                                <strong id="articleImageFileName">
                                    Imagen seleccionada
                                </strong>

                                <span>
                                    Imagen destacada del artículo
                                </span>

                            </div>

                            <button
                                type="button"
                                id="removeArticleImage"
                                class="article-image-remove"
                            >
                                Quitar imagen
                            </button>

                        </div>

                    </div>


                    <input
                        type="file"
                        id="imagen"
                        name="imagen"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    >

                </div>


                <small>
                    Opcional. Puedes publicar el artículo
                    sin una imagen destacada.
                </small>

            </div>

        </section>


        <!-- ========================================= -->
        <!-- PUBLICACIÓN -->
        <!-- ========================================= -->

        <section class="article-form-section">

            <div class="article-form-section-header">

                <span class="article-form-section-number">
                    04
                </span>

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


            <div class="article-form-group">

                <label for="estado">
                    Estado
                </label>

                <select
                    id="estado"
                    name="estado"
                >

                    <option value="borrador">
                        Guardar como borrador
                    </option>

                    <option value="publicado">
                        Publicar artículo
                    </option>

                </select>

                <small>
                    Los artículos publicados serán visibles
                    para los visitantes del blog.
                </small>

            </div>

        </section>


        <!-- ========================================= -->
        <!-- ACCIONES -->
        <!-- ========================================= -->

        <div class="article-form-actions">

            <a
                href="/incuyo/cyberblog/public/admin/articles"
                class="article-form-cancel"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="article-form-submit"
            >
                Guardar artículo
            </button>

        </div>

    </form>

</section>


<!-- ========================================= -->
<!-- ESTILOS ESPECÍFICOS DE ESTA VISTA -->
<!-- ========================================= -->

<link
    rel="stylesheet"
    href="/incuyo/cyberblog/public/assets/css/article-create.css"
>


<!-- ========================================= -->
<!-- LÓGICA DE IMAGEN DESTACADA -->
<!-- ========================================= -->

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const imageInput =
            document.getElementById('imagen');

        const imageEmpty =
            document.getElementById('articleImageEmpty');

        const imagePreview =
            document.getElementById('articleImagePreview');

        const imagePreviewImg =
            document.getElementById('articleImagePreviewImg');

        const imageFileName =
            document.getElementById('articleImageFileName');

        const removeImageButton =
            document.getElementById('removeArticleImage');


        if (
            !imageInput ||
            !imageEmpty ||
            !imagePreview ||
            !imagePreviewImg ||
            !imageFileName ||
            !removeImageButton
        ) {
            return;
        }


        imageInput.addEventListener(
            'change',
            function () {

                const file =
                    this.files[0];

                if (!file) {
                    return;
                }


                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];


                if (
                    !allowedTypes.includes(file.type)
                ) {

                    this.value = '';

                    alert(
                        'Formato de imagen no permitido. Solo se permiten JPG, PNG y WEBP.'
                    );

                    return;
                }


                if (
                    file.size > 5 * 1024 * 1024
                ) {

                    this.value = '';

                    alert(
                        'La imagen no puede superar los 5 MB.'
                    );

                    return;
                }


                const reader =
                    new FileReader();


                reader.onload =
                    function (event) {

                        imagePreviewImg.src =
                            event.target.result;

                        imageFileName.textContent =
                            file.name;

                        imageEmpty.hidden =
                            true;

                        imagePreview.hidden =
                            false;
                    };


                reader.readAsDataURL(file);
            }
        );


        removeImageButton.addEventListener(
            'click',
            function () {

                imageInput.value = '';

                imagePreviewImg.src = '';

                imageFileName.textContent =
                    'Imagen seleccionada';

                imagePreview.hidden =
                    true;

                imageEmpty.hidden =
                    false;
            }
        );

    }
);

</script>