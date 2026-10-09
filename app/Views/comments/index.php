<?php

declare(strict_types=1);

$comments = $comments ?? [];
$approvedComments = $approvedComments ?? [];

?>

<div class="admin-page">

    <!-- Encabezado de la página -->
    <div class="admin-page-header">

        <div>
            <h1>Moderación de comentarios</h1>

            <p>
                Revisá los comentarios recibidos antes de publicarlos en CyberBlog.
            </p>

            <a
                href="/admin"
                class="admin-button admin-button-secondary comments-back-button"
            >
                ← Volver al dashboard
            </a>
        </div>

    </div>

    <!-- Mensaje de error.
         Los mensajes de éxito se muestran en el layout general. -->
    <?php if (!empty($flashError)): ?>

        <div
            class="admin-alert admin-alert-error"
            role="alert"
        >
            <?= htmlspecialchars(
                $flashError,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </div>

    <?php endif; ?>


    <!-- =========================================
         COMENTARIOS PENDIENTES
         ========================================= -->
    <section class="admin-card">

        <div class="admin-card-header">

            <div>
                <h2>Comentarios pendientes</h2>

                <p>
                    Total por revisar:
                    <strong><?= count($comments) ?></strong>
                </p>
            </div>

        </div>

        <?php if (empty($comments)): ?>

            <div class="comments-admin-empty">

                <span class="comments-admin-empty-icon">
                    ✓
                </span>

                <h3>No hay comentarios pendientes</h3>

                <p>
                    Todos los comentarios recibidos ya fueron revisados.
                </p>

            </div>

        <?php else: ?>

            <div class="comments-admin-list">

                <?php foreach ($comments as $comment): ?>

                    <?php
                    $comentarioId = (int) ($comment['id'] ?? 0);

                    $comentarioPadreId = isset($comment['comentario_padre_id'])
                        ? (int) $comment['comentario_padre_id']
                        : 0;

                    $esRespuesta = $comentarioPadreId > 0;

                    $textoPadre = trim(
                        (string) ($comment['comentario_padre'] ?? '')
                    );
                    ?>

                    <article class="comments-admin-item">

                        <header class="comments-admin-item-header">

                            <div>

                                <h3>
                                    <?= htmlspecialchars(
                                        $comment['autor'] ?? 'Usuario',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </h3>

                                <p class="comments-admin-email">
                                    <?= htmlspecialchars(
                                        $comment['email'] ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </p>

                            </div>

                            <span class="comments-admin-status">
                                Pendiente
                            </span>

                        </header>

                        <div class="comments-admin-meta">

                            <span>
                                Artículo:

                                <strong>
                                    <?= htmlspecialchars(
                                        $comment['articulo_titulo'] ?? 'Sin título',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </strong>
                            </span>

                            <span>
                                Fecha:

                                <?php if (!empty($comment['created_at'])): ?>

                                    <?= htmlspecialchars(
                                        (string) $comment['created_at'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                <?php else: ?>

                                    No disponible

                                <?php endif; ?>

                            </span>

                        </div>

                        <?php if ($esRespuesta): ?>

                            <div class="comments-admin-meta">

                                <span>
                                    Tipo:

                                    <strong>
                                        Respuesta al comentario #<?= $comentarioPadreId ?>
                                    </strong>
                                </span>

                            </div>

                            <?php if ($textoPadre !== ''): ?>

                                <div class="comments-admin-content">

                                    <strong>
                                        Comentario al que responde:
                                    </strong>

                                    <blockquote>
                                        <?= nl2br(
                                            htmlspecialchars(
                                                $textoPadre,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            )
                                        ) ?>
                                    </blockquote>

                                </div>

                            <?php endif; ?>

                        <?php else: ?>

                            <div class="comments-admin-meta">

                                <span>
                                    Tipo:
                                    <strong>Comentario principal</strong>
                                </span>

                            </div>

                        <?php endif; ?>

                        <div class="comments-admin-content">

                            <?= nl2br(
                                htmlspecialchars(
                                    $comment['comentario'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                )
                            ) ?>

                        </div>

                        <footer class="comments-admin-actions">

                            <!-- Aprobar comentario -->
                            <form
                                action="/admin/comments/approve/<?= $comentarioId ?>"
                                method="POST"
                            >

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= htmlspecialchars(
                                        $csrfToken ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                <button
                                    type="submit"
                                    class="comments-admin-button comments-admin-button-approve"
                                >
                                    Aprobar comentario
                                </button>

                            </form>

                            <!-- Eliminar comentario y descendientes -->
                            <form
                                action="/admin/comments/delete/<?= $comentarioId ?>"
                                method="POST"
                                onsubmit="return confirm('¿Seguro que querés eliminar este comentario? También se eliminarán todas sus respuestas y las respuestas de estas. Esta acción no se puede deshacer.');"
                            >

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= htmlspecialchars(
                                        $csrfToken ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                <button
                                    type="submit"
                                    class="comments-admin-button comments-admin-button-delete"
                                >
                                    Eliminar conversación
                                </button>

                            </form>

                        </footer>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>


    <!-- =========================================
         COMENTARIOS PUBLICADOS
         ========================================= -->
    <section class="admin-card">

        <div class="admin-card-header">

            <div>
                <h2>Comentarios publicados</h2>

                <p>
                    Total publicados:
                    <strong><?= count($approvedComments) ?></strong>
                </p>

                <p>
                    Desde esta sección podés eliminar comentarios publicados
                    y todas las respuestas que dependan de ellos.
                </p>
            </div>

        </div>

        <?php if (empty($approvedComments)): ?>

            <div class="comments-admin-empty">

                <span class="comments-admin-empty-icon">
                    ✓
                </span>

                <h3>No hay comentarios publicados</h3>

                <p>
                    Cuando apruebes comentarios, aparecerán en esta sección.
                </p>

            </div>

        <?php else: ?>

            <div class="comments-admin-list">

                <?php foreach ($approvedComments as $comment): ?>

                    <?php
                    $comentarioId = (int) ($comment['id'] ?? 0);

                    $comentarioPadreId = isset($comment['comentario_padre_id'])
                        ? (int) $comment['comentario_padre_id']
                        : 0;

                    $esRespuesta = $comentarioPadreId > 0;

                    $textoPadre = trim(
                        (string) ($comment['comentario_padre'] ?? '')
                    );
                    ?>

                    <article class="comments-admin-item">

                        <header class="comments-admin-item-header">

                            <div>

                                <h3>
                                    <?= htmlspecialchars(
                                        $comment['autor'] ?? 'Usuario',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </h3>

                                <p class="comments-admin-email">
                                    <?= htmlspecialchars(
                                        $comment['email'] ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </p>

                            </div>

                            <span class="comments-admin-status">
                                Publicado
                            </span>

                        </header>

                        <div class="comments-admin-meta">

                            <span>
                                Artículo:

                                <strong>
                                    <?= htmlspecialchars(
                                        $comment['articulo_titulo'] ?? 'Sin título',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </strong>
                            </span>

                            <span>
                                Fecha:

                                <?php if (!empty($comment['created_at'])): ?>

                                    <?= htmlspecialchars(
                                        (string) $comment['created_at'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                <?php else: ?>

                                    No disponible

                                <?php endif; ?>

                            </span>

                        </div>

                        <?php if ($esRespuesta): ?>

                            <div class="comments-admin-meta">

                                <span>
                                    Tipo:

                                    <strong>
                                        Respuesta al comentario #<?= $comentarioPadreId ?>
                                    </strong>
                                </span>

                            </div>

                            <?php if ($textoPadre !== ''): ?>

                                <div class="comments-admin-content">

                                    <strong>
                                        Comentario al que responde:
                                    </strong>

                                    <blockquote>
                                        <?= nl2br(
                                            htmlspecialchars(
                                                $textoPadre,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            )
                                        ) ?>
                                    </blockquote>

                                </div>

                            <?php endif; ?>

                        <?php else: ?>

                            <div class="comments-admin-meta">

                                <span>
                                    Tipo:
                                    <strong>Comentario principal</strong>
                                </span>

                            </div>

                        <?php endif; ?>

                        <div class="comments-admin-content">

                            <?= nl2br(
                                htmlspecialchars(
                                    $comment['comentario'] ?? '',
                                    ENT_QUOTES,
                                    'UTF-8'
                                )
                            ) ?>

                        </div>

                        <footer class="comments-admin-actions">

                            <!-- Eliminar comentario publicado y descendientes -->
                            <form
                                action="/admin/comments/delete/<?= $comentarioId ?>"
                                method="POST"
                                onsubmit="return confirm('¿Seguro que querés eliminar este comentario publicado? También se eliminarán todas sus respuestas y las respuestas de estas. Esta acción no se puede deshacer.');"
                            >

                                <input
                                    type="hidden"
                                    name="csrf_token"
                                    value="<?= htmlspecialchars(
                                        $csrfToken ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                <button
                                    type="submit"
                                    class="comments-admin-button comments-admin-button-delete"
                                >
                                    Eliminar conversación
                                </button>

                            </form>

                        </footer>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </section>

</div>