<?php

declare(strict_types=1);

?>

<h2>

    Categoría: <?= htmlspecialchars($category['nombre']) ?>

</h2>

<p>

    <?= htmlspecialchars($category['descripcion']) ?>

</p>

<hr>

<?php if (empty($articles)): ?>

    <p>No hay artículos en esta categoría.</p>

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

                <?= htmlspecialchars($article['resumen']) ?>

            </p>

            <hr>

        </article>

    <?php endforeach; ?>

<?php endif; ?>

<p>

    <a href="/incuyo/cyberblog/public/blog">

        ← Volver al Blog

    </a>

</p>