<?php

declare(strict_types=1);

?>

<section class="blog-page">

    <div class="container">


        <!-- ENCABEZADO -->
        <div class="blog-page-header">

            <p class="section-label">

                // CATEGORÍA

            </p>


            <h1>

                <?= htmlspecialchars(
                    $category['nombre']
                ) ?>

            </h1>


            <?php if (
                !empty(
                    $category['descripcion']
                )
            ): ?>

                <p>

                    <?= htmlspecialchars(
                        $category['descripcion']
                    ) ?>

                </p>

            <?php endif; ?>

        </div>


        <!-- ARTÍCULOS -->
        <?php if (
            empty($articles)
        ): ?>

            <div class="empty-state">

                <span class="empty-icon">

                    &gt;_

                </span>


                <h2>

                    No hay artículos en esta categoría

                </h2>


                <p>

                    Actualmente no existen artículos
                    publicados en
                    <?= htmlspecialchars(
                        $category['nombre']
                    ) ?>.

                </p>

            </div>


        <?php else: ?>


            <div class="articles-grid">


                <?php foreach (
                    $articles as $article
                ): ?>


                    <article class="article-card">


                        <?php if (
                            !empty(
                                $article['imagen']
                            )
                        ): ?>

                            <a
                                href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars(
                                    $article['slug']
                                ) ?>"
                                class="article-image-link"
                            >

                                <img
                                    src="/incuyo/cyberblog/public/uploads/articles/<?= htmlspecialchars(
                                        $article['imagen']
                                    ) ?>"
                                    alt="<?= htmlspecialchars(
                                        $article['titulo']
                                    ) ?>"
                                    class="article-image"
                                >

                            </a>

                        <?php endif; ?>


                        <div class="article-card-content">


                            <div class="article-card-top">

                                <span
                                    class="article-category"
                                >

                                    <?= htmlspecialchars(
                                        $article['categoria']
                                    ) ?>

                                </span>

                            </div>


                            <h2
                                class="article-title"
                            >

                                <a
                                    href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars(
                                        $article['slug']
                                    ) ?>"
                                >

                                    <?= htmlspecialchars(
                                        $article['titulo']
                                    ) ?>

                                </a>

                            </h2>


                            <p
                                class="article-excerpt"
                            >

                                <?= htmlspecialchars(
                                    $article['resumen']
                                ) ?>

                            </p>


                            <div
                                class="article-card-footer"
                            >

                                <span
                                    class="article-author"
                                >

                                    <span>

                                        //

                                    </span>

                                    <?= htmlspecialchars(
                                        $article['autor']
                                    ) ?>

                                </span>


                                <a
                                    href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars(
                                        $article['slug']
                                    ) ?>"
                                    class="read-more"
                                >

                                    Leer artículo

                                    <span>

                                        →

                                    </span>

                                </a>

                            </div>


                        </div>

                    </article>


                <?php endforeach; ?>


            </div>


        <?php endif; ?>


        <!-- VOLVER AL BLOG -->
        <div
            style="margin-top: 50px;"
        >

            <a
                href="/incuyo/cyberblog/public/blog"
                class="back-link"
            >

                <span>

                    ←

                </span>

                Volver al blog

            </a>

        </div>


    </div>

</section>