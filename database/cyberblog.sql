-- ============================================================
-- CYBERBLOG
-- ============================================================
-- Archivo de inicialización de la base de datos.
--
-- Objetivos:
--   1. Crear las tablas principales de CyberBlog.
--   2. Definir los índices para optimizar las consultas.
--   3. Establecer las relaciones entre las tablas.
--   4. Crear las tablas de seguimiento de artículos.
--   5. Crear el sistema interno de notificaciones.
--
-- IMPORTANTE:
--   Este archivo está pensado para una instalación desde cero.
--   No debe importarse sobre una base que ya contiene estas
--   tablas, porque las instrucciones CREATE TABLE fallarían.
--
-- Las operaciones de creación de tablas, índices y relaciones
-- se organizan en secciones para facilitar el mantenimiento.
-- ============================================================


-- ============================================================
-- CONFIGURACIÓN INICIAL
-- ============================================================

-- Evita que MySQL/MariaDB modifique automáticamente ciertos
-- valores AUTO_INCREMENT cuando se insertan valores explícitos.
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- Nota: las sentencias DDL siguientes pueden provocar COMMIT implícito.
-- START TRANSACTION no hace que todo este archivo sea atómico.

-- Establece la zona horaria de la sesión en UTC.
-- Esto ayuda a mantener consistentes los valores de fecha y hora.
SET time_zone = "+00:00";

-- Guarda temporalmente la configuración de caracteres actual.
-- Al finalizar el archivo se restaura la configuración original.
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

-- Utiliza UTF-8 de cuatro bytes para admitir caracteres Unicode,
-- incluidos emojis y caracteres de distintos idiomas.
 /*!40101 SET NAMES utf8mb4 */;


-- ============================================================
-- 1. TABLA: articulos
-- ============================================================
-- Almacena los artículos publicados y los borradores.
--
-- Relaciones:
--   usuario_id   -> usuario que creó el artículo.
--   categoria_id -> categoría a la que pertenece.
--   serie_id     -> serie opcional a la que pertenece.
--
-- El campo slug identifica al artículo en las URL públicas.
-- El estado permite distinguir entre borradores y publicaciones.
-- ============================================================

CREATE TABLE `articulos` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `categoria_id` int(10) UNSIGNED NOT NULL,
  `serie_id` int(10) UNSIGNED DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `resumen` text DEFAULT NULL,
  `contenido` longtext NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` enum('borrador','publicado') DEFAULT 'publicado',
  `vistas` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 2. TABLA: articulo_etiqueta
-- ============================================================
-- Tabla intermedia para la relación muchos a muchos entre
-- artículos y etiquetas.
--
-- Un artículo puede tener varias etiquetas y una etiqueta
-- puede estar asociada a varios artículos.
--
-- La clave primaria compuesta impide repetir la misma relación.
-- Si se elimina un artículo o una etiqueta, se elimina también
-- la relación correspondiente, pero no otros artículos ni otras
-- etiquetas.
-- ============================================================

CREATE TABLE `articulo_etiqueta` (
  `articulo_id` int(10) UNSIGNED NOT NULL,
  `etiqueta_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 3. TABLA: categorias
-- ============================================================
-- Almacena las categorías disponibles en CyberBlog.
--
-- El nombre y el slug deben ser únicos.
-- El slug se utiliza para identificar la categoría en la URL.
-- ============================================================

CREATE TABLE `categorias` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 4. TABLA: comentarios
-- ============================================================
-- Almacena los comentarios y las respuestas de los artículos.
--
-- articulo_id:
--   Identifica el artículo al que pertenece el comentario.
--
-- usuario_id:
--   Identifica al usuario registrado que escribió el comentario.
--   Es nullable para permitir comentarios sin usuario asociado.
--
-- comentario_padre_id:
--   Permite construir conversaciones anidadas.
--   NULL significa que el comentario no es una respuesta directa
--   a otro comentario.
--
-- estado:
--   pendiente: espera moderación.
--   aprobado: puede mostrarse públicamente.
--
-- Las notificaciones por respuestas se generarán únicamente
-- después de que el comentario haya sido aprobado.
-- ============================================================

CREATE TABLE `comentarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `articulo_id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED DEFAULT NULL,
  `comentario_padre_id` int(10) UNSIGNED DEFAULT NULL,
  `autor` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `comentario` text NOT NULL,
  `estado` enum('pendiente','aprobado') DEFAULT 'pendiente',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 5. TABLA: etiquetas
-- ============================================================
-- Almacena las etiquetas que pueden asignarse a los artículos.
--
-- Tanto el nombre como el slug son únicos.
-- ============================================================

CREATE TABLE `etiquetas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 6. TABLA: series
-- ============================================================
-- Almacena las series de artículos.
--
-- Una serie puede contener varios artículos.
-- Los artículos pueden no pertenecer a ninguna serie.
--
-- estado:
--   borrador: la serie todavía no está publicada.
--   publicada: la serie está disponible públicamente.
-- ============================================================

CREATE TABLE `series` (
  `id` int(10) UNSIGNED NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` enum('borrador','publicada') NOT NULL DEFAULT 'borrador',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 7. TABLA: usuarios
