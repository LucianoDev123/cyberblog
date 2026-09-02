<?php

declare(strict_types=1);
?>

<main class="series-page">

    <div class="container">

        <header class="page-header">

            <p class="page-kicker">
                SERIES
            </p>

            <h1>
                Series de artículos
            </h1>

            <p class="page-description">
                Recorridos temáticos para aprender
                diferentes tecnologías y conceptos
                de ciberseguridad.
            </p>

        </header>

        <?php if (empty($series)): ?>

            <section class="empty-state">

                <div class="empty-icon">
                    [ ]
                </div>

                <h2>
                    No hay series publicadas
                </h2>

                <p>
                    Actualmente no existen series
                    disponibles para consultar.
                </p>

            </section>

        <?php else: ?>

            <section class="series-grid">

                <?php foreach ($series as $serie): ?>

                    <article class="series-card">

                        <?php if (!empty($serie['imagen'])): ?>

                            <div class="series-card-image">

                                <img
                                    src="/incuyo/cyberblog/public/uploads/series/<?= htmlspecialchars(
                                        $serie['imagen'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                    alt="<?= htmlspecialchars(
                                        $serie['titulo'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>"
                                >

                            </div>

                        <?php endif; ?>

                        <div class="series-card-content">

                            <p class="series-card-label">
                                SERIE
                            </p>

                            <h2 class="series-card-title">
                                <?= htmlspecialchars(
                                    $serie['titulo'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </h2>

                            <?php if (!empty($serie['descripcion'])): ?>

                                <p class="series-card-description">
                                    <?= nl2br(
                                        htmlspecialchars(
                                            $serie['descripcion'],
                                            ENT_QUOTES,
                                            'UTF-8'
                                        )
                                    ) ?>
                                </p>

                            <?php endif; ?>

                            <p class="series-card-count">
                                <?= (int) $serie['total_articulos'] ?>
                                <?= (
                                    (int) $serie['total_articulos'] === 1
                                )
                                    ? ' artículo'
                                    : ' artículos'
                                ?>
                            </p>

                            <a
                                href="/incuyo/cyberblog/public/series/<?= htmlspecialchars(
                                    $serie['slug'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>"
                                class="series-card-link"
                            >
                                Ver serie
                                <span aria-hidden="true">
                                    →
                                </span>
                            </a>

                        </div>

                    </article>

                <?php endforeach; ?>

            </section>

        <?php endif; ?>

    </div>

</main>