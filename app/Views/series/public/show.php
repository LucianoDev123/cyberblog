<?php

declare(strict_types=1);

?>


<section class="series-public-page">


    <!-- ===================================================== -->
    <!-- ENCABEZADO DE LA SERIE -->
    <!-- ===================================================== -->

    <div class="series-public-header">


        <span class="series-public-eyebrow">

            SERIE

        </span>


        <h1>

            <?= htmlspecialchars(
                $serie['titulo'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?>

        </h1>


        <?php if (
            !empty($serie['descripcion'])
        ): ?>

            <p class="series-public-description">

                <?= nl2br(
                    htmlspecialchars(
                        $serie['descripcion'],
                        ENT_QUOTES,
                        'UTF-8'
                    )
                ) ?>

            </p>

        <?php endif; ?>


        <div class="series-public-meta">

            <span>

                <?= (int) $totalArticles ?>

                <?= $totalArticles === 1
                    ? 'artículo'
                    : 'artículos'
                ?>

            </span>

        </div>


    </div>


    <!-- ===================================================== -->
    <!-- LISTADO DE ARTÍCULOS -->
    <!-- ===================================================== -->

    <div class="series-public-articles">


        <?php if (
            !empty($articles)
        ): ?>


            <div class="series-public-list">


                <?php foreach (
                    $articles
                    as $index => $article
                ): ?>


                    <article
                        class="series-public-article"
                    >


                        <!-- NÚMERO -->

                        <div
                            class="series-public-number"
                        >

                            <?= str_pad(
                                (string) (
                                    $index + 1
                                ),
                                2,
                                '0',
                                STR_PAD_LEFT
                            ) ?>

                        </div>


                        <!-- INFORMACIÓN -->

                        <div
                            class="series-public-article-content"
                        >


                            <h2>

                                <a
                                    href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars(
                                        $article['slug'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                                    <?= htmlspecialchars(
                                        $article['titulo'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </a>

                            </h2>


                            <?php if (
                                !empty(
                                    $article['resumen']
                                )
                            ): ?>

                                <p>

                                    <?= htmlspecialchars(
                                        $article['resumen'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>

                                </p>

                            <?php endif; ?>


                            <a
                                href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars(
                                    $article['slug'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                class="series-public-read-link"
                            >

                                Leer artículo →

                            </a>


                        </div>


                    </article>


                <?php endforeach; ?>


            </div>


        <?php else: ?>


            <!-- ================================================= -->
            <!-- SERIE SIN ARTÍCULOS -->
            <!-- ================================================= -->

            <div class="series-public-empty">


                <span
                    class="series-public-empty-icon"
                >

                    &gt;_

                </span>


                <h2>

                    Esta serie todavía no tiene artículos publicados.

                </h2>


                <p>

                    Los artículos aparecerán aquí
                    cuando sean publicados y asociados
                    a esta serie.

                </p>


            </div>


        <?php endif; ?>


    </div>


    <!-- ===================================================== -->
    <!-- VOLVER A SERIES -->
    <!-- ===================================================== -->

    <div class="series-public-footer">


        <a
            href="/incuyo/cyberblog/public/series"
            class="series-public-back"
        >

            ← Volver a todas las series

        </a>


    </div>


</section>