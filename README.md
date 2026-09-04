# CyberBlog

CyberBlog es una aplicación web de publicación de contenidos desarrollada en PHP utilizando una arquitectura MVC propia, sin frameworks externos. El proyecto permite administrar artículos, categorías, etiquetas, series y usuarios desde un panel administrativo, además de ofrecer una interfaz pública para consultar y buscar contenido.

## Características principales

### Área pública

- Página de inicio.
- Listado de artículos publicados.
- Visualización individual de artículos.
- Listado público de series.
- Visualización de artículos asociados a una serie.
- Buscador de artículos.
- Paginación de resultados.
- Interfaz responsive.
- Diseño visual con temática tecnológica/ciberseguridad.

### Panel administrativo

- Inicio de sesión.
- Control de acceso mediante roles.
- Gestión de usuarios.
- Alta, modificación y eliminación de categorías.
- Alta, modificación y eliminación de artículos.
- Gestión de etiquetas.
- Gestión de series.
- Publicación y administración del estado de los contenidos.
- Validación de formularios.
- Protección CSRF en operaciones sensibles.

## Tecnologías utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Apache
- XAMPP
- Git

## Arquitectura

El proyecto utiliza una arquitectura MVC propia para separar las responsabilidades de la aplicación:

```text
app/
├── Config/
├── Controllers/
├── Core/
├── Middleware/
├── Models/
└── Views/

public/
└── assets/
    └── css/

routes/
└── web.php
```

### Componentes principales

- **Models:** interacción con la base de datos y acceso a los datos.
- **Views:** presentación visual de la aplicación.
- **Controllers:** reciben las solicitudes y coordinan la lógica.
- **Core:** componentes centrales de la aplicación.
- **Middleware:** control de acceso y validaciones transversales.
- **Config:** configuración de la aplicación.
- **Routes:** definición de las rutas disponibles.
- **Public:** punto de entrada público y recursos estáticos.

## Requisitos

Para ejecutar el proyecto localmente se necesita:

- XAMPP o un entorno equivalente.
- Apache habilitado.
- MySQL habilitado.
- PHP compatible con la versión utilizada por el proyecto.
- Git, si se desea clonar el repositorio.

## Instalación local

### 1. Clonar el repositorio

```bash
git clone URL_DEL_REPOSITORIO
```

### 2. Copiar el proyecto

Ubicar la carpeta del proyecto dentro del directorio público de Apache. En XAMPP para Windows, una ubicación habitual es:

```text
C:\xampp\htdocs\incuyo\cyberblog
```

### 3. Crear la base de datos

Crear una base de datos MySQL para CyberBlog e importar el script SQL del proyecto, si se encuentra incluido en el repositorio.

> El nombre de la base de datos, las credenciales y los parámetros de conexión deben configurarse de acuerdo con el entorno local.

### 4. Configurar la conexión

Revisar los archivos de configuración ubicados en:

```text
app/Config/
```

Completar los valores correspondientes al servidor MySQL, nombre de la base de datos, usuario y contraseña.

### 5. Iniciar los servicios

Desde el panel de XAMPP, iniciar:

- Apache
- MySQL

### 6. Abrir la aplicación

Acceder desde el navegador a:

```text
http://localhost/incuyo/cyberblog/public/
```

## Rutas principales

```text
/                         Página de inicio
/blog                     Listado público de artículos
/blog/search              Buscador de artículos
/series                   Listado público de series
```

Las rutas administrativas se encuentran definidas en:

```text
routes/web.php
```

## Seguridad

Durante el desarrollo se incorporaron mecanismos y buenas prácticas orientadas a reducir riesgos comunes en aplicaciones web:

- Separación de responsabilidades mediante MVC.
- Control de acceso basado en roles.
- Protección CSRF en formularios sensibles.
- Validación de datos recibidos.
- Uso de consultas preparadas para el acceso a datos.
- Gestión de sesiones y autenticación.
- Restricción de operaciones administrativas.
- Manejo controlado de errores.
- Organización del código para facilitar mantenimiento y auditoría.

## Usuario de prueba

Para realizar pruebas funcionales puede utilizarse el usuario administrativo configurado en el entorno de desarrollo:

```text
Usuario: LucianoDev123
```

> La contraseña no se incluye en este README. Debe utilizarse la credencial definida en la base de datos o configurada para el entorno de pruebas.

## Estado del proyecto

Proyecto finalizado y preparado para su entrega.

## Autor

Luciano Lobos

## Licencia

Este proyecto fue desarrollado con fines educativos y de portfolio. No se ha definido una licencia de distribución específica.
