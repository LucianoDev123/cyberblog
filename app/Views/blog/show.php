<?php

declare(strict_types=1);

/*
 * Organizamos los comentarios aprobados en un árbol.
 *
 * Cada comentario tendrá un array "children" con sus respuestas.
 * Si el padre no está disponible en la lista aprobada, el
 * comentario se muestra como principal por seguridad.
 */
$comments = $comments ?? [];
$commentsById = [];

foreach ($comments as $comment) {
    $comment['children'] = [];
    $commentsById[(int) $comment['id']] = $comment;
}

$commentTree = [];

foreach (array_keys($commentsById) as $commentId) {
    $parentId = $commentsById[$commentId]['comentario_padre_id'] ?? null;

    if (
        $parentId !== null &&
        isset($commentsById[(int) $parentId]) &&
        (int) $parentId !== (int) $commentId
    ) {
        $commentsById[(int) $parentId]['children'][] = &$commentsById[$commentId];
    } else {
        $commentTree[] = &$commentsById[$commentId];
    }
}

unset($commentId);

?>

<main class="article-page">

    <div class="container article-container">

        <a href="/blog" class="back-link">
            <span aria-hidden="true">←</span>
            Volver al blog
        </a>

        <header class="article-header">

            <?php if (!empty($article['categoria'])): ?>
                <a
                    href="/category/<?= htmlspecialchars(
                        $article['categoria_slug'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    class="article-category"
                >
                    <?= htmlspecialchars(
                        $article['categoria'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </a>
            <?php endif; ?>

            <h1>
                <?= htmlspecialchars(
                    $article['titulo'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </h1>

            <div class="article-meta">

                <?php if (!empty($article['autor'])): ?>
                    <span>
                        Por
                        <strong>
                            <?= htmlspecialchars(
                                $article['autor'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </strong>
                    </span>
                <?php endif; ?>

                <?php if (
                    !empty($article['autor']) &&
                    !empty($article['created_at'])
                ): ?>
                    <span class="meta-divider" aria-hidden="true">/</span>
                <?php endif; ?>

                <?php if (!empty($article['created_at'])): ?>
                    <span>
                        <?= htmlspecialchars(
                            date(
                                'd/m/Y',
                                strtotime($article['created_at'])
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </span>
                <?php endif; ?>

            </div>

            <!-- Seguimiento del artículo -->
            <div class="article-follow">

                <div class="article-follow-action">
                    <?php if (!empty($isAuthenticated)): ?>

                        <form
                            method="POST"
                            action="/blog/<?= htmlspecialchars(
                                rawurlencode((string) ($article['slug'] ?? '')),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>/<?= !empty($isFollowing) ? 'unfollow' : 'follow' ?>"
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

                            <button type="submit" class="follow-button">
                                <?= !empty($isFollowing)
                                    ? 'Dejar de seguir'
                                    : 'Seguir artículo' ?>
                            </button>
                        </form>

                    <?php else: ?>

                        <a href="/login" class="follow-button">
                            Iniciá sesión para seguir este artículo
                        </a>

                    <?php endif; ?>
                </div>

                <span class="follow-count">
                    <?= (int) ($followerCount ?? 0) ?>
                    <?= (int) ($followerCount ?? 0) === 1
                        ? 'seguidor'
                        : 'seguidores' ?>
                </span>

            </div>



            <?php if (
                !empty($article['serie_id']) &&
                !empty($article['serie']) &&
                !empty($article['serie_slug'])
            ): ?>
                <div class="article-series">

                    <span class="article-series-label">SERIE</span>

                    <a
                        href="/series/<?= htmlspecialchars(
                            $article['serie_slug'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        class="article-series-link"
                    >
                        <?= htmlspecialchars(
                            $article['serie'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>
                    </a>

                </div>
            <?php endif; ?>

        </header>


        <?php if (!empty($article['imagen'])): ?>
            <figure class="article-featured-image">

                <img
                    src="/uploads/articles/<?= htmlspecialchars(
                        $article['imagen'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    alt="<?= htmlspecialchars(
                        $article['titulo'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                >

            </figure>
        <?php endif; ?>


        <?php if (!empty($article['resumen'])): ?>
            <div class="article-summary">
                <?= nl2br(
                    htmlspecialchars(
                        $article['resumen'],
                        ENT_QUOTES,
                        'UTF-8'
                    )
                ) ?>
            </div>
        <?php endif; ?>


        <article class="article-content">
            <?php
            /*
             * El contenido del artículo puede contener HTML
             * generado desde el editor.
             *
             * Se conserva el comportamiento original.
             */
            ?>
            <?= $article['contenido'] ?? '' ?>
        </article>


        <?php if (!empty($seriesNavigation)): ?>

            <nav
                class="series-navigation"
                aria-label="Navegación de la serie"
            >

                <div class="series-navigation-progress">
                    Parte
                    <?= (int) $seriesNavigation['current'] ?>
                    de
                    <?= (int) $seriesNavigation['total'] ?>
                </div>

                <div class="series-navigation-links">

                    <?php if (!empty($seriesNavigation['previous'])): ?>

                        <a
                            href="/blog/<?= htmlspecialchars(
                                $seriesNavigation['previous']['slug'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            class="series-navigation-link series-navigation-previous"
                        >

                            <span class="series-navigation-direction">
                                ← Anterior
                            </span>

                            <strong>
                                <?= htmlspecialchars(
                                    $seriesNavigation['previous']['titulo'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </strong>

                        </a>

                    <?php endif; ?>


                    <?php if (!empty($seriesNavigation['next'])): ?>

                        <a
                            href="/blog/<?= htmlspecialchars(
                                $seriesNavigation['next']['slug'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>"
                            class="series-navigation-link series-navigation-next"
                        >

                            <span class="series-navigation-direction">
                                Siguiente →
                            </span>

                            <strong>
                                <?= htmlspecialchars(
                                    $seriesNavigation['next']['titulo'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </strong>

                        </a>

                    <?php endif; ?>

                </div>

            </nav>

        <?php endif; ?>


        <!-- ===================================================== -->
        <!-- COMENTARIOS -->
        <!-- ===================================================== -->

        <section
            class="article-comments"
            id="comentarios"
            aria-labelledby="comments-title"
        >

            <header class="comments-header">

                <h2 id="comments-title">
                    Comentarios
                    <span class="comments-count">
                        (<?= count($comments) ?>)
                    </span>
                </h2>

                <p>
                    Participá de la conversación sobre este artículo.
                    Los comentarios nuevos se revisan antes de publicarse.
                </p>

            </header>


            <?php if (!empty($flashSuccess)): ?>

                <div
                    class="comments-message comments-message-success"
                    role="status"
                >
                    <?= htmlspecialchars(
                        $flashSuccess,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>

            <?php endif; ?>


            <?php if (!empty($flashError)): ?>

                <div
                    class="comments-message comments-message-error"
                    role="alert"
                >
                    <?= htmlspecialchars(
                        $flashError,
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>

            <?php endif; ?>


            <!-- FORMULARIO PARA UN COMENTARIO PRINCIPAL -->

            <?php if (!empty($isAuthenticated)): ?>

                <form
                    class="comment-form"
                    action="/blog/<?= htmlspecialchars(
                        $article['slug'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>/comments"
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

                    <div class="form-group">

                        <label for="comment-text">
                            Tu comentario
                        </label>

                        <textarea
                            id="comment-text"
                            name="comentario"
                            rows="5"
                            maxlength="2000"
                            required
                            placeholder="Escribí tu comentario..."
                            aria-describedby="comment-help"
                        ></textarea>

                        <small id="comment-help">
                            Máximo 2000 caracteres.
                            No incluyas información sensible.
                        </small>

                    </div>

                    <button
                        type="submit"
                        class="comment-submit"
                    >
                        Enviar comentario
                    </button>

                </form>

            <?php else: ?>

                <div class="comments-login-prompt">

                    <p>
                        Para comentar, primero tenés que iniciar sesión.
                    </p>

                    <a
                        class="comments-login-link"
                        href="/login"
                    >
                        Iniciar sesión
                    </a>

                </div>

            <?php endif; ?>


            <!-- LISTADO DE CONVERSACIONES -->

            <div class="comments-list">

                <?php if (!empty($commentTree)): ?>

                    <?php
                    /*
                     * Renderiza un comentario y sus respuestas.
                     *
                     * La profundidad visual se limita a dos niveles
                     * de indentación. Las respuestas más profundas
                     * siguen apareciendo, pero sin más indentación.
                     */
                    $renderComment = function (
                        array $comment,
                        int $depth = 0
                    ) use (
                        &$renderComment,
                        $isAuthenticated,
                        $csrfToken,
                        $article
                    ): void {

                        $visualDepth = min($depth, 2);

                        $commentId = (int) $comment['id'];

                        $role = $comment['usuario_rol'] ?? '';

                        $articleAuthorId = isset($comment['articulo_autor_id'])
                            ? (int) $comment['articulo_autor_id']
                            : 0;

                        $commentUserId = isset($comment['usuario_id'])
                            ? (int) $comment['usuario_id']
                            : 0;

                        $isArticleAuthor = (
                            $commentUserId > 0 &&
                            $articleAuthorId > 0 &&
                            $commentUserId === $articleAuthorId
                        );
                    ?>

                        <article
                            id="comment-<?= $commentId ?>"
                            class="comment-card comment-depth-<?= $visualDepth ?>"
                            data-comment-id="<?= $commentId ?>"
                        >

                            <header class="comment-card-header">

                                <div class="comment-author-info">

                                    <strong class="comment-author">
                                        <?= htmlspecialchars(
                                            $comment['autor'] ?? 'Usuario',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </strong>

                                    <div class="comment-badges">

                                        <?php if ($role === 'admin'): ?>
                                            <span class="comment-badge comment-badge-admin">
                                                ADMINISTRADOR
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($role === 'editor'): ?>
                                            <span class="comment-badge comment-badge-editor">
                                                EDITOR
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($isArticleAuthor): ?>
                                            <span class="comment-badge comment-badge-author">
                                                AUTOR DEL ARTÍCULO
                                            </span>
                                        <?php endif; ?>

                                    </div>

                                </div>


                                <?php if (!empty($comment['created_at'])): ?>

                                    <time
                                        class="comment-date"
                                        datetime="<?= htmlspecialchars(
                                            date(
                                                DATE_ATOM,
                                                strtotime($comment['created_at'])
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>"
                                    >
                                        <?= htmlspecialchars(
                                            date(
                                                'd/m/Y H:i',
                                                strtotime($comment['created_at'])
                                            ),
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>
                                    </time>

                                <?php endif; ?>

                            </header>


                            <div class="comment-content">
                                <?= nl2br(
                                    htmlspecialchars(
                                        $comment['comentario'] ?? '',
                                        ENT_QUOTES,
                                        'UTF-8'
                                    )
                                ) ?>
                            </div>


                            <?php if (!empty($isAuthenticated)): ?>

                                <details class="comment-reply">

                                    <summary class="comment-reply-toggle">
                                        Responder
                                    </summary>

                                    <form
                                        class="comment-form comment-reply-form"
                                        action="/blog/<?= htmlspecialchars(
                                            $article['slug'] ?? '',
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>/comments"
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

                                        <input
                                            type="hidden"
                                            name="comentario_padre_id"
                                            value="<?= $commentId ?>"
                                        >

                                        <div class="form-group">

                                            <label for="reply-<?= $commentId ?>">
                                                Tu respuesta
                                            </label>

                                            <textarea
                                                id="reply-<?= $commentId ?>"
                                                name="comentario"
                                                rows="3"
                                                maxlength="2000"
                                                required
                                                placeholder="Escribí tu respuesta..."
                                            ></textarea>

                                        </div>

                                        <button
                                            type="submit"
                                            class="comment-submit"
                                        >
                                            Enviar respuesta
                                        </button>

                                    </form>

                                </details>

                            <?php endif; ?>


                            <?php if (!empty($comment['children'])): ?>

                                <div class="comment-replies comment-replies-depth-<?= $visualDepth ?>">

                                    <?php foreach ($comment['children'] as $child): ?>

                                        <?php
                                        $renderComment(
                                            $child,
                                            $depth + 1
                                        );
                                        ?>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>

                        </article>

                    <?php
                    };
                    ?>


                    <?php foreach ($commentTree as $rootComment): ?>

                        <?php
                        $renderComment($rootComment);
                        ?>

                    <?php endforeach; ?>

                <?php else: ?>

                    <p class="comments-empty">
                        Todavía no hay comentarios aprobados.
                        ¡Podés ser el primero!
                    </p>

                <?php endif; ?>

            </div>

        </section>


        <!-- FOOTER DEL ARTÍCULO -->

        <footer class="article-footer">

            <?php if (!empty($article['categoria_slug'])): ?>

                <a
                    href="/category/<?= htmlspecialchars(
                        $article['categoria_slug'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    class="back-link"
                >

                    <span aria-hidden="true">←</span>

                    Más artículos de

                    <?= htmlspecialchars(
                        $article['categoria'] ?? 'esta categoría',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </a>

            <?php else: ?>

                <a href="/blog" class="back-link">
                    <span aria-hidden="true">←</span>
                    Volver al blog
                </a>

            <?php endif; ?>

        </footer>

    </div>

</main>