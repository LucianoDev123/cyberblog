<?php

declare(strict_types=1);

?>

<div class="articles-admin-page">

    <!-- =====================================================
         ENCABEZADO
         ===================================================== -->

    <div class="articles-page-header">

        <div>

            <span class="articles-section-label">
                CONTENIDO
            </span>

            <h2>
                Administración de Artículos
            </h2>

            <p>
                Gestioná los artículos publicados y
                borradores de CyberBlog.
            </p>

        </div>

        <a
            href="/incuyo/cyberblog/public/admin/articles/create"
            class="admin-button articles-create-button"
        >
            <span aria-hidden="true">
                +
            </span>

            Nuevo artículo
        </a>

    </div>


    <!-- =====================================================
         TABLA DE ARTÍCULOS
         ===================================================== -->

    <div class="articles-table-wrapper">

        <table class="articles-table">

            <thead>

                <tr>

                    <th>
                        ID
                    </th>

                    <th>
                        Título
                    </th>

                    <th>
                        Autor
                    </th>

                    <th>
                        Categoría
                    </th>

                    <th>
                        Estado
                    </th>

                    <th>
                        Acciones
                    </th>

                </tr>

            </thead>

            <tbody>

                <?php if (empty($articles)): ?>

                    <tr>

                        <td
                            colspan="6"
                            class="articles-empty"
                        >
                            No hay artículos registrados.
                        </td>

                    </tr>

                <?php else: ?>

                    <?php foreach ($articles as $article): ?>

                        <tr>

                            <!-- ID -->

                            <td class="articles-id">

                                <?= (int) $article['id'] ?>

                            </td>


                            <!-- TÍTULO -->

                            <td>

                                <span class="articles-title">

                                    <?= htmlspecialchars(
                                        $article['titulo'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            </td>


                            <!-- AUTOR -->

                            <td>

                                <?= htmlspecialchars(
                                    $article['autor'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </td>


                            <!-- CATEGORÍA -->

                            <td>

                                <?= htmlspecialchars(
                                    $article['categoria'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>

                            </td>


                            <!-- ESTADO -->

                            <td>

                                <?php

                                $estado = strtolower(
                                    (string) $article['estado']
                                );

                                ?>

                                <span
                                    class="articles-status articles-status-<?= htmlspecialchars(
                                        $estado,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                    <?= htmlspecialchars(
                                        $article['estado'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </span>

                            </td>


                            <!-- ACCIONES -->

                            <td>

                                <div class="articles-table-actions">

                                    <!-- EDITAR -->

                                    <a
                                        href="/incuyo/cyberblog/public/admin/articles/edit/<?= (int) $article['id'] ?>"
                                        class="articles-action-edit"
                                    >
                                        Editar
                                    </a>


                                    <?php if (
                                        $_SESSION['usuario_rol'] === 'admin'
                                    ): ?>

                                        <!-- ELIMINAR -->

                                        <form
                                            action="/incuyo/cyberblog/public/admin/articles/delete/<?= (int) $article['id'] ?>"
                                            method="POST"
                                            onsubmit="return confirm('¿Estás seguro de que quieres eliminar este artículo?');"
                                        >

                                            <!--
                                                Token CSRF.
                                            -->

                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= htmlspecialchars(
                                                    $csrfToken,
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="articles-action-delete"
                                            >
                                                Eliminar
                                            </button>

                                        </form>

                                    <?php endif; ?>

                                </div>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php endif; ?>

            </tbody>

        </table>

    </div>


    <!-- =====================================================
         PAGINACIÓN
         ===================================================== -->

    <?php if (
        isset($pagination) &&
        $pagination->getTotalPages() > 1
    ): ?>

        <nav
            class="admin-pagination"
            aria-label="Paginación de artículos"
        >

            <div class="admin-pagination-info">

                Página
                <?= $pagination->getCurrentPage() ?>
                de
                <?= $pagination->getTotalPages() ?>

            </div>


            <div class="admin-pagination-links">

                <?php if (
                    $pagination->hasPreviousPage()
                ): ?>

                    <a
                        href="/incuyo/cyberblog/public/admin/articles?page=<?= $pagination->getPreviousPage() ?>"
                        class="admin-button admin-pagination-link"
                    >
                        ← Anterior
                    </a>

                <?php endif; ?>


                <?php foreach (
                    $pagination->getPages()
                    as $page
                ): ?>

                    <?php if (
                        $page ===
                        $pagination->getCurrentPage()
                    ): ?>

                        <span
                            class="admin-button admin-pagination-link admin-pagination-current"
                            aria-current="page"
                        >
                            <?= $page ?>
                        </span>

                    <?php else: ?>

                        <a
                            href="/incuyo/cyberblog/public/admin/articles?page=<?= $page ?>"
                            class="admin-button admin-pagination-link"
                        >
                            <?= $page ?>
                        </a>

                    <?php endif; ?>

                <?php endforeach; ?>


                <?php if (
                    $pagination->hasNextPage()
                ): ?>

                    <a
                        href="/incuyo/cyberblog/public/admin/articles?page=<?= $pagination->getNextPage() ?>"
                        class="admin-button admin-pagination-link"
                    >
                        Siguiente →
                    </a>

                <?php endif; ?>

            </div>

        </nav>

    <?php endif; ?>

</div>