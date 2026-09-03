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
                Nueva serie
            </h1>


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
                    Completa la información que utilizará
                    CyberBlog para identificar y mostrar la serie.
                </p>

            </div>

        </div>


        <form
            action="/incuyo/cyberblog/public/admin/series"
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
                    placeholder="Ejemplo: Introducción a Wazuh"
                    autocomplete="off"
                >


                <small>

                    Utiliza un título claro que identifique
                    el recorrido de aprendizaje.

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
                ></textarea>


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

                    Crear serie

                </button>

            </div>


        </form>

    </div>

</section>