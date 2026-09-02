<?php

declare(strict_types=1);

?>

<main class="article-page">

    <div class="container article-container">

        <!-- ===================================================== -->
        <!-- VOLVER AL BLOG -->
        <!-- ===================================================== -->

        <a
            href="/incuyo/cyberblog/public/blog"
            class="back-link"
        >

            <span aria-hidden="true">
                ←
            </span>

            Volver al blog

        </a>


        <!-- ===================================================== -->
        <!-- ENCABEZADO DEL ARTÍCULO -->
        <!-- ===================================================== -->

        <header class="article-header">

            <!-- CATEGORÍA -->

            <?php if (!empty($article['categoria'])): ?>

                <a
                    href="/incuyo/cyberblog/public/category/<?= htmlspecialchars(
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


            <!-- TÍTULO -->

            <h1>

                <?= htmlspecialchars(
                    $article['titulo'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

            </h1>


            <!-- ================================================= -->
            <!-- METADATA -->
            <!-- ================================================= -->

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

                    <span
                        class="meta-divider"
                        aria-hidden="true"
                    >
                        /
                    </span>

                <?php endif; ?>


                <?php if (!empty($article['created_at'])): ?>

                    <span>

                        <?= htmlspecialchars(
                            date(
                                'd/m/Y',
                                strtotime(
                                    $article['created_at']
                                )
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </span>

                <?php endif; ?>

            </div>


            <!-- ================================================= -->
            <!-- INFORMACIÓN DE SERIE -->
            <!-- ================================================= -->

            <?php if (
                !empty($article['serie_id']) &&
                !empty($article['serie']) &&
                !empty($article['serie_slug'])
            ): ?>

                <div class="article-series">

                    <span class="article-series-label">
                        SERIE
                    </span>

                    <a
                        href="/incuyo/cyberblog/public/series/<?= htmlspecialchars(
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


        <!-- ===================================================== -->
        <!-- IMAGEN DESTACADA -->
        <!-- ===================================================== -->

        <?php if (!empty($article['imagen'])): ?>

            <figure class="article-featured-image">

                <img
                    src="/incuyo/cyberblog/public/uploads/articles/<?= htmlspecialchars(
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


        <!-- ===================================================== -->
        <!-- RESUMEN -->
        <!-- ===================================================== -->

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


        <!-- ===================================================== -->
        <!-- CONTENIDO -->
        <!-- ===================================================== -->

        <article class="article-content">

            <?php
            /*
             * El contenido del artículo puede contener
             * HTML generado desde el editor.
             *
             * Por eso NO aplicamos htmlspecialchars()
             * aquí.
             */
            ?>

            <?= $article['contenido'] ?? '' ?>

        </article>


        <!-- ===================================================== -->
        <!-- NAVEGACIÓN DE LA SERIE -->
        <!-- ===================================================== -->

        <?php if (!empty($seriesNavigation)): ?>

            <nav
                class="series-navigation"
                aria-label="Navegación de la serie"
            >

                <!-- ================================================= -->
                <!-- CABECERA DE LA NAVEGACIÓN -->
                <!-- ================================================= -->

                <div class="series-navigation-header">

                    <span class="series-navigation-label">

                        SERIE

                    </span>


                    <a
                        href="/incuyo/cyberblog/public/series/<?= htmlspecialchars(
                            $seriesNavigation['serie']['slug'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        class="series-navigation-title"
                    >

                        <?= htmlspecialchars(
                            $seriesNavigation['serie']['titulo'],
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>

                    </a>

                </div>


                <!-- ================================================= -->
                <!-- PROGRESO -->
                <!-- ================================================= -->

                <div class="series-navigation-progress">

                    Parte

                    <?= (int) $seriesNavigation['current'] ?>

                    de

                    <?= (int) $seriesNavigation['total'] ?>

                </div>


                <!-- ================================================= -->
                <!-- ANTERIOR / SIGUIENTE -->
                <!-- ================================================= -->

                <div class="series-navigation-links">


                    <!-- ============================================= -->
                    <!-- ARTÍCULO ANTERIOR -->
                    <!-- ============================================= -->

                    <?php if (
                        !empty(
                            $seriesNavigation['previous']
                        )
                    ): ?>

                        <a
                            href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars(
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


                    <!-- ============================================= -->
                    <!-- ARTÍCULO SIGUIENTE -->
                    <!-- ============================================= -->

                    <?php if (
                        !empty(
                            $seriesNavigation['next']
                        )
                    ): ?>

                        <a
                            href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars(
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
        <!-- FOOTER DEL ARTÍCULO -->
        <!-- ===================================================== -->

        <footer class="article-footer">

            <?php if (!empty($article['categoria_slug'])): ?>

                <a
                    href="/incuyo/cyberblog/public/category/<?= htmlspecialchars(
                        $article['categoria_slug'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    class="back-link"
                >

                    <span aria-hidden="true">
                        ←
                    </span>

                    Más artículos de
                    <?= htmlspecialchars(
                        $article['categoria'] ?? 'esta categoría',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>

                </a>

            <?php else: ?>

                <a
                    href="/incuyo/cyberblog/public/blog"
                    class="back-link"
                >

                    <span aria-hidden="true">
                        ←
                    </span>

                    Volver al blog

                </a>

            <?php endif; ?>

        </footer>

    </div>

</main>