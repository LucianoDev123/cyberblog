<?php

declare(strict_types=1);
?>

<div class="admin-dashboard">

    <!-- =====================================================
         ENCABEZADO
         ===================================================== -->

    <section class="dashboard-intro">

        <div>

            <span class="dashboard-eyebrow">
                CYBERBLOG / ADMIN
            </span>

            <h2>
                Resumen general
            </h2>

            <p>
                Estado actual del contenido y los usuarios
                de CyberBlog.
            </p>

        </div>

    </section>


    <!-- =====================================================
         ESTADÍSTICAS PRINCIPALES
         ===================================================== -->

    <section class="dashboard-stats">

        <!-- ARTÍCULOS -->

        <article class="dashboard-stat-card">

            <div class="dashboard-stat-header">

                <span class="dashboard-stat-label">
                    ARTÍCULOS
                </span>

                <span class="dashboard-stat-icon">
                    ▤
                </span>

            </div>

            <strong class="dashboard-stat-value">
                <?= (int) $totalArticles ?>
            </strong>

            <span class="dashboard-stat-description">
                Artículos registrados
            </span>

        </article>


        <!-- PUBLICADOS -->

        <article class="dashboard-stat-card">

            <div class="dashboard-stat-header">

                <span class="dashboard-stat-label">
                    PUBLICADOS
                </span>

                <span class="dashboard-stat-icon dashboard-stat-icon-success">
                    ✓
                </span>

            </div>

            <strong class="dashboard-stat-value">
                <?= (int) $publishedArticles ?>
            </strong>

            <span class="dashboard-stat-description">
                Artículos publicados
            </span>

        </article>


        <!-- BORRADORES -->

        <article class="dashboard-stat-card">

            <div class="dashboard-stat-header">

                <span class="dashboard-stat-label">
                    BORRADORES
                </span>

                <span class="dashboard-stat-icon dashboard-stat-icon-warning">
                    ○
                </span>

            </div>

            <strong class="dashboard-stat-value">
                <?= (int) $draftArticles ?>
            </strong>

            <span class="dashboard-stat-description">
                Artículos pendientes
            </span>

        </article>


        <!-- USUARIOS -->

        <article class="dashboard-stat-card">

            <div class="dashboard-stat-header">

                <span class="dashboard-stat-label">
                    USUARIOS
                </span>

                <span class="dashboard-stat-icon">
                    ◉
                </span>

            </div>

            <strong class="dashboard-stat-value">
                <?= (int) $totalUsers ?>
            </strong>

            <span class="dashboard-stat-description">
                Usuarios registrados
            </span>

        </article>


        <!-- CATEGORÍAS -->

        <article class="dashboard-stat-card">

            <div class="dashboard-stat-header">

                <span class="dashboard-stat-label">
                    CATEGORÍAS
                </span>

                <span class="dashboard-stat-icon">
                    ▦
                </span>

            </div>

            <strong class="dashboard-stat-value">
                <?= (int) $totalCategories ?>
            </strong>

            <span class="dashboard-stat-description">
                Categorías disponibles
            </span>

        </article>


        <!-- SERIES -->

        <article class="dashboard-stat-card">

            <div class="dashboard-stat-header">

                <span class="dashboard-stat-label">
                    SERIES
                </span>

                <span class="dashboard-stat-icon">
                    ≡
                </span>

            </div>

            <strong class="dashboard-stat-value">
                <?= (int) $totalSeries ?>
            </strong>

            <span class="dashboard-stat-description">
                Series creadas
            </span>

        </article>

    </section>


    <!-- =====================================================
         CONTENIDO PRINCIPAL
         ===================================================== -->

    <section class="dashboard-section">

        <div class="dashboard-section-header">

            <div>

                <span class="dashboard-section-eyebrow">
                    CONTENIDO
                </span>

                <h2>
                    Artículos recientes
                </h2>

            </div>

            <a
                href="/incuyo/cyberblog/public/admin/articles"
                class="dashboard-section-link"
            >
                Ver todos
                <span aria-hidden="true">
                    →
                </span>
            </a>

        </div>


        <?php if (empty($recentArticles)): ?>

            <div class="dashboard-empty">

                <div class="dashboard-empty-icon">
                    ▤
                </div>

                <h3>
                    No hay artículos
                </h3>

                <p>
                    Todavía no existen artículos registrados
                    en CyberBlog.
                </p>

                <a
                    href="/incuyo/cyberblog/public/admin/articles/create"
                    class="admin-button"
                >
                    Crear artículo
                </a>

            </div>

        <?php else: ?>

            <div class="dashboard-table-wrapper">

                <table class="dashboard-table">

                    <thead>

                        <tr>

                            <th>
                                Artículo
                            </th>

                            <th>
                                Categoría
                            </th>

                            <th>
                                Serie
                            </th>

                            <th>
                                Estado
                            </th>

                            <th>
                                Fecha
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php foreach ($recentArticles as $article): ?>

                            <tr>

                                <td>

                                    <div class="dashboard-article-title">

                                        <?= htmlspecialchars(
                                            $article['titulo'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </div>

                                </td>

                                <td>

                                    <span class="dashboard-category">

                                        <?= htmlspecialchars(
                                            $article['categoria'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </span>

                                </td>

                                <td>

                                    <?php if (!empty($article['serie'])): ?>

                                        <?= htmlspecialchars(
                                            $article['serie'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    <?php else: ?>

                                        <span class="dashboard-muted">
                                            Sin serie
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?php
                                    $estado = strtolower(
                                        (string) $article['estado']
                                    );
                                    ?>

                                    <span
                                        class="dashboard-status dashboard-status-<?= htmlspecialchars(
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

                                <td>

                                    <span class="dashboard-date">

                                        <?= htmlspecialchars(
                                            $article['created_at'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        ) ?>

                                    </span>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php endif; ?>

    </section>


    <!-- =====================================================
         ACCIONES RÁPIDAS
         ===================================================== -->

    <section class="dashboard-section">

        <div class="dashboard-section-header">

            <div>

                <span class="dashboard-section-eyebrow">
                    ACCIONES
                </span>

                <h2>
                    Acciones rápidas
                </h2>

            </div>

        </div>


        <div class="dashboard-actions">

            <a
                href="/incuyo/cyberblog/public/admin/articles/create"
                class="dashboard-action"
            >

                <span class="dashboard-action-icon">
                    +
                </span>

                <span class="dashboard-action-content">

                    <strong>
                        Nuevo artículo
                    </strong>

                    <small>
                        Crear contenido
                    </small>

                </span>

                <span class="dashboard-action-arrow">
                    →
                </span>

            </a>


            <a
                href="/incuyo/cyberblog/public/admin/series/create"
                class="dashboard-action"
            >

                <span class="dashboard-action-icon">
                    ≡
                </span>

                <span class="dashboard-action-content">

                    <strong>
                        Nueva serie
                    </strong>

                    <small>
                        Crear una serie
                    </small>

                </span>

                <span class="dashboard-action-arrow">
                    →
                </span>

            </a>


            <a
                href="/incuyo/cyberblog/public/admin/categories/create"
                class="dashboard-action"
            >

                <span class="dashboard-action-icon">
                    ▦
                </span>

                <span class="dashboard-action-content">

                    <strong>
                        Nueva categoría
                    </strong>

                    <small>
                        Organizar contenido
                    </small>

                </span>

                <span class="dashboard-action-arrow">
                    →
                </span>

            </a>


            <a
                href="/incuyo/cyberblog/public/admin/users"
                class="dashboard-action"
            >

                <span class="dashboard-action-icon">
                    ◉
                </span>

                <span class="dashboard-action-content">

                    <strong>
                        Usuarios
                    </strong>

                    <small>
                        Administrar usuarios
                    </small>

                </span>

                <span class="dashboard-action-arrow">
                    →
                </span>

            </a>

        </div>

    </section>

</div>