<?php

declare(strict_types=1);

?>

<section class="admin-section">

    <div
        style="
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        "
    >

        <div>

            <h2
                style="
                    margin: 0 0 6px;
                "
            >
                Categorías
            </h2>

            <p
                style="
                    margin: 0;
                    color: #6b7280;
                "
            >
                Administra las categorías utilizadas
                por los artículos del blog.
            </p>

        </div>


        <a
            href="/incuyo/cyberblog/public/admin/categories/create"
            class="admin-button"
        >
            + Nueva categoría
        </a>

    </div>


    <?php if (empty($categories)): ?>

        <div
            style="
                padding: 40px;
                background: #ffffff;
                border: 1px solid var(--admin-border);
                border-radius: 10px;
                text-align: center;
            "
        >

            <h3>
                No hay categorías
            </h3>

            <p
                style="
                    color: var(--admin-text-muted);
                "
            >
                Todavía no se ha creado ninguna categoría.
            </p>

            <a
                href="/incuyo/cyberblog/public/admin/categories/create"
                class="admin-button"
            >
                Crear primera categoría
            </a>

        </div>

    <?php else: ?>

        <div
            style="
                overflow-x: auto;
            "
        >

            <table>

                <thead>

                    <tr>

                        <th>
                            Nombre
                        </th>

                        <th>
                            Slug
                        </th>

                        <th>
                            Artículos
                        </th>

                        <th>
                            Descripción
                        </th>

                        <th>
                            Acciones
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php foreach (
                        $categories
                        as $category
                    ): ?>

                        <tr>

                            <td>

                                <strong>
                                    <?= htmlspecialchars(
                                        $category['nombre'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </strong>

                            </td>


                            <td>

                                <code>
                                    <?= htmlspecialchars(
                                        $category['slug'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </code>

                            </td>


                            <td>

                                <?= (int) $category[
                                    'total_articulos'
                                ] ?>

                            </td>


                            <td>

                                <?php if (
                                    !empty(
                                        $category['descripcion']
                                    )
                                ): ?>

                                    <?= htmlspecialchars(
                                        $category['descripcion'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                <?php else: ?>

                                    <span
                                        style="
                                            color: var(--admin-text-muted);
                                        "
                                    >
                                        Sin descripción
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <div
                                    style="
                                        display: flex;
                                        flex-wrap: wrap;
                                        gap: 8px;
                                    "
                                >

                                    <a
                                        href="/incuyo/cyberblog/public/admin/categories/edit/<?= (int) $category['id'] ?>"
                                        class="admin-button"
                                    >
                                        Editar
                                    </a>


                                    <form
                                        method="POST"
                                        action="/incuyo/cyberblog/public/admin/categories/delete/<?= (int) $category['id'] ?>"
                                        style="
                                            margin: 0;
                                        "
                                        onsubmit="return confirm(
                                            '¿Seguro que deseas eliminar esta categoría?'
                                        );"
                                    >

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

    <?php endif; ?>

</section>