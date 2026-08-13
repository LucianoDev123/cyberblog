<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function login(): void
    {
        // Cargamos la vista del formulario de login.
        $this->view('auth/login', [
            'title' => 'Iniciar sesión'
        ]);
    }

    /**
     * Recibe las credenciales y verifica la contraseña.
     */
    public function authenticate(): void
    {
        // Obtenemos el correo enviado desde el formulario.
        $email = $_POST['email'];

        // Obtenemos la contraseña que escribió el usuario.
        $password = $_POST['password'];

        // Creamos un objeto del modelo User.
        $userModel = new User();

        // Buscamos el usuario en la base de datos mediante su email.
        $user = $userModel->findByEmail($email);

        // Comprobamos si el usuario existe.
        if ($user === false) {

            // Si no existe, rechazamos el login.
            die('Usuario o contraseña incorrectos.');
        }

        // Comprobamos si la contraseña coincide con el hash almacenado.
        if (!password_verify($password, $user['password'])) {

            // Si no coincide, rechazamos el login.
            die('Usuario o contraseña incorrectos.');
        }

        // Generamos un nuevo ID de sesión después de autenticar al usuario.
        session_regenerate_id(true);

        // Guardamos el ID del usuario en la sesión.
        $_SESSION['usuario_id'] = $user['id'];

        // Guardamos el nombre del usuario en la sesión.
        $_SESSION['usuario_nombre'] = $user['nombre'];

        // Guardamos el rol del usuario en la sesión.
        $_SESSION['usuario_rol'] = $user['rol'];

        // Redirigimos al usuario al panel de administración.
        header('Location: /incuyo/cyberblog/public/admin');

        // Detenemos la ejecución después de la redirección.
        exit;
        

    }

        /**
     * Cierra la sesión del usuario.
     */
    public function logout(): void
    {
        // Eliminamos todas las variables almacenadas en la sesión.
        $_SESSION = [];

        // Destruimos la sesión actual.
        session_destroy();

        // Redirigimos al usuario al formulario de login.
        header('Location: /incuyo/cyberblog/public/login');

        // Detenemos la ejecución después de la redirección.
        exit;
    }


}