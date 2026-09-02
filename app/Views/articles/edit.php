<?php

declare(strict_types=1);

?>

<section class="article-page">


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


        <!-- ===================================== -->
        <!-- CONTENIDO -->
        <!-- ===================================== -->

        <div class="admin-form-group">

            <label for="contenido-editor">

                Contenido

            </label>


            <!-- EDITOR VISUAL -->

            <div
                id="contenido-editor"
                class="content-editor"
                contenteditable="true"
                data-placeholder="Escribe el contenido del artículo aquí. Puedes pegar texto e imágenes directamente con Ctrl + V."
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

                💡 Puedes editar el texto existente y pegar
                nuevas imágenes directamente dentro del editor.

                Las imágenes permanecerán en el orden
                exacto en el que las insertes.

            </small>

        </div>


        <!-- ===================================== -->
        <!-- IMAGEN DESTACADA -->
        <!-- ===================================== -->

        <div class="admin-form-group">

            <label for="imagen">

                Imagen destacada

            </label>


            <?php if (
                !empty($article['imagen'])
            ): ?>


                <!-- ================================= -->
                <!-- IMAGEN ACTUAL -->
                <!-- ================================= -->

                <div class="current-featured-image">

                    <p>

                        Imagen actual:

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


                <!-- ================================= -->
                <!-- ELIMINAR IMAGEN -->
                <!-- ================================= -->

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


            <?php else: ?>


                <p>

                    <small>

                        Este artículo actualmente no tiene
                        una imagen destacada.

                    </small>

                </p>


            <?php endif; ?>


            <!-- ================================= -->
            <!-- NUEVA IMAGEN -->
            <!-- ================================= -->

            <input
                type="file"
                id="imagen"
                name="imagen"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            >


            <small>

                <?php if (
                    !empty($article['imagen'])
                ): ?>

                    Si seleccionas una nueva imagen,
                    reemplazará automáticamente la actual.

                    <br><br>

                    Si marcas la opción de eliminar,
                    la imagen actual será eliminada.

                    <?php else: ?>

                    Puedes seleccionar una imagen
                    destacada para el artículo.

                <?php endif; ?>


                <br><br>


                Formatos permitidos:
                JPG, PNG y WEBP.

                Tamaño máximo:
                5 MB.

            </small>

        </div>


        <!-- ===================================== -->
        <!-- ESTADO -->
        <!-- ===================================== -->

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

                Los artículos en borrador no serán visibles
                públicamente.

            </small>

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