<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Vista de administración de categorías
|--------------------------------------------------------------------------
|
| Esta vista se encarga únicamente de presentar las categorías.
| La obtención de datos, validaciones y eliminación pertenecen
| al controlador y al modelo.
|
| Variables esperadas:
|
| $categories → listado de categorías.
| $csrfToken  → token CSRF para formularios POST.
|
*/

?>

<section class="categories-admin-page">

    <!-- =========================================================
         ENCABEZADO DE LA PÁGINA
         ========================================================= -->

    <div class="categories-page-header">

        <div class="categories-page-heading">

            <span class="categories-section-label">
                Administración
            </span>

            <h2 class="categories-page-title">
                Categorías
            </h2>

            <p class="categories-page-description">
                Administra las categorías utilizadas por los artículos
                del blog.
            </p>

        </div>

        <a
            href="/incuyo/cyberblog/public/admin/categories/create"
            class="categories-primary-button"
        >
            <span aria-hidden="true">+</span>
            Nueva categoría
        </a>

    </div>


    <?php if (empty($categories)): ?>

        <!-- =====================================================
             ESTADO VACÍO
             ===================================================== -->

        <div class="categories-empty-state">

            <div
                class="categories-empty-icon"
                aria-hidden="true"
            >
                #
            </div>

            <h3 class="categories-empty-title">
                No hay categorías
            </h3>

            <p class="categories-empty-description">
                Todavía no se ha creado ninguna categoría.
            </p>

            <a
                href="/incuyo/cyberblog/public/admin/categories/create"
                class="categories-primary-button"
            >
                Crear primera categoría
            </a>

        </div>

    <?php else: ?>

        <!-- =====================================================
             RESUMEN
             ===================================================== -->

        <div class="categories-summary-card">

            <div class="categories-summary-icon" aria-hidden="true">
                #
            </div>

            <div class="categories-summary-content">

                <span class="categories-summary-label">
                    Categorías registradas
                </span>

                <strong class="categories-summary-value">
                    <?= (int) count($categories) ?>
                </strong>

            </div>

        </div>


        <!-- =====================================================
             TABLA DE CATEGORÍAS
             ===================================================== -->

        <div class="categories-table-card">

            <div class="categories-table-header">

                <div>

                    <h3 class="categories-table-title">
                        Listado de categorías
                    </h3>

                    <p class="categories-table-description">
                        Gestioná las categorías asociadas a tus artículos.
                    </p>

                </div>

            </div>


            <div class="categories-table-wrapper">

                <table class="categories-table">

                    <thead>

                        <tr>

                            <th scope="col">
                                Nombre
                            </th>

                            <th scope="col">
                                Slug
                            </th>

                            <th scope="col">
                                Artículos
                            </th>

                            <th scope="col">
                                Descripción
                            </th>

                            <th scope="col">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($categories as $category): ?>

                            <tr>

                                <!-- Nombre -->

                                <td>

                                    <div class="categories-name-cell">

                                        <span class="categories-name-indicator"></span>

                                        <strong class="categories-name">
                                            <?= htmlspecialchars(
                                                (string) $category['nombre'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </strong>

                                    </div>

                                </td>


                                <!-- Slug -->

                                <td>

                                    <code class="categories-slug">
                                        <?= htmlspecialchars(
                                            (string) $category['slug'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </code>

                                </td>


                                <!-- Cantidad de artículos -->

                                <td>

                                    <span class="categories-article-count">

                                        <?= (int) $category['total_articulos'] ?>

                                        <span class="categories-article-count-label">
                                            <?= (int) $category['total_articulos'] === 1
                                                ? 'artículo'
                                                : 'artículos' ?>
                                        </span>

                                    </span>

                                </td>


                                <!-- Descripción -->

                                <td>

                                    <?php if (!empty($category['descripcion'])): ?>

                                        <span class="categories-description">

                                            <?= htmlspecialchars(
                                                (string) $category['descripcion'],
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>

                                        </span>

                                    <?php else: ?>

                                        <span class="categories-no-description">
                                            Sin descripción
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- Acciones -->

                                <td>

                                    <div class="categories-actions">

                                        <a
                                            href="/incuyo/cyberblog/public/admin/categories/edit/<?= (int) $category['id'] ?>"
                                            class="categories-edit-button"
                                        >
                                            Editar
                                        </a>


                                        <form
                                            method="POST"
                                            action="/incuyo/cyberblog/public/admin/categories/delete/<?= (int) $category['id'] ?>"
                                            class="categories-delete-form"
                                            onsubmit="return confirm(
                                                '¿Seguro que deseas eliminar esta categoría?'
                                            );"
                                        >

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= htmlspecialchars(
                                                    (string) $csrfToken,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="categories-delete-button"
                                            >
                                                Eliminar
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>

</section>