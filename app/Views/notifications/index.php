<?php
declare(strict_types=1);
?>
<section class="container notifications-page">
    <header class="notifications-header">
        <div>
            <p class="notifications-eyebrow">CYBERBLOG / ACTIVIDAD</p>
            <h1>Mis notificaciones</h1>
            <p class="notifications-intro">Respuestas y novedades de los artículos que seguís.</p>
        </div>

        <?php if (!empty($unreadCount)): ?>
            <form method="POST" action="/notifications/read-all">
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
                <button type="submit" class="notifications-read-all">
                    Marcar todas como leídas
                </button>
            </form>
        <?php endif; ?>
    </header>

    <?php if (empty($notifications)): ?>
        <section class="notifications-empty">
            <span class="notifications-empty-icon" aria-hidden="true">♧</span>
            <h2>Estás al día</h2>
            <p>Cuando alguien responda tus comentarios o haya actividad en un artículo que seguís, aparecerá acá.</p>
            <a href="/blog">Explorar artículos</a>
        </section>
    <?php else: ?>
        <section class="notifications-list" aria-label="Listado de notificaciones">
            <?php foreach ($notifications as $notification): ?>
                <?php
                $isRead = (int) ($notification['leida'] ?? 0) === 1;
                $slug = (string) ($notification['articulo_slug'] ?? '');
                $commentId = (int) ($notification['comentario_id'] ?? 0);
                $targetUrl = $slug !== ''
                    ? '/blog/' . rawurlencode($slug) . ($commentId > 0 ? '#comment-' . $commentId : '')
                    : '/notifications';
                $createdAt = strtotime((string) ($notification['created_at'] ?? ''));
                if ($createdAt === false) {
                    $createdAt = time();
                }
                ?>
                <article class="notification-item <?= $isRead ? 'is-read' : 'is-unread' ?>">
                    <span class="notification-status" aria-hidden="true"></span>

                    <div class="notification-body">
                        <p class="notification-message">
                            <?= htmlspecialchars((string) ($notification['mensaje'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <time datetime="<?= htmlspecialchars(date(DATE_ATOM, $createdAt), ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars(date('d/m/Y H:i', $createdAt), ENT_QUOTES, 'UTF-8') ?>
                        </time>

                        <div class="notification-actions">
                            <a class="notification-open" href="<?= htmlspecialchars($targetUrl, ENT_QUOTES, 'UTF-8') ?>">
                                Ver conversación
                            </a>

                            <?php if (!$isRead): ?>
                                <form method="POST" action="/notifications/read/<?= (int) ($notification['id'] ?? 0) ?>">
                                    <input
                                        type="hidden"
                                        name="csrf_token"
                                        value="<?= htmlspecialchars($csrfToken ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                    >
                                    <button type="submit" class="notification-mark-read">
                                        Marcar como leída
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!$isRead): ?>
                        <span class="notification-new-label">NUEVA</span>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</section>
