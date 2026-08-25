<?php

declare(strict_types=1);

?>

<section class="article-page">

    <div class="container article-container">

        <!-- VOLVER AL BLOG -->
        <a
            href="/incuyo/cyberblog/public/blog"
            class="back-link"
        >
            <span>←</span>
            Volver al blog
        </a>


        <article class="article-full">

            <!-- CABECERA DEL ARTÍCULO -->
            <header class="article-header">

                <a
                    href="/incuyo/cyberblog/public/category/<?= htmlspecialchars($article['categoria_slug'] ?? '') ?>"
                    class="article-category"
                >
                    <?= htmlspecialchars($article['categoria']) ?>
                </a>


                <h1>

                    <?= htmlspecialchars($article['titulo']) ?>

                </h1>


                <div class="article-meta">

                    <span>

                        <strong>
                            //
                        </strong>

                        <?= htmlspecialchars($article['autor']) ?>

                    </span>


                    <span class="meta-divider">
                        |
                    </span>


                    <span>

                        <?= htmlspecialchars($article['created_at']) ?>

                    </span>

                </div>

            </header>


            <!-- IMAGEN PRINCIPAL DEL ARTÍCULO -->
            <?php if (!empty($article['imagen'])): ?>

                <div class="article-featured-image">

                    <img
                        src="/incuyo/cyberblog/public/uploads/articles/<?= htmlspecialchars($article['imagen']) ?>"
                        alt="<?= htmlspecialchars($article['titulo']) ?>"
                    >

                </div>

            <?php endif; ?>


            <!-- RESUMEN -->
            <?php if (!empty($article['resumen'])): ?>

                <div class="article-summary">

                    <?= htmlspecialchars($article['resumen']) ?>

                </div>

            <?php endif; ?>


            <!-- CONTENIDO -->
            <div class="article-content">

                <?= $article['contenido'] ?>

            </div>


            <!-- PIE -->
            <footer class="article-footer">

                <a
                    href="/incuyo/cyberblog/public/blog"
                    class="back-link"
                >
                    <span>←</span>
                    Volver al listado
                </a>

            </footer>

        </article>

    </div>

</section>