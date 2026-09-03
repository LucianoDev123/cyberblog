<?php

declare(strict_types=1);

?>

<section class="series-admin-page series-form-page">


    <!-- =========================================================
         ENCABEZADO
         ========================================================= -->

    <div class="admin-page-header">

        <div>

            <p class="section-label">
                ADMINISTRACIÓN
            </p>


            <h1>
                Editar serie
            </h1>


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


    <!-- =========================================================
         FORMULARIO
         ========================================================= -->

    <div class="series-form-card">

        <div class="series-form-header">

            <div>

                <p class="series-form-label">
                    INFORMACIÓN DE LA SERIE
                </p>


                <h2>
                    Datos principales
                </h2>


                <p>
                    Modifica los datos de la serie.
                    Los cambios se aplicarán al guardar.
                </p>

            </div>

        </div>


        <form
            action="/incuyo/cyberblog/public/admin/series/update/<?= (int) $serie['id'] ?>"
            method="POST"
            class="series-form"
        >


            <!-- =================================================
                 TÍTULO
                 ================================================= -->

            <div class="admin-form-group">

                <label for="titulo">

                    Título de la serie

                    <span class="series-required">
                        *
                    </span>

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
                    autocomplete="off"
                >


                <small>

                    Al guardar, la URL de la serie
                    se actualizará automáticamente.

                </small>

            </div>


            <!-- =================================================
                 SLUG
                 ================================================= -->

            <div class="admin-form-group">

                <label for="slug-preview">

                    URL de la serie

                </label>


                <div class="series-slug-preview">

                    <span>
                        /series/
                    </span>


                    <input
                        type="text"
                        id="slug-preview"
                        value="<?= htmlspecialchars(
                            $serie['slug'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                        readonly
                    >

                </div>


                <small>

                    Esta URL se genera automáticamente
                    a partir del título de la serie.

                </small>

            </div>


            <!-- =================================================
                 DESCRIPCIÓN
                 ================================================= -->

            <div class="admin-form-group">

                <label for="descripcion">

                    Descripción

                </label>


                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="6"
                    maxlength="1000"
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


            <!-- =================================================
                 ESTADO
                 ================================================= -->

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


            <!-- =================================================
                 INFORMACIÓN
                 ================================================= -->

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


            <!-- =================================================
                 ACCIONES
                 ================================================= -->

            <div class="admin-form-actions series-form-actions">

                <a
                    href="/incuyo/cyberblog/public/admin/series"
                    class="admin-button admin-button-secondary"
                >

                    Cancelar

                </a>


                <button
                    type="submit"
                    class="admin-button admin-button-primary"
                >

                    Guardar cambios

                </button>

            </div>


        </form>

    </div>

</section>