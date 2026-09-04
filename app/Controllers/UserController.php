<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Pagination;
use App\Middleware\RoleMiddleware;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Muestra el listado paginado de usuarios.
     *
     * Solamente los administradores pueden acceder a esta sección.
     * Se muestran 15 usuarios por página para evitar cargar todos
     * los registros de la base de datos al mismo tiempo.
     */
    public function index(): void
    {
        // Verificamos que el usuario actual tenga permisos de administrador.
        RoleMiddleware::handle(['admin']);

        $userModel = new User();

        // Definimos la cantidad máxima de usuarios por página.
        $perPage = 15;

        /*
         * Obtenemos el número de página enviado por la URL.
         *
         * Si no se envía una página válida, comenzamos desde la página 1.
         */
        $currentPage = filter_input(
            INPUT_GET,
            'page',
            FILTER_VALIDATE_INT
        );

        if (
            $currentPage === false ||
            $currentPage === null ||
            $currentPage < 1
        ) {
            $currentPage = 1;
        }

        // Obtenemos la cantidad total de usuarios para calcular las páginas.
        $totalUsers = $userModel->countAllUsers();

        // Creamos el objeto encargado de calcular la paginación.
        $pagination = new Pagination(
            $totalUsers,
            $currentPage,
            $perPage
        );

        /*
         * Obtenemos solamente los usuarios correspondientes
         * a la página actual.
         */
        $users = $userModel->getPaginatedUsers(
            $pagination->getPerPage(),
            $pagination->getOffset()
        );

        // Enviamos los usuarios y la información de paginación a la vista.
        $this->adminView('users/index', [
            'title'      => 'Administración de Usuarios',
            'users'      => $users,
            'pagination' => $pagination
        ]);
    }


    /**
     * Muestra el formulario para crear un usuario.
     */
    public function create(): void
    {
        // Verificamos que el usuario actual tenga permisos de administrador.
        RoleMiddleware::handle(['admin']);

        // Mostramos el formulario de creación.
        $this->adminView('users/create', [
            'title' => 'Nuevo usuario'
        ]);
    }


    /**
     * Guarda un nuevo usuario.
     */
    public function store(): void
    {
        // Verificamos que el usuario actual tenga permisos de administrador.
        RoleMiddleware::handle(['admin']);

        // Validamos el token CSRF enviado por el formulario.
        $this->verifyCsrfToken();

        $userModel = new User();

        // Obtenemos los datos enviados mediante POST.
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol = trim($_POST['rol'] ?? '');
        $estado = trim($_POST['estado'] ?? '');

        // Conservamos los datos para poder mostrarlos nuevamente si hay errores.
        $oldInput = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'username' => $username,
            'email'    => $email,
            'rol'      => $rol,
            'estado'   => $estado
        ];

        // Validamos que los campos obligatorios no estén vacíos.
        if (
            $nombre === '' ||
            $apellido === '' ||
            $username === '' ||
            $email === '' ||
            $password === '' ||
            $rol === '' ||
            $estado === ''
        ) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'Todos los campos son obligatorios.'
            );

            header('Location: /incuyo/cyberblog/public/admin/users/create');
            exit;
        }

        // Validamos que el correo tenga un formato válido.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El correo electrónico no tiene un formato válido.'
            );

            header('Location: /incuyo/cyberblog/public/admin/users/create');
            exit;
        }

        // Evitamos registrar dos usuarios con el mismo correo.
        if ($userModel->emailExists($email)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El correo electrónico ya está registrado.'
            );

            header('Location: /incuyo/cyberblog/public/admin/users/create');
            exit;
        }

        // Evitamos registrar dos usuarios con el mismo nombre de usuario.
        if ($userModel->usernameExists($username)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El nombre de usuario ya está registrado.'
            );

            header('Location: /incuyo/cyberblog/public/admin/users/create');
            exit;
        }

        // Convertimos la contraseña en un hash seguro antes de guardarla.
        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $created = $userModel->create([
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'username' => $username,
            'email'    => $email,
            'password' => $hashedPassword,
            'rol'      => $rol,
            'estado'   => $estado
        ]);

        if (!$created) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'No se pudo crear el usuario.'
            );

            header('Location: /incuyo/cyberblog/public/admin/users/create');
            exit;
        }

        // Informamos que la creación fue exitosa.
        $this->setFlash(
            'success',
            'Usuario creado correctamente.'
        );

        header('Location: /incuyo/cyberblog/public/admin/users');
        exit;
    }


    /**
     * Muestra el formulario de edición de un usuario.
     */
    public function edit(int $id): void
    {
        // Verificamos que el usuario actual tenga permisos de administrador.
        RoleMiddleware::handle(['admin']);

        $userModel = new User();

        // Buscamos el usuario que se desea editar.
        $user = $userModel->findById($id);

        // Si el usuario no existe, volvemos al listado.
        if (!$user) {
            $this->setFlash(
                'error',
                'El usuario no existe.'
            );

            header('Location: /incuyo/cyberblog/public/admin/users');
            exit;
        }

        // Mostramos el formulario con los datos actuales del usuario.
        $this->adminView('users/edit', [
            'title' => 'Editar usuario',
            'user'  => $user
        ]);
    }


    /**
     * Actualiza los datos de un usuario.
     */
    public function update(int $id): void
    {
        // Verificamos que el usuario actual tenga permisos de administrador.
        RoleMiddleware::handle(['admin']);

        // Validamos el token CSRF enviado por el formulario.
        $this->verifyCsrfToken();

        $userModel = new User();

        // Buscamos el usuario antes de actualizarlo.
        $user = $userModel->findById($id);

        if (!$user) {
            $this->setFlash(
                'error',
                'El usuario no existe.'
            );

            header('Location: /incuyo/cyberblog/public/admin/users');
            exit;
        }

        // Obtenemos los datos enviados mediante POST.
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $rol = trim($_POST['rol'] ?? '');
        $estado = trim($_POST['estado'] ?? '');

        // Conservamos los datos para poder mostrarlos nuevamente si hay errores.
        $oldInput = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'email'    => $email,
            'rol'      => $rol,
            'estado'   => $estado
        ];

        // Validamos que los campos obligatorios no estén vacíos.
        if (
            $nombre === '' ||
            $apellido === '' ||
            $email === '' ||
            $rol === '' ||
            $estado === ''
        ) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'Todos los campos son obligatorios.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/edit/' . $id
            );

            exit;
        }

        // Validamos que el correo tenga un formato válido.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El correo electrónico no tiene un formato válido.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/edit/' . $id
            );

            exit;
        }

        /*
         * Verificamos que el correo no pertenezca a otro usuario.
         *
         * Se excluye el ID actual porque el usuario puede conservar
         * su propio correo electrónico.
         */
        if ($userModel->emailExistsForOtherUser($email, $id)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El correo electrónico ya está registrado por otro usuario.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/edit/' . $id
            );

            exit;
        }

        /*
         * Si el usuario está editando su propia cuenta y cambia su rol,
         * conservamos el rol administrativo en la sesión actual.
         *
         * Esto evita perder los permisos durante la sesión activa.
         */
        $currentUserId = (int) ($_SESSION['usuario_id'] ?? 0);

        if ($id === $currentUserId) {
            $_SESSION['usuario_rol'] = $rol;
        }

        $updated = $userModel->update($id, [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'email'    => $email,
            'rol'      => $rol,
            'estado'   => $estado
        ]);

        if (!$updated) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'No se pudieron actualizar los datos del usuario.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/edit/' . $id
            );

            exit;
        }

        // Informamos que la actualización fue exitosa.
        $this->setFlash(
            'success',
            'Usuario actualizado correctamente.'
        );

        header('Location: /incuyo/cyberblog/public/admin/users');
        exit;
    }


    /**
     * Elimina un usuario.
     *
     * Este método solamente puede ser ejecutado por un administrador.
     * También evita eliminar la propia cuenta y el último administrador.
     */
    public function delete(int $id): void
    {
        // Verificamos que el usuario actual tenga permisos de administrador.
        RoleMiddleware::handle(['admin']);

        // Validamos el token CSRF para evitar solicitudes maliciosas.
        $this->verifyCsrfToken();

        // Obtenemos el ID del usuario actualmente autenticado.
        $currentUserId = (int) ($_SESSION['usuario_id'] ?? 0);

        // Impedimos que un administrador elimine su propia cuenta.
        if ($id === $currentUserId) {
            $this->setFlash(
                'error',
                'No podés eliminar tu propia cuenta.'
            );

            header('Location: /incuyo/cyberblog/public/admin/users');
            exit;
        }

        $userModel = new User();

        // Buscamos el usuario antes de intentar eliminarlo.
        $user = $userModel->findById($id);

        // Si el usuario no existe, mostramos un mensaje y volvemos al listado.
        if (!$user) {
            $this->setFlash(
                'error',
                'El usuario no existe.'
            );

            header('Location: /incuyo/cyberblog/public/admin/users');
            exit;
        }

        /*
         * Si el usuario que se intenta eliminar es administrador,
         * comprobamos que todavía exista al menos otro administrador.
         *
         * Esto evita dejar el sistema sin ninguna cuenta administrativa.
         */
        if ($user['rol'] === 'admin') {
            $totalAdmins = $userModel->countAdmins();

            if ($totalAdmins <= 1) {
                $this->setFlash(
                    'error',
                    'No se puede eliminar el último administrador.'
                );

                header('Location: /incuyo/cyberblog/public/admin/users');
                exit;
            }
        }

        // Ejecutamos la eliminación después de superar todas las validaciones.
        $deleted = $userModel->delete($id);

        // Informamos al usuario si la operación fue exitosa o falló.
        if ($deleted) {
            $this->setFlash(
                'success',
                'Usuario eliminado correctamente.'
            );
        } else {
            $this->setFlash(
                'error',
                'No se pudo eliminar el usuario.'
            );
        }

        // Volvemos al listado después de finalizar la operación.
        header('Location: /incuyo/cyberblog/public/admin/users');
        exit;
    }
}