<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\RoleMiddleware;
use App\Models\User;

class UserController extends Controller
{
    public function index(): void
    {
        // Solo los administradores pueden administrar usuarios.
        RoleMiddleware::handle(['admin']);

        // Creamos el modelo User.
        $userModel = new User();

        // Obtenemos todos los usuarios desde MySQL.
        $users = $userModel->getAllUsers();

        // Enviamos los usuarios a la vista.
        $this->adminView('users/index', [
            'title' => 'Administración de Usuarios',
            'users' => $users
        ]);
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

        // Buscamos el usuario mediante su ID.
        $user = $userModel->find($id);

        // Comprobamos si el usuario existe.
        if ($user === false) {

            http_response_code(404);

            die('Usuario no encontrado');
        }

        // Enviamos los datos del usuario a la vista.
        $this->adminView('users/edit', [
            'title' => 'Editar usuario',
            'user' => $user
        ]);
    }


        /**
     * Actualiza los datos de un usuario.
     */
    public function update(int $id): void
    {
        // Solo los administradores pueden modificar usuarios.
        RoleMiddleware::handle(['admin']);

        // Creamos el modelo User.
        $userModel = new User();

        // Recibimos los datos enviados desde el formulario.
        $data = [
            'nombre'   => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'email'    => $_POST['email'],
            'rol'      => $_POST['rol'],
            'estado'   => $_POST['estado']
        ];

                // Si estamos modificando nuestro propio usuario,
        // no permitimos cambiar el rol.
        if ($id === $_SESSION['usuario_id']) {

            $data['rol'] = $_SESSION['usuario_rol'];
        }

        // Actualizamos el usuario en la base de datos.
        $userModel->update($id, $data);

        // Volvemos al listado de usuarios.
        header('Location: /incuyo/cyberblog/public/admin/users');

        exit;
    }


}