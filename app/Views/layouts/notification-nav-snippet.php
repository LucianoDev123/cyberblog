<!-- Notificaciones: colocar dentro de <nav class="main-nav"> para usuarios autenticados -->
<?php if ($isAuthenticated): ?>
    <?php
    // El contador se consulta solo para el usuario de la sesión.
    $notificationUnreadCount = 0;
    try {
        $notificationUnreadCount = (new \App\Models\Notification())
            ->countUnread((int) $_SESSION['usuario_id']);
    } catch (\Throwable $exception) {
        error_log('No se pudo obtener el contador de notificaciones: ' . $exception->getMessage());
    }
    ?>
    <a href="/notifications" class="nav-link nav-link-notifications" aria-label="Mis notificaciones">
        <span aria-hidden="true">♧</span>
        Notificaciones
        <?php if ($notificationUnreadCount > 0): ?>
            <span class="notification-badge">
                <?= $notificationUnreadCount > 99 ? '99+' : (int) $notificationUnreadCount ?>
            </span>
        <?php endif; ?>
    </a>
<?php endif; ?>
