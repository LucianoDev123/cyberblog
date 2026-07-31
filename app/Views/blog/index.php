<?php

declare(strict_types=1);
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>CyberBlog</title>

</head>

<body>

    <h1>CyberBlog</h1>

    <hr>

    <?php if (empty($articles)): ?>

        <p>No hay artículos publicados.</p>

    <?php else: ?>

        <?php foreach ($articles as $article): ?>

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

                    <?= htmlspecialchars($article['resumen']) ?>

                </p>

                <hr>

            </article>

        <?php endforeach; ?>

    <?php endif; ?>

</body>

</html>