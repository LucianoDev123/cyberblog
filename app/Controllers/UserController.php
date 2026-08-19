<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\RoleMiddleware;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Muestra el listado de usuarios.
     */
    public function index(): void
    {
        // Solo los administradores pueden administrar usuarios.
        RoleMiddleware::handle(['admin']);

        // Creamos el modelo User.
        $userModel = new User();

        // Obtenemos todos los usuarios.
        $users = $userModel->getAllUsers();

        // Enviamos los usuarios a la vista.
        $this->adminView('users/index', [
            'title' => 'Administración de Usuarios',
            'users' => $users
        ]);
    }


    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create(): void
    {
        // Solo los administradores pueden crear usuarios.
        RoleMiddleware::handle(['admin']);

        // Recuperamos los datos anteriores del formulario.
        $oldInput = $this->getOldInput();

        // Cargamos la vista.
        $this->adminView('users/create', [
            'title' => 'Nuevo usuario',
            'oldInput' => $oldInput
        ]);
    }


    /**
     * Recibe el formulario y crea el nuevo usuario.
     */
    public function store(): void
    {
        // Solo los administradores pueden crear usuarios.
        RoleMiddleware::handle(['admin']);

        // Validamos el token CSRF antes de procesar el formulario.
        $this->verifyCsrfToken();

        // ---------------------------------------------------------
        // OBTENER DATOS
        // ---------------------------------------------------------

        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $rol = $_POST['rol'] ?? '';
        $estado = $_POST['estado'] ?? '';

        // Datos que podemos conservar si ocurre un error.
        // Nunca guardamos la contraseña.
        $oldInput = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'username' => $username,
            'email'    => $email,
            'rol'      => $rol,
            'estado'   => $estado
        ];


        // ---------------------------------------------------------
        // CAMPOS OBLIGATORIOS
        // ---------------------------------------------------------

        if (
            $nombre === '' ||
            $apellido === '' ||
            $username === '' ||
            $email === '' ||
            $password === ''
        ) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'Todos los campos obligatorios deben completarse.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        // ---------------------------------------------------------
        // VALIDACIONES
        // ---------------------------------------------------------

        if (strlen($nombre) > 100) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El nombre no puede superar los 100 caracteres.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        if (strlen($apellido) > 100) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El apellido no puede superar los 100 caracteres.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        if (strlen($username) < 3 || strlen($username) > 50) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El username debe tener entre 3 y 50 caracteres.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El email no tiene un formato válido.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        if (strlen($email) > 150) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El email no puede superar los 150 caracteres.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        // ---------------------------------------------------------
        // ROL
        // ---------------------------------------------------------

        $allowedRoles = [
            'admin',
            'editor',
            'usuario'
        ];

        if (!in_array($rol, $allowedRoles, true)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El rol seleccionado no es válido.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        // ---------------------------------------------------------
        // ESTADO
        // ---------------------------------------------------------

        if (!in_array($estado, ['0', '1'], true)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El estado seleccionado no es válido.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        // ---------------------------------------------------------
        // CONTRASEÑA
        // ---------------------------------------------------------

        if (strlen($password) < 8) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'La contraseña debe tener al menos 8 caracteres.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        // ---------------------------------------------------------
        // MODELO
        // ---------------------------------------------------------

        $userModel = new User();


        // ---------------------------------------------------------
        // USERNAME DUPLICADO
        // ---------------------------------------------------------

        if ($userModel->usernameExists($username)) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El username ya está registrado.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        // ---------------------------------------------------------
        // EMAIL DUPLICADO
        // ---------------------------------------------------------

        if ($userModel->emailExists($email)) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'Ya existe un usuario registrado con ese email.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        // ---------------------------------------------------------
        // PASSWORD
        // ---------------------------------------------------------

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );


        // ---------------------------------------------------------
        // CREAR USUARIO
        // ---------------------------------------------------------

        $data = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'username' => $username,
            'email'    => $email,
            'password' => $passwordHash,
            'rol'      => $rol,
            'estado'   => (int) $estado
        ];

        // Intentamos crear el usuario.
        $created = $userModel->create($data);

        // Comprobamos si MySQL confirmó la operación.
        if (!$created) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'No se pudo crear el usuario. Inténtalo nuevamente.'
            );

            header(
                'Location: /incuyo/cyberblog/public/admin/users/create'
            );

            exit;
        }


        // ---------------------------------------------------------
        // ÉXITO
        // ---------------------------------------------------------

        $this->setFlash(
            'success',
            'Usuario creado correctamente.'
        );

        header(
            'Location: /incuyo/cyberblog/public/admin/users'
        );

        exit;
    }


    /**
     * Muestra el formulario para editar un usuario.
     */
    public function edit(int $id): void
    {
        // Solo los administradores pueden editar usuarios.
        RoleMiddleware::handle(['admin']);

        // Creamos el modelo User.
        $userModel = new User();

        // Buscamos el usuario.
        $user = $userModel->find($id);

        // Comprobamos si existe.
        if ($user === false) {

            http_response_code(404);

            die('Usuario no encontrado');
        }

        // Recuperamos los datos anteriores del formulario.
        $oldInput = $this->getOldInput();

        // Cargamos la vista de edición.
        $this->adminView('users/edit', [
            'title' => 'Editar usuario',
            'user' => $user,
            'oldInput' => $oldInput
        ]);
    }


    /**
     * Actualiza los datos de un usuario.
     */
    public function update(int $id): void
    {
        // Solo los administradores pueden modificar usuarios.
        RoleMiddleware::handle(['admin']);

        // Validamos el token CSRF antes de procesar el formulario.
        $this->verifyCsrfToken();


        // ---------------------------------------------------------
        // OBTENER DATOS
        // ---------------------------------------------------------

        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $rol = $_POST['rol'] ?? '';
        $estado = $_POST['estado'] ?? '';


        // Datos que podemos conservar si ocurre un error.
        $oldInput = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'email'    => $email,
            'rol'      => $rol,
            'estado'   => $estado
        ];


        // ---------------------------------------------------------
        // CAMPOS OBLIGATORIOS
        // ---------------------------------------------------------

        if (
            $nombre === '' ||
            $apellido === '' ||
            $email === ''
        ) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'Los campos obligatorios deben completarse.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/users/edit/{$id}"
            );

            exit;
        }


        // ---------------------------------------------------------
        // NOMBRE
        // ---------------------------------------------------------

        if (strlen($nombre) > 100) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El nombre no puede superar los 100 caracteres.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/users/edit/{$id}"
            );

            exit;
        }


        // ---------------------------------------------------------
        // APELLIDO
        // ---------------------------------------------------------

        if (strlen($apellido) > 100) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El apellido no puede superar los 100 caracteres.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/users/edit/{$id}"
            );

            exit;
        }


        // ---------------------------------------------------------
        // EMAIL
        // ---------------------------------------------------------

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El email no tiene un formato válido.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/users/edit/{$id}"
            );

            exit;
        }


        if (strlen($email) > 150) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El email no puede superar los 150 caracteres.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/users/edit/{$id}"
            );

            exit;
        }


        // ---------------------------------------------------------
        // ROL
        // ---------------------------------------------------------

        $allowedRoles = [
            'admin',
            'editor',
            'usuario'
        ];

        if (!in_array($rol, $allowedRoles, true)) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El rol seleccionado no es válido.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/users/edit/{$id}"
            );

            exit;
        }


        // ---------------------------------------------------------
        // ESTADO
        // ---------------------------------------------------------

        if (!in_array($estado, ['0', '1'], true)) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El estado seleccionado no es válido.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/users/edit/{$id}"
            );

            exit;
        }


        // ---------------------------------------------------------
        // MODELO
        // ---------------------------------------------------------

        $userModel = new User();


        // ---------------------------------------------------------
        // EMAIL DUPLICADO
        // ---------------------------------------------------------

        if ($userModel->emailExistsForOtherUser($email, $id)) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'Ese email ya está registrado por otro usuario.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/users/edit/{$id}"
            );

            exit;
        }


        // ---------------------------------------------------------
        // PROTEGER EL PROPIO ROL
        // ---------------------------------------------------------

        if ($id === $_SESSION['usuario_id']) {
            $rol = $_SESSION['usuario_rol'];
        }


        // ---------------------------------------------------------
        // ACTUALIZAR
        // ---------------------------------------------------------

        $data = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'email'    => $email,
            'rol'      => $rol,
            'estado'   => (int) $estado
        ];

        // Intentamos actualizar el usuario.
        $updated = $userModel->update($id, $data);

        // Comprobamos si la operación fue exitosa.
        if (!$updated) {

            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'No se pudo actualizar el usuario. Inténtalo nuevamente.'
            );

            header(
                "Location: /incuyo/cyberblog/public/admin/users/edit/{$id}"
            );

            exit;
        }


        // ---------------------------------------------------------
        // ÉXITO
        // ---------------------------------------------------------

        $this->setFlash(
            'success',
            'Usuario actualizado correctamente.'
        );

        header(
            'Location: /incuyo/cyberblog/public/admin/users'
        );

        exit;
    }
}