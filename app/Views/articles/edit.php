<?php

declare(strict_types=1);

?>

<section class="series-page">


    <!-- ========================================= -->
    <!-- ENCABEZADO -->
    <!-- ========================================= -->

    <div class="series-page-header">

        <div>

            <span class="series-eyebrow">

                ADMINISTRACIÓN

            </span>


            <h2>

                Editar artículo

            </h2>


            <p>

                Modifica el contenido, organización
                y configuración del artículo.

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
        class="series-form article-form"
    >


        <!-- TOKEN CSRF -->

        <input
            type="hidden"
            id="csrf_token"
            name="csrf_token"
            value="<?= htmlspecialchars(
                $csrfToken ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?>"
        >


        <!-- ========================================= -->
        <!-- TÍTULO -->
        <!-- ========================================= -->

        <div class="admin-form-group">

            <label for="titulo">

                Título del artículo

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
                maxlength="255"
            >

        </div>


        <!-- ========================================= -->
        <!-- CATEGORÍA -->
        <!-- ========================================= -->

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
                            (int) $category['id']
                            ===
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


        <!-- ========================================= -->
        <!-- SERIE -->
        <!-- ========================================= -->

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
                            !empty($article['serie_id'])
                            &&
                            (int) $serie['id']
                            ===
                            (int) $article['serie_id']
                        ): ?>

                            selected

                        <?php endif; ?>

                    >

                        <?= htmlspecialchars(
                            $serie['titulo'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                        <?php if (
                            ($serie['estado'] ?? '')
                            === 'borrador'
                        ): ?>

                            — Borrador

                        <?php endif; ?>

                    </option>

                <?php endforeach; ?>

            </select>


            <small>

                Puedes mover el artículo a otra serie
                o dejarlo sin una serie asociada.

            </small>

        </div>


        <!-- ========================================= -->
        <!-- RESUMEN -->
        <!-- ========================================= -->

        <div class="admin-form-group">

            <label for="resumen">

                Resumen

            </label>


            <textarea
                id="resumen"
                name="resumen"
                rows="5"
                required
            ><?= htmlspecialchars(
                $article['resumen'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?></textarea>


            <small>

                Este texto se utiliza en las tarjetas
                y vistas previas del artículo.

            </small>

        </div>


        <!-- ========================================= -->
        <!-- CONTENIDO -->
        <!-- ========================================= -->

        <div class="admin-form-group">

            <label for="contenido-editor">

                Contenido

            </label>


            <div
                id="contenido-editor"
                class="content-editor"
                contenteditable="true"
                data-placeholder="Escribe el contenido del artículo aquí. Puedes pegar texto e imágenes directamente con Ctrl + V."
            ><?= $article['contenido'] ?? '' ?></div>


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

                💡 Puedes editar el contenido existente
                y pegar nuevas imágenes directamente
                dentro del editor.

            </small>

        </div>


        <!-- ========================================= -->
        <!-- IMAGEN DESTACADA -->
        <!-- ========================================= -->

        <div class="admin-form-group">

            <label for="imagen">

                Imagen destacada

            </label>


            <?php if (
                !empty($article['imagen'])
            ): ?>

                <div class="article-current-image">

                    <img
                        src="/incuyo/cyberblog/public/uploads/articles/<?= htmlspecialchars(
                            $article['imagen'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        alt="Imagen actual del artículo"
                    >

                </div>


                <small>

                    Si no seleccionas una nueva imagen,
                    se conservará la imagen actual.

                </small>

            <?php else: ?>

                <small>

                    Este artículo todavía no tiene
                    una imagen destacada.

                </small>

            <?php endif; ?>


            <input
                type="file"
                id="imagen"
                name="imagen"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            >


            <small>

                Formatos permitidos: JPG, PNG y WEBP.
                Tamaño máximo: 5 MB.

            </small>

        </div>


        <!-- ========================================= -->
        <!-- ESTADO -->
        <!-- ========================================= -->

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
                        ($article['estado'] ?? '')
                        === 'borrador'
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
                        ($article['estado'] ?? '')
                        === 'publicado'
                    )
                        ? 'selected'
                        : ''
                    ?>
                >

                    🌐 Publicar artículo

                </option>

            </select>

        </div>


        <!-- ========================================= -->
        <!-- ACCIONES -->
        <!-- ========================================= -->

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