-- ============================================================
-- Almacena las cuentas de usuario de CyberBlog.
--
-- rol:
--   admin   -> administrador.
--   editor  -> editor de contenido.
--   usuario -> usuario estándar.
--
-- estado:
--   1 -> cuenta activa.
--   0 -> cuenta deshabilitada.
--
-- Las contraseñas deben almacenarse mediante un hash seguro,
-- nunca como contraseñas en texto plano.
-- ============================================================

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `rol` enum('admin','editor','usuario') DEFAULT 'usuario',
  `estado` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 8. TABLA: password_resets
-- ============================================================
-- Almacena las solicitudes de recuperación de contraseña.
--
-- token_hash:
--   Hash del token de recuperación, no el token original.
--
-- attempts:
--   Cantidad de intentos registrados para el proceso.
--
-- expires_at:
--   Fecha y hora de vencimiento del token.
--
-- La clave foránea elimina las solicitudes de recuperación
-- cuando se elimina el usuario correspondiente.
-- ============================================================

CREATE TABLE `password_resets` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `token_hash` char(64) NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 9. TABLA: articulo_seguidores
-- ============================================================
-- Registra qué usuarios decidieron seguir determinados artículos.
--
-- El seguimiento es explícito:
--   - Comentar un artículo NO significa seguirlo.
--   - El usuario debe pulsar "Seguir artículo".
--   - Puede dejar de seguirlo cuando quiera.
--
-- usuario_id:
--   Usuario que sigue el artículo.
--
-- articulo_id:
--   Artículo seguido.
--
-- created_at:
--   Fecha en la que comenzó el seguimiento.
--
-- uq_articulo_seguidores_usuario_articulo:
--   Impide que un usuario siga dos veces el mismo artículo.
--
-- idx_articulo_seguidores_articulo_id:
--   Facilita encontrar todos los usuarios que siguen un artículo.
--
-- ON DELETE CASCADE:
--   Si se elimina el usuario o el artículo, se elimina la relación
--   de seguimiento correspondiente.
--
-- Las notificaciones históricas se almacenan en otra tabla,
-- por lo que dejar de seguir un artículo no las elimina.
-- ============================================================

