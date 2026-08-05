<?php

declare(strict_types=1);

?>

<h2>Artículos del Blog</h2>

<?php if (empty($articles)): ?>

    <p>No hay artículos publicados.</p>

<?php else: ?>

    <?php foreach ($articles as $article): ?>

        <article>

            <h3>

                <a href="/incuyo/cyberblog/public/blog/<?= htmlspecialchars($article['slug']) ?>">

                    <?= htmlspecialchars($article['titulo']) ?>

                </a>

            </h3>

            <p>

                <strong>Autor:</strong>

                <?= htmlspecialchars($article['autor']) ?>

            </p>

            <p>

                <strong>Categoría:</strong>

                <a href="/incuyo/cyberblog/public/category/<?= htmlspecialchars($article['categoria_slug']) ?>">

                    <?= htmlspecialchars($article['categoria']) ?>

                </a>

            </p>

            <p>

                <?= htmlspecialchars($article['resumen']) ?>

            </p>

            <hr>

        </article>

    <?php endforeach; ?>

<?php endif; ?>