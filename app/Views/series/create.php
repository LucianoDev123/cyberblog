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

                Nueva serie

            </h2>


            <p>

                Crea una colección para agrupar
                artículos relacionados dentro de CyberBlog.

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
        action="/incuyo/cyberblog/public/admin/series"
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
                placeholder="Ejemplo: Introducción a Wazuh"
            >

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
            ></textarea>


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

                <option value="borrador">

                    Borrador

                </option>


                <option value="publicada">

                    Publicada

                </option>

            </select>


            <small>

                Las series en borrador todavía no estarán
                disponibles en la sección pública.

            </small>

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

                Crear serie

            </button>


        </div>


    </form>


</section>