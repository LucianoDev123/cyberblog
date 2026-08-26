<?php

declare(strict_types=1);

?>

<section class="series-page">


    <!-- ENCABEZADO -->

    <div class="series-page-header">

        <div>

            <span class="series-eyebrow">

                ADMINISTRACIÓN

            </span>


            <h2>

                Editar serie

            </h2>


            <p>

                Modifica la información y configuración
                de esta colección de artículos.

            </p>

        </div>


        <a
            href="/incuyo/cyberblog/public/admin/series"
            class="admin-button admin-button-secondary"
        >

            ← Volver a series

        </a>

    </div>


    <!-- FORMULARIO -->

    <form
        action="/incuyo/cyberblog/public/admin/series/update/<?= (int) $serie['id'] ?>"
        method="POST"
        class="series-form"
    >


        <!-- TÍTULO -->

        <div class="admin-form-group">

            <label for="titulo">

                Título de la serie

            </label>


            <input
                type="text"
                id="titulo"
                name="titulo"
                required
                maxlength="255"
                value="<?= htmlspecialchars(
                    $serie['titulo'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

        </div>


        <!-- SLUG -->

        <div class="admin-form-group">

            <label for="slug-preview">

                URL de la serie

            </label>


            <input
                type="text"
                id="slug-preview"
                value="/series/<?= htmlspecialchars(
                    $serie['slug'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
                readonly
            >


            <small>

                La URL se generará automáticamente
                a partir del título cuando guardes los cambios.

            </small>

        </div>


        <!-- DESCRIPCIÓN -->

        <div class="admin-form-group">

            <label for="descripcion">

                Descripción

            </label>


            <textarea
                id="descripcion"
                name="descripcion"
                rows="6"
                placeholder="Describe de qué trata esta serie..."
            ><?= htmlspecialchars(
                $serie['descripcion'] ?? '',
                ENT_QUOTES,
                'UTF-8'
            ) ?></textarea>


            <small>

                La descripción ayudará a los visitantes
                a comprender el objetivo de la serie.

            </small>

        </div>


        <!-- ESTADO -->

        <div class="admin-form-group">

            <label for="estado">

                Estado

            </label>


            <select
                id="estado"
                name="estado"
            >

                <option
                    value="borrador"
                    <?= (
                        ($serie['estado'] ?? '') === 'borrador'
                    )
                        ? 'selected'
                        : ''
                    ?>
                >

                    Borrador

                </option>


                <option
                    value="publicada"
                    <?= (
                        ($serie['estado'] ?? '') === 'publicada'
                    )
                        ? 'selected'
                        : ''
                    ?>
                >

                    Publicada

                </option>

            </select>


            <small>

                Las series en borrador no estarán
                disponibles en la sección pública.

            </small>

        </div>


        <!-- INFORMACIÓN DE LA SERIE -->

        <div class="series-information">


            <div class="series-information-item">

                <span>

                    Fecha de creación

                </span>


                <strong>

                    <?php

                    if (
                        !empty($serie['created_at'])
                    ) {

                        echo htmlspecialchars(
                            date(
                                'd/m/Y H:i',
                                strtotime(
                                    $serie['created_at']
                                )
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        );

                    } else {

                        echo 'No disponible';

                    }

                    ?>

                </strong>

            </div>


            <div class="series-information-item">

                <span>

                    Última actualización

                </span>


                <strong>

                    <?php

                    if (
                        !empty($serie['updated_at'])
                    ) {

                        echo htmlspecialchars(
                            date(
                                'd/m/Y H:i',
                                strtotime(
                                    $serie['updated_at']
                                )
                            ),
                            ENT_QUOTES,
                            'UTF-8'
                        );

                    } else {

                        echo 'No disponible';

                    }

                    ?>

                </strong>

            </div>


        </div>


        <!-- BOTONES -->

        <div class="admin-form-actions">


            <a
                href="/incuyo/cyberblog/public/admin/series"
                class="admin-button admin-button-secondary"
            >

                Cancelar

            </a>


            <button
                type="submit"
                class="admin-button"
            >

                Guardar cambios

            </button>


        </div>


    </form>


</section>