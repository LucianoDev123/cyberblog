<?php

declare(strict_types=1);

$oldInput =
    $oldInput ?? [];

?>

<section class="admin-section">

    <div
        style="
            margin-bottom: 24px;
        "
    >

        <a
            href="/incuyo/cyberblog/public/admin/categories"
            style="
                color: var(--admin-primary);
                font-size: 14px;
            "
        >
            ← Volver a categorías
        </a>

    </div>


    <div
        style="
            max-width: 800px;
            background: #ffffff;
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            padding: 32px;
            box-shadow: var(--admin-shadow);
        "
    >

        <h2
            style="
                margin-top: 0;
            "
        >
            Nueva categoría
        </h2>


        <p
            style="
                color: var(--admin-text-muted);
                margin-bottom: 30px;
            "
        >
            Crea una categoría para organizar
            los artículos del blog.
        </p>


        <form
            method="POST"
            action="/incuyo/cyberblog/public/admin/categories"
        >

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(
                    $csrfToken,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >


            <div
                style="
                    margin-bottom: 22px;
                "
            >

                <label
                    for="nombre"
                >
                    Nombre
                </label>

                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?= htmlspecialchars(
                        $oldInput['nombre'] ?? '',
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>"
                    maxlength="100"
                    required
                    autofocus
                >

            </div>


            <div
                style="
                    margin-bottom: 28px;
                "
            >

                <label
                    for="descripcion"
                >
                    Descripción
                </label>

                <textarea
                    id="descripcion"
                    name="descripcion"
                    rows="6"
                ><?= htmlspecialchars(
                    $oldInput['descripcion'] ?? '',
                    ENT_QUOTES,
                    'UTF-8'
                ) ?></textarea>

            </div>


            <div
                style="
                    display: flex;
                    gap: 12px;
                    align-items: center;
                "
            >

                <button
                    type="submit"
                    class="admin-button"
                >
                    Crear categoría
                </button>


                <a
                    href="/incuyo/cyberblog/public/admin/categories"
                    style="
                        color: var(--admin-text-muted);
                        font-size: 14px;
                    "
                >
                    Cancelar
                </a>

            </div>

        </form>

    </div>

</section>