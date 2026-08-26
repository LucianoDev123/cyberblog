<?php

declare(strict_types=1);

?>

<section class="admin-page">

    <!-- ========================================= -->
    <!-- ENCABEZADO DE LA PÁGINA -->
    <!-- ========================================= -->

    <div class="admin-page-header">

        <div>

            <p class="section-label">
                ADMINISTRACIÓN
            </p>

            <h1>
                Series de artículos
            </h1>

            <p>
                Gestiona colecciones de artículos relacionados
                y organiza el contenido en recorridos temáticos.
            </p>

        </div>

    </div>


    <!-- ========================================= -->
    <!-- ACCIONES -->
    <!-- ========================================= -->

    <div class="series-actions">

        <a
            href="/incuyo/cyberblog/public/admin/series/create"
            class="admin-button admin-button-primary"
        >

            <span aria-hidden="true">
                +
            </span>

            Nueva serie

        </a>

    </div>


    <!-- ========================================= -->
    <!-- LISTADO DE SERIES -->
    <!-- ========================================= -->

    <div class="admin-table-wrapper">

        <?php if (!empty($series)): ?>

            <table class="admin-table">

                <thead>

                    <tr>

                        <th>
                            Serie
                        </th>

                        <th>
                            Estado
                        </th>

                        <th>
                            Artículos
                        </th>

                        <th>
                            Creada
                        </th>

                        <th>
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php foreach ($series as $serie): ?>

                        <tr>

                            <td>

                                <div class="admin-table-title">

                                    <strong>

                                        <?= htmlspecialchars(
                                            $serie['titulo']
                                        ) ?>

                                    </strong>


                                    <span>

                                        /<?= htmlspecialchars(
                                            $serie['slug']
                                        ) ?>

                                    </span>

                                </div>

                            </td>


                            <td>

                                <?php if (
                                    $serie['estado'] === 'publicada'
                                ): ?>

                                    <span class="status status-published">
                                        Publicada
                                    </span>

                                <?php else: ?>

                                    <span class="status status-draft">
                                        Borrador
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <span class="series-article-count">

                                    <?= (int) (
                                        $serie['total_articulos'] ?? 0
                                    ) ?>

                                </span>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    date(
                                        'd/m/Y',
                                        strtotime(
                                            $serie['created_at']
                                        )
                                    )
                                ) ?>

                            </td>


                            <td>

                                <div class="admin-table-actions">

                                    <a
                                        href="/incuyo/cyberblog/public/admin/series/edit/<?= (int) $serie['id'] ?>"
                                        class="admin-action-link"
                                    >
                                        Editar
                                    </a>


                                    <form
                                        action="/incuyo/cyberblog/public/admin/series/delete/<?= (int) $serie['id'] ?>"
                                        method="POST"
                                        onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta serie? Los artículos no serán eliminados.')"
                                    >

                                        <button
                                            type="submit"
                                            class="admin-action-link admin-action-danger"
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

        <?php else: ?>

            <div class="admin-empty-state">

                <span
                    class="admin-empty-icon"
                    aria-hidden="true"
                >
                    >_
                </span>


                <h2>
                    Todavía no hay series
                </h2>


                <p>
                    Crea una serie para comenzar a agrupar
                    artículos relacionados dentro de CyberBlog.
                </p>


                <a
                    href="/incuyo/cyberblog/public/admin/series/create"
                    class="admin-button admin-button-primary"
                >
                    Crear primera serie
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>