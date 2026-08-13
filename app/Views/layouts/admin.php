<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title><?= $title ?? 'Panel de Administración' ?></title>

</head>

<body>

<header>

    <h1>CyberBlog - Administración</h1>

    <hr>

    <nav>

        <a href="/incuyo/cyberblog/public/admin">Dashboard</a> |

        <a href="/incuyo/cyberblog/public/admin/articles">Artículos</a> |

        <a href="/incuyo/cyberblog/public/admin/categories">Categorías</a> |

        <a href="/incuyo/cyberblog/public/admin/users">Usuarios</a> |

        <a href="/incuyo/cyberblog/public/">Ver Blog</a>

        <a href="/incuyo/cyberblog/public/logout">Cerrar sesión</a>

    </nav>

    <hr>

</header>

<main>

    <?= $content ?>

</main>

<hr>

<footer>

    © <?= date('Y') ?> CyberBlog

</footer>

</body>

</html>