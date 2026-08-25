<?php

declare(strict_types=1);

?>

<section class="blog-page">

    <div class="container">

        <!-- ENCABEZADO -->
        <div class="blog-page-header">

            <p class="section-label">
                // CYBERSECURITY BLOG
            </p>

            <h1>
                Últimos artículos
            </h1>

            <p>
                Investigación, aprendizaje y conocimiento sobre
                ciberseguridad, seguridad ofensiva y defensa.
            </p>

        </div>


        <!-- LISTADO DE ARTÍCULOS -->
        <?php if (empty($articles)): ?>

            <div class="empty-state">

                <span class="empty-icon">
                    &gt;_
                </span>

                <h2>
                    No hay artículos publicados
                </h2>

                <p>
                    Actualmente no hay contenido disponible.
                    Próximamente encontrarás nuevos artículos aquí.
                </p>

            </div>

        <?php else: ?>

            <div class="articles-grid">

                <?php foreach ($articles as $article): ?>

                    <article class="article-card">

                        <!-- IMAGEN DESTACADA -->
                        <?php if (
                            !empty($article['imagen'])
                        ): ?>

                            <a
                                href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars($article['slug']) ?>"
                                class="article-image-link"
                            >

                                <div class="article-image-wrapper">

                                    <img
                                        src="/incuyo/cyberblog/public/uploads/articles/<?= htmlspecialchars($article['imagen']) ?>"
                                        alt="<?= htmlspecialchars($article['titulo']) ?>"
                                        class="article-image"
                                        loading="lazy"
                                    >

                                </div>

                            </a>

                        <?php endif; ?>


                        <div class="article-card-content">

                            <div class="article-card-top">

                                <a
                                    href="/incuyo/cyberblog/public/category/<?= htmlspecialchars($article['categoria_slug']) ?>"
                                    class="article-category"
                                >
                                    <?= htmlspecialchars($article['categoria']) ?>
                                </a>

                            </div>


                            <h2 class="article-title">

                                <a
                                    href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars($article['slug']) ?>"
                                >
                                    <?= htmlspecialchars($article['titulo']) ?>
                                </a>

                            </h2>


                            <p class="article-excerpt">

                                <?= htmlspecialchars($article['resumen']) ?>

                            </p>


                            <div class="article-card-footer">

                                <span class="article-author">

                                    <span>
                                        //
                                    </span>

                                    <?= htmlspecialchars($article['autor']) ?>

                                </span>


                                <a
                                    href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars($article['slug']) ?>"
                                    class="read-more"
                                >
                                    Leer artículo
                                    <span>→</span>
                                </a>

                            </div>

                        </div>

                    </article>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</section>