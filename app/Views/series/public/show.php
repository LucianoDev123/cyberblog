<?php

declare(strict_types=1);

?>

<style>

/* =========================================================
   SERIES - PUBLIC DETAIL
   ========================================================= */

.series-public-page {
    width: min(
        calc(100% - 40px),
        1200px
    );

    margin: 0 auto;

    padding: 90px 0 110px;
}


.series-public-header {
    max-width: 900px;

    margin-bottom: 60px;

    padding-bottom: 40px;

    border-bottom: 1px solid #26303d;
}


.series-public-eyebrow {
    display: inline-block;

    margin-bottom: 18px;

    color: #39ff88;

    font-family: "JetBrains Mono", monospace;

    font-size: 0.75rem;

    font-weight: 700;

    letter-spacing: 0.14em;
}


.series-public-header h1 {
    margin: 0 0 22px;

    color: #f1f5f9;

    font-size: clamp(
        2.8rem,
        6vw,
        5rem
    );

    line-height: 1.05;

    letter-spacing: -0.04em;
}


.series-public-description {
    max-width: 760px;

    margin: 0 0 28px;

    color: #94a3b8;

    font-size: 1.05rem;

    line-height: 1.8;
}


.series-public-meta {
    display: flex;

    align-items: center;

    gap: 12px;

    color: #64748b;

    font-family: "JetBrains Mono", monospace;

    font-size: 0.78rem;
}


/* =========================================================
   LISTADO DE ARTÍCULOS
   ========================================================= */

.series-public-articles {
    max-width: 1000px;
}


.series-public-list {
    display: flex;

    flex-direction: column;

    border-top: 1px solid #26303d;
}


.series-public-article {
    display: grid;

    grid-template-columns: 90px minmax(0, 1fr);

    gap: 30px;

    padding: 34px 0;

    border-bottom: 1px solid #26303d;

    transition:
        padding-left 0.25s ease,
        background 0.25s ease;
}


.series-public-article:hover {
    padding-left: 10px;

    background: rgba(57, 255, 136, 0.025);
}


.series-public-number {
    padding-top: 4px;

    color: #39ff88;

    font-family: "JetBrains Mono", monospace;

    font-size: 1rem;

    font-weight: 700;

    letter-spacing: 0.08em;
}


.series-public-article-content {
    min-width: 0;
}


.series-public-article-content h2 {
    margin: 0 0 14px;

    font-size: 1.55rem;

    line-height: 1.3;
}


.series-public-article-content h2 a {
    color: #f1f5f9;

    text-decoration: none;

    transition: color 0.25s ease;
}


.series-public-article-content h2 a:hover {
    color: #39ff88;
}


.series-public-article-content p {
    max-width: 760px;

    margin: 0 0 22px;

    color: #94a3b8;

    line-height: 1.75;
}


.series-public-read-link {
    display: inline-flex;

    align-items: center;

    gap: 8px;

    color: #f1f5f9;

    font-family: "JetBrains Mono", monospace;

    font-size: 0.8rem;

    font-weight: 600;

    text-decoration: none;

    transition: color 0.25s ease;
}


.series-public-read-link:hover {
    color: #39ff88;
}


/* =========================================================
   SERIE SIN ARTÍCULOS
   ========================================================= */

.series-public-empty {
    max-width: 760px;

    padding: 50px 40px;

    background: #141a22;

    border: 1px solid #26303d;

    text-align: center;
}


.series-public-empty-icon {
    display: block;

    margin-bottom: 20px;

    color: #39ff88;

    font-family: "JetBrains Mono", monospace;

    font-size: 1.8rem;

    font-weight: 700;
}


.series-public-empty h2 {
    margin: 0 0 14px;

    color: #f1f5f9;

    font-size: 1.4rem;

    line-height: 1.4;
}


.series-public-empty p {
    max-width: 600px;

    margin: 0 auto;

    color: #94a3b8;

    line-height: 1.7;
}


/* =========================================================
   VOLVER
   ========================================================= */

.series-public-footer {
    max-width: 1000px;

    margin-top: 50px;

    padding-top: 30px;

    border-top: 1px solid #26303d;
}


.series-public-back {
    display: inline-flex;

    align-items: center;

    gap: 10px;

    color: #94a3b8;

    font-family: "JetBrains Mono", monospace;

    font-size: 0.8rem;

    text-decoration: none;

    transition: color 0.25s ease;
}


.series-public-back:hover {
    color: #39ff88;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 700px) {

    .series-public-page {
        width: min(
            calc(100% - 32px),
            1200px
        );

        padding: 60px 0 80px;
    }


    .series-public-header {
        margin-bottom: 40px;

        padding-bottom: 30px;
    }


    .series-public-header h1 {
        font-size: 2.6rem;
    }


    .series-public-article {
        grid-template-columns: 1fr;

        gap: 10px;

        padding: 28px 0;
    }


    .series-public-number {
        padding-top: 0;
    }


    .series-public-article:hover {
        padding-left: 0;
    }


    .series-public-empty {
        padding: 40px 24px;
    }

}

</style>


<main class="series-public-page">


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

                <?= (int) $totalArticles === 1
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

                    Esta serie todavía no tiene
                    artículos publicados.

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


</main>