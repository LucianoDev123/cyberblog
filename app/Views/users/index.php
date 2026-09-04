<?php

declare(strict_types=1);

/*
 * Normalizamos los valores recibidos desde el controlador.
 *
 * Esto evita errores si alguna variable no fue enviada
 * o si el listado todavía no contiene usuarios.
 */
$users = $users ?? [];
$pagination = $pagination ?? null;

/*
 * La clase Pagination ya dispone de getTotalItems().
 * Si no existe una instancia de paginación, utilizamos
 * la cantidad de usuarios recibidos como alternativa.
 */
$totalUsers = $pagination
    ? $pagination->getTotalItems()
    : count($users);

$currentPage = $pagination
    ? $pagination->getCurrentPage()
    : 1;

$totalPages = $pagination
    ? $pagination->getTotalPages()
    : 1;
?>

<div class="users-admin-page">

    <!-- Encabezado principal de la sección de usuarios. -->
    <div class="users-page-header">

        <div class="users-page-heading">

            <span class="users-page-eyebrow">
                Administración
            </span>

            <h1 class="users-page-title">
                Usuarios
            </h1>

            <p class="users-page-description">
                Administrá las cuentas, permisos y estados de los usuarios
                registrados en CyberBlog.
            </p>

        </div>

        <!-- Acción principal para crear un nuevo usuario. -->
        <a
            href="/incuyo/cyberblog/public/admin/users/create"
            class="users-primary-button"
        >
            <span aria-hidden="true">+</span>
            Nuevo usuario
        </a>

    </div>


    <!-- Tarjeta resumen con la cantidad total de usuarios. -->
    <div class="users-summary-card">

        <div class="users-summary-icon" aria-hidden="true">
            👥
        </div>

        <div class="users-summary-content">

            <span class="users-summary-label">
                Usuarios registrados
            </span>

            <strong class="users-summary-value">
                <?= (int) $totalUsers ?>
            </strong>

        </div>

    </div>


    <!-- Contenedor principal de la tabla. -->
    <div class="users-table-card">

        <div class="users-table-header">

            <div>

                <h2 class="users-table-title">
                    Listado de usuarios
                </h2>

                <p class="users-table-description">
                    Página <?= (int) $currentPage ?>
                    de
                    <?= (int) $totalPages ?>
                </p>

            </div>

        </div>


        <?php if (empty($users)): ?>

            <!-- Estado mostrado cuando no existen usuarios. -->
            <div class="users-empty-state">

                <div class="users-empty-icon" aria-hidden="true">
                    👤
                </div>

                <h3>
                    No hay usuarios registrados
                </h3>

                <p>
                    Todavía no existen usuarios para mostrar en este listado.
                </p>

                <a
                    href="/incuyo/cyberblog/public/admin/users/create"
                    class="users-primary-button"
                >
                    Crear primer usuario
                </a>

            </div>

        <?php else: ?>

            <div class="users-table-wrapper">

                <table class="users-table">

                    <thead>

                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Correo electrónico</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Registro</th>
                            <th class="users-actions-column">Acciones</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($users as $user): ?>

                            <?php
                            /*
                             * Convertimos el ID a entero porque proviene
                             * de la base de datos y se utiliza en URLs.
                             */
                            $userId = (int) ($user['id'] ?? 0);

                            /*
                             * Construimos el nombre completo.
                             */
                            $fullName = trim(
                                (string) ($user['nombre'] ?? '') . ' ' .
                                (string) ($user['apellido'] ?? '')
                            );

                            /*
                             * Si el nombre está vacío, mostramos un texto
                             * alternativo para evitar una celda vacía.
                             */
                            if ($fullName === '') {
                                $fullName = 'Usuario sin nombre';
                            }

                            /*
                             * Todos los valores se convierten explícitamente
                             * a string antes de utilizar htmlspecialchars().
                             *
                             * Esto evita errores cuando MySQL devuelve
                             * valores numéricos como estado, rol o fechas.
                             */
                            $fullName = htmlspecialchars(
                                (string) $fullName,
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            $username = htmlspecialchars(
                                (string) ($user['username'] ?? ''),
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            $email = htmlspecialchars(
                                (string) ($user['email'] ?? ''),
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            $role = htmlspecialchars(
                                (string) ($user['rol'] ?? ''),
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            $status = htmlspecialchars(
                                (string) ($user['estado'] ?? ''),
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            $createdAt = htmlspecialchars(
                                (string) ($user['created_at'] ?? ''),
                                ENT_QUOTES,
                                'UTF-8'
                            );

                            /*
                             * Convertimos los valores de rol y estado
                             * en clases CSS para mostrar badges visuales.
                             */
                            $roleClass = strtolower(
                                (string) ($user['rol'] ?? '')
                            );

                            $statusClass = strtolower(
                                (string) ($user['estado'] ?? '')
                            );

                            /*
                             * Generamos la inicial del nombre para el avatar.
                             */
                            $avatarSource = trim(
                                (string) ($user['nombre'] ?? '')
                            );

                            $avatarInitial = $avatarSource !== ''
                                ? strtoupper(
                                    substr($avatarSource, 0, 1)
                                )
                                : 'U';

                            $avatarInitial = htmlspecialchars(
                                (string) $avatarInitial,
                                ENT_QUOTES,
                                'UTF-8'
                            );
                            ?>

                            <tr>

                                <td>
                                    <span class="users-id">
                                        #<?= $userId ?>
                                    </span>
                                </td>

                                <td>

                                    <div class="users-user-cell">

                                        <div
                                            class="users-avatar"
                                            aria-hidden="true"
                                        >
                                            <?= $avatarInitial ?>
                                        </div>

                                        <div class="users-user-information">

                                            <strong class="users-user-name">
                                                <?= $fullName ?>
                                            </strong>

                                            <span class="users-user-username">
                                                @<?= $username ?>
                                            </span>

                                        </div>

                                    </div>

                                </td>

                                <td>
                                    <span class="users-email">
                                        <?= $email ?>
                                    </span>
                                </td>

                                <td>
                                    <span
                                        class="users-badge users-role-<?= htmlspecialchars(
                                            (string) $roleClass,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >
                                        <?= $role ?>
                                    </span>
                                </td>

                                <td>
                                    <span
                                        class="users-badge users-status-<?= htmlspecialchars(
                                            (string) $statusClass,
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >
                                        <?= $status ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="users-created-at">
                                        <?= $createdAt ?>
                                    </span>
                                </td>

                                <td class="users-actions-column">

                                    <div class="users-actions">

                                        <!-- Enlace para editar el usuario. -->
                                        <a
                                            href="/incuyo/cyberblog/public/admin/users/edit/<?= $userId ?>"
                                            class="users-action-button users-edit-button"
                                            title="Editar usuario"
                                        >
                                            <span aria-hidden="true">✏️</span>
                                            Editar
                                        </a>

                                        <!--
                                         * La eliminación utiliza POST y no GET
                                         * porque es una operación destructiva.
                                         -->
                                        <form
                                            method="POST"
                                            action="/incuyo/cyberblog/public/admin/users/delete/<?= $userId ?>"
                                            class="users-delete-form"
                                            onsubmit="return confirm('¿Estás seguro de que querés eliminar este usuario? Esta acción no se puede deshacer.');"
                                        >

                                            <!-- Token CSRF para proteger la operación. -->
                                            <input
                                                type="hidden"
                                                name="csrf_token"
                                                value="<?= htmlspecialchars(
                                                    (string) (
                                                        $_SESSION['csrf_token'] ?? ''
                                                    ),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                            >

                                            <button
                                                type="submit"
                                                class="users-action-button users-delete-button"
                                                title="Eliminar usuario"
                                            >
                                                <span aria-hidden="true">🗑️</span>
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


            <?php if ($pagination && $pagination->getTotalPages() > 1): ?>

                <!-- Navegación entre las páginas del listado. -->
                <nav
                    class="users-pagination"
                    aria-label="Paginación de usuarios"
                >

                    <div class="users-pagination-information">
                        Página <?= (int) $currentPage ?>
                        de
                        <?= (int) $totalPages ?>
                    </div>

                    <div class="users-pagination-links">

                        <?php if ($pagination->hasPreviousPage()): ?>

                            <a
                                href="/incuyo/cyberblog/public/admin/users?page=<?= (int) ($currentPage - 1) ?>"
                                class="users-pagination-link"
                            >
                                ← Anterior
                            </a>

                        <?php else: ?>

                            <span class="users-pagination-link users-pagination-disabled">
                                ← Anterior
                            </span>

                        <?php endif; ?>


                        <?php foreach ($pagination->getPages() as $page): ?>

                            <?php
                            /*
                             * La clase Pagination puede devolver números
                             * enteros. Por eso convertimos la comparación
                             * y la salida a tipos seguros.
                             */
                            $pageNumber = (int) $page;
                            ?>

                            <?php if ($pageNumber === (int) $currentPage): ?>

                                <span class="users-pagination-link users-pagination-current">
                                    <?= $pageNumber ?>
                                </span>

                            <?php else: ?>

                                <a
                                    href="/incuyo/cyberblog/public/admin/users?page=<?= $pageNumber ?>"
                                    class="users-pagination-link"
                                >
                                    <?= $pageNumber ?>
                                </a>

                            <?php endif; ?>

                        <?php endforeach; ?>


                        <?php if ($pagination->hasNextPage()): ?>

                            <a
                                href="/incuyo/cyberblog/public/admin/users?page=<?= (int) ($currentPage + 1) ?>"
                                class="users-pagination-link"
                            >
                                Siguiente →
                            </a>

                        <?php else: ?>

                            <span class="users-pagination-link users-pagination-disabled">
                                Siguiente →
                            </span>

                        <?php endif; ?>

                    </div>

                </nav>

            <?php endif; ?>

        <?php endif; ?>

    </div>

</div>