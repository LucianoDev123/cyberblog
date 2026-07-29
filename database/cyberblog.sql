DROP DATABASE IF EXISTS cyberblog;

CREATE DATABASE cyberblog
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE cyberblog;

-- ===========================
-- TABLA: usuarios
-- ===========================

CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    rol ENUM('admin','editor','usuario') DEFAULT 'usuario',
    estado TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- ===========================
-- TABLA: categorias
-- ===========================

CREATE TABLE categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE,
    descripcion TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- ===========================
-- TABLA: articulos
-- ===========================

CREATE TABLE articulos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    usuario_id INT UNSIGNED NOT NULL,
    categoria_id INT UNSIGNED NOT NULL,

    titulo VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    resumen TEXT,
    contenido LONGTEXT NOT NULL,
    imagen VARCHAR(255) DEFAULT NULL,

    estado ENUM('borrador','publicado') DEFAULT 'publicado',

    vistas INT DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_articulo_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id),

    CONSTRAINT fk_articulo_categoria
        FOREIGN KEY (categoria_id)
        REFERENCES categorias(id)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- ===========================
-- TABLA: etiquetas
-- ===========================

CREATE TABLE etiquetas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(120) NOT NULL UNIQUE
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- ===========================
-- TABLA: articulo_etiqueta
-- ===========================

CREATE TABLE articulo_etiqueta (

    articulo_id INT UNSIGNED NOT NULL,
    etiqueta_id INT UNSIGNED NOT NULL,

    PRIMARY KEY (articulo_id, etiqueta_id),

    CONSTRAINT fk_rel_articulo
        FOREIGN KEY (articulo_id)
        REFERENCES articulos(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_rel_etiqueta
        FOREIGN KEY (etiqueta_id)
        REFERENCES etiquetas(id)
        ON DELETE CASCADE

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;

-- ===========================
-- TABLA: comentarios
-- ===========================

CREATE TABLE comentarios (

    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    articulo_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NULL,

    autor VARCHAR(100),
    email VARCHAR(150),

    comentario TEXT NOT NULL,

    estado ENUM('pendiente','aprobado')
        DEFAULT 'pendiente',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_comentario_articulo
        FOREIGN KEY (articulo_id)
        REFERENCES articulos(id),

    CONSTRAINT fk_comentario_usuario
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)

) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;