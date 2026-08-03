<?php

declare(strict_types=1);

?>

<article>

    <h2>

        <?= htmlspecialchars($article['titulo']) ?>

    </h2>

    <p>

        <strong>Autor:</strong>

        <?= htmlspecialchars($article['autor']) ?>

    </p>

    <p>

        <strong>Categoría:</strong>

        <?= htmlspecialchars($article['categoria']) ?>

    </p>

    <p>

        <strong>Fecha:</strong>

        <?= htmlspecialchars($article['created_at']) ?>

    </p>

    <hr>

    <div>

        <?= $article['contenido'] ?>

    </div>

    <hr>

    <p>

        <a href="/incuyo/cyberblog/public/blog">

            ← Volver al listado

        </a>

    </p>

</article>