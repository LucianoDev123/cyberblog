<?php

declare(strict_types=1);
?>

<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title><?= htmlspecialchars($article['titulo']) ?></title>

</head>

<body>

    <h1><?= htmlspecialchars($article['titulo']) ?></h1>

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

</body>

</html>