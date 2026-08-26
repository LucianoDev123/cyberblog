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

                Nuevo artículo

            </h2>


            <p>

                Crea un nuevo artículo y organízalo
                dentro de una categoría o una serie temática.

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
        action="/incuyo/cyberblog/public/admin/articles"
        method="POST"
        enctype="multipart/form-data"
        class="series-form article-form"
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
                required
                maxlength="255"
                placeholder="Ejemplo: Instalación y configuración de Wazuh"
            >


            <small>

                El título se utilizará para generar
                automáticamente la URL del artículo.

            </small>

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
                placeholder="Escribe un resumen breve del artículo..."
            ></textarea>


            <small>

                El resumen se mostrará en las tarjetas
                y vistas previas del artículo.

            </small>

        </div>


        <!-- ========================================= -->
        <!-- CONTENIDO -->
        <!-- ========================================= -->

        <div class="admin-form-group">

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

                    ✖

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


            <small>

                💡 Puedes utilizar las herramientas
                de formato y pegar imágenes directamente
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


            <input
                type="file"
                id="imagen"
                name="imagen"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
            >


            <small>

                Opcional. Formatos permitidos:
                JPG, PNG y WEBP. Tamaño máximo: 5 MB.

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

                <option value="borrador">

                    📝 Guardar como borrador

                </option>


                <option value="publicado">

                    🌐 Publicar artículo

                </option>

            </select>


            <small>

                Los artículos publicados serán visibles
                para los visitantes del blog.

            </small>

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

                Guardar artículo

            </button>


        </div>


    </form>


</section>