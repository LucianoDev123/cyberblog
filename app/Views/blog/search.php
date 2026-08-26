<section class="search-page">

    <div class="container">

        <div class="search-page-header">

            <p class="section-label">
                BUSCADOR DE CONTENIDO
            </p>

            <h1>
                Encuentra conocimiento
                <span>relacionado.</span>
            </h1>

            <p>
                Busca artículos, herramientas, tecnologías
                o conceptos de ciberseguridad.
            </p>

        </div>


        <form
            action="/incuyo/cyberblog/public/blog/search"
            method="GET"
            class="blog-search-form"
        >

            <div class="blog-search-wrapper">

                <span
                    class="blog-search-icon"
                    aria-hidden="true"
                >
                    &gt;_
                </span>

                <input
                    type="search"
                    name="q"
                    value="<?= htmlspecialchars($search) ?>"
                    placeholder="Ej: Wazuh, OPNsense, Active Directory..."
                    aria-label="Buscar artículos"
                    required
                >

                <button
                    type="submit"
                >
                    Buscar
                </button>

            </div>

        </form>


        <?php if ($search !== ''): ?>

            <div class="search-results-header">

                <p class="search-results-count">

                    Resultados para:

                    <strong>
                        <?= htmlspecialchars($search) ?>
                    </strong>

                    <span>
                        <?= count($articles) ?>
                        encontrado<?= count($articles) === 1 ? '' : 's' ?>
                    </span>

                </p>

            </div>


            <?php if (!empty($articles)): ?>

                <div class="articles-grid">

                    <?php foreach ($articles as $article): ?>

                        <article class="article-card">

                            <?php if (!empty($article['imagen'])): ?>

                                <a
                                    href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars($article['slug']) ?>"
                                    class="article-card-image"
                                >

                                    <img
                                        src="/incuyo/cyberblog/public/uploads/articles/<?= htmlspecialchars($article['imagen']) ?>"
                                        alt="<?= htmlspecialchars($article['titulo']) ?>"
                                    >

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

                                    <div class="article-author">

                                        Por

                                        <span>
                                            <?= htmlspecialchars($article['autor']) ?>
                                        </span>

                                    </div>


                                    <a
                                        href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars($article['slug']) ?>"
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

            <?php else: ?>

                <div class="empty-state">

                    <span class="empty-icon">
                        404
                    </span>

                    <h2>
                        No encontramos artículos
                    </h2>

                    <p>
                        No existen artículos publicados relacionados con
                        "<strong><?= htmlspecialchars($search) ?></strong>".
                    </p>

                </div>

            <?php endif; ?>

        <?php else: ?>

            <div class="empty-state">

                <span class="empty-icon">
                    &gt;_
                </span>

                <h2>
                    ¿Qué quieres aprender?
                </h2>

                <p>
                    Introduce una palabra clave para buscar contenido
                    dentro de CyberBlog.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>