CREATE TABLE `articulo_seguidores` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `articulo_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),

  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_articulo_seguidores_usuario_articulo` (`usuario_id`, `articulo_id`),
  KEY `idx_articulo_seguidores_articulo_id` (`articulo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 10. TABLA: notificaciones
-- ============================================================
-- Almacena las notificaciones internas que recibe cada usuario.
--
-- Las notificaciones se mostrarán mediante la campana del sitio.
-- No se envían por correo electrónico.
--
-- usuario_id:
--   Usuario destinatario de la notificación.
--
-- tipo:
--   Identifica el tipo de evento que originó la notificación.
--   Los valores concretos se definirán en el código PHP.
--
-- articulo_id:
--   Artículo relacionado con el evento.
--   Puede ser NULL si el artículo se elimina.
--
-- comentario_id:
--   Comentario relacionado con el evento.
--   Puede ser NULL si el comentario se elimina.
--
-- actor_usuario_id:
--   Usuario que originó la notificación.
--   Puede ser NULL si se elimina esa cuenta.
--
-- mensaje:
--   Texto que se mostrará en el centro de notificaciones.
--
-- leida:
--   0 -> notificación pendiente de lectura.
--   1 -> notificación leída.
--
-- created_at:
--   Fecha en la que se generó la notificación.
--
-- idx_notificaciones_usuario_leida_fecha:
--   Optimiza la consulta de notificaciones de un usuario,
--   especialmente las que todavía no fueron leídas.
--
-- Las referencias opcionales utilizan ON DELETE SET NULL:
--   La notificación se conserva aunque se elimine el contenido
--   relacionado o el usuario que originó el evento.
--
-- La relación con el destinatario utiliza ON DELETE CASCADE:
--   Si se elimina la cuenta destinataria, se eliminan sus avisos.
-- ============================================================

CREATE TABLE `notificaciones` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `articulo_id` int(10) UNSIGNED DEFAULT NULL,
  `comentario_id` int(10) UNSIGNED DEFAULT NULL,
  `actor_usuario_id` int(10) UNSIGNED DEFAULT NULL,
  `mensaje` varchar(255) NOT NULL,
  `leida` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),

  PRIMARY KEY (`id`),
  KEY `idx_notificaciones_usuario_leida_fecha` (`usuario_id`, `leida`, `created_at`),
  KEY `idx_notificaciones_articulo_id` (`articulo_id`),
  KEY `idx_notificaciones_comentario_id` (`comentario_id`),
  KEY `idx_notificaciones_actor_usuario_id` (`actor_usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================================
-- 11. ÍNDICES DE LAS TABLAS EXISTENTES
-- ============================================================
-- Los índices aceleran las búsquedas y las relaciones.
--
-- Las claves primarias identifican de forma única cada registro.
-- Los índices UNIQUE impiden duplicar valores que deben ser
-- únicos, como el slug de un artículo o el correo de un usuario.
--
-- Las tablas nuevas ya declaran sus índices en CREATE TABLE.
-- Por eso no es necesario volver a agregarlos aquí.
-- ============================================================

ALTER TABLE `articulos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_articulo_usuario` (`usuario_id`),
  ADD KEY `fk_articulo_categoria` (`categoria_id`),
  ADD KEY `idx_articulos_serie_id` (`serie_id`);

ALTER TABLE `articulo_etiqueta`
  ADD PRIMARY KEY (`articulo_id`,`etiqueta_id`),
  ADD KEY `fk_rel_etiqueta` (`etiqueta_id`);

ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_comentario_articulo` (`articulo_id`),
  ADD KEY `fk_comentario_usuario` (`usuario_id`),
  ADD KEY `idx_comentario_padre_id` (`comentario_padre_id`);

ALTER TABLE `etiquetas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`),
  ADD UNIQUE KEY `slug` (`slug`);

ALTER TABLE `series`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token_hash` (`token_hash`),
  ADD KEY `idx_password_resets_usuario_id` (`usuario_id`),
  ADD KEY `idx_password_resets_expires_at` (`expires_at`);


-- ============================================================
-- 12. CONFIGURACIÓN AUTO_INCREMENT
-- ============================================================
-- Permite que la base genere automáticamente los identificadores
-- de los nuevos registros.
--
-- No se fijan aquí los valores AUTO_INCREMENT originales del
-- volcado anterior, ya que este archivo representa una instalación
-- limpia sin datos de producción.
-- ============================================================

ALTER TABLE `articulos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `categorias`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `comentarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `etiquetas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `series`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `password_resets`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;


-- ============================================================
-- 13. CLAVES FORÁNEAS DE LAS TABLAS EXISTENTES
-- ============================================================
-- Las claves foráneas mantienen la integridad referencial.
--
-- ON DELETE CASCADE:
--   Elimina relaciones dependientes cuando se elimina el registro
--   principal.
--
-- ON DELETE SET NULL:
--   Conserva el registro dependiente, pero deja en NULL la
--   referencia al registro eliminado.
-- ============================================================

-- Un artículo debe tener un usuario y una categoría existentes.
-- Si se elimina la serie, el artículo permanece sin serie.

ALTER TABLE `articulos`
  ADD CONSTRAINT `fk_articulo_categoria`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `categorias` (`id`),

  ADD CONSTRAINT `fk_articulo_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `usuarios` (`id`),

  ADD CONSTRAINT `fk_articulos_series`
    FOREIGN KEY (`serie_id`)
    REFERENCES `series` (`id`)
    ON DELETE SET NULL;


-- Las relaciones artículo-etiqueta desaparecen si se elimina
-- cualquiera de los dos registros relacionados.

ALTER TABLE `articulo_etiqueta`
  ADD CONSTRAINT `fk_rel_articulo`
    FOREIGN KEY (`articulo_id`)
    REFERENCES `articulos` (`id`)
    ON DELETE CASCADE,

  ADD CONSTRAINT `fk_rel_etiqueta`
    FOREIGN KEY (`etiqueta_id`)
    REFERENCES `etiquetas` (`id`)
    ON DELETE CASCADE;


-- Los comentarios pertenecen a un artículo.
-- Los comentarios pueden estar asociados a un usuario.
-- Si se elimina el comentario padre, la respuesta conserva
-- su registro y queda sin padre.

ALTER TABLE `comentarios`
  ADD CONSTRAINT `fk_comentario_articulo`
    FOREIGN KEY (`articulo_id`)
    REFERENCES `articulos` (`id`),

  ADD CONSTRAINT `fk_comentario_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `usuarios` (`id`),

  ADD CONSTRAINT `fk_comentario_padre`
    FOREIGN KEY (`comentario_padre_id`)
    REFERENCES `comentarios` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE;


-- Si se elimina un usuario, se eliminan sus solicitudes
-- de recuperación de contraseña.

ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk_password_resets_usuario`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE;


-- ============================================================
-- 14. CLAVES FORÁNEAS DE SEGUIMIENTO Y NOTIFICACIONES
-- ============================================================

ALTER TABLE `articulo_seguidores`
  ADD CONSTRAINT `fk_articulo_seguidores_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_articulo_seguidores_articulo`
    FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `notificaciones`
  ADD CONSTRAINT `fk_notificaciones_usuario`
    FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notificaciones_articulo`
    FOREIGN KEY (`articulo_id`) REFERENCES `articulos` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notificaciones_comentario`
    FOREIGN KEY (`comentario_id`) REFERENCES `comentarios` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notificaciones_actor_usuario`
    FOREIGN KEY (`actor_usuario_id`) REFERENCES `usuarios` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE;


-- ============================================================
-- FIN DE LA DEFINICIÓN DEL ESQUEMA
-- ============================================================

COMMIT;


-- Restaura la configuración original de caracteres.
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;