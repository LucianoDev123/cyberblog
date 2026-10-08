<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;
use App\Models\User;

class AccountController extends Controller
{
    public function index(): void
    {
        AuthMiddleware::handle();

        $userId = (int) $_SESSION['usuario_id'];

        $userModel = new User();
        $user = $userModel->findById($userId);

        if ($user === false) {
            $_SESSION = [];
            session_destroy();

            header('Location: /login');
            exit;
        }

        if ((int) $user['estado'] !== 1) {
            $_SESSION = [];
            session_destroy();

            header('Location: /login');
            exit;
        }

        $oldInput = $this->getOldInput();

        $this->view('account/index', [
            'title'        => 'Mi cuenta',
            'user'         => $user,
            'csrfToken'    => $this->getCsrfToken(),
            'oldInput'     => $oldInput,
            'flashError'   => $this->getFlash('error'),
            'flashSuccess' => $this->getFlash('success')
        ]);
    }

    public function update(): void
    {
        AuthMiddleware::handle();

        /*
         * Validamos CSRF antes de procesar cualquier modificación.
         */
        $this->verifyCsrfToken();

        /*
         * La identidad viene exclusivamente de la sesión.
         */
        $userId = (int) $_SESSION['usuario_id'];

        /*
         * Obtenemos únicamente los campos permitidos.
         *
         * No aceptamos:
         * - id
         * - rol
         * - estado
         */
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');

        /*
         * Datos que podremos recuperar si ocurre un error.
         */
        $oldInput = [
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'username' => $username,
            'email'    => $email
        ];

        /*
         * Validación de campos obligatorios.
         */
        if (
            $nombre === '' ||
            $apellido === '' ||
            $username === '' ||
            $email === ''
        ) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'Todos los campos son obligatorios.'
            );

            header('Location: /account');
            exit;
        }

        /*
         * Validación del email.
         */
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El correo electrónico no es válido.'
            );

            header('Location: /account');
            exit;
        }

        $userModel = new User();

        /*
         * Recuperamos el estado actual de la cuenta desde la BD.
         *
         * No utilizamos los datos enviados por el cliente
         * para determinar cuál es el estado actual.
         */
        $user = $userModel->findById($userId);

        if ($user === false) {
            $_SESSION = [];
            session_destroy();

            header('Location: /login');
            exit;
        }

        /*
         * Comprobamos si realmente existe algún cambio.
         */
        if (
            $user['nombre'] === $nombre &&
            $user['apellido'] === $apellido &&
            $user['username'] === $username &&
            $user['email'] === $email
        ) {
            $this->setFlash(
                'success',
                'No realizaste ningún cambio en tu cuenta.'
            );

            header('Location: /account');
            exit;
        }

        /*
         * El email debe ser único, pero el usuario actual
         * puede conservar su propio email.
         */
        if ($userModel->emailExistsForOtherUser($email, $userId)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El correo electrónico ya está registrado.'
            );

            header('Location: /account');
            exit;
        }

        /*
         * El username debe ser único, pero el usuario actual
         * puede conservar su propio username.
         */
        if ($userModel->usernameExistsForOtherUser($username, $userId)) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'El nombre de usuario ya está registrado.'
            );

            header('Location: /account');
            exit;
        }

        /*
         * Actualizamos exclusivamente los datos personales.
         *
         * updateAccount() no permite modificar:
         * - rol
         * - estado
         */
        $updated = $userModel->updateAccount(
            $userId,
            $nombre,
            $apellido,
            $username,
            $email
        );

        if (!$updated) {
            $this->setOldInput($oldInput);

            $this->setFlash(
                'error',
                'No se pudieron actualizar los datos de la cuenta.'
            );

            header('Location: /account');
            exit;
        }

        /*
         * Actualizamos el nombre almacenado en sesión porque
         * el header utiliza usuario_nombre.
         */
        $_SESSION['usuario_nombre'] = $nombre;

        $this->setFlash(
            'success',
            'Los datos de tu cuenta se actualizaron correctamente.'
        );

        header('Location: /account');
        exit;
    }

    public function changePassword(): void
    {
        AuthMiddleware::handle();

        /*
         * Validamos CSRF antes de procesar cualquier modificación.
         */
        $this->verifyCsrfToken();

        /*
         * La identidad viene exclusivamente de la sesión.
         */
        $userId = (int) $_SESSION['usuario_id'];

        /*
         * Obtenemos las contraseñas enviadas.
         *
         * Nunca guardamos estos valores en sesión,
         * oldInput ni mensajes flash.
         */
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';

        /*
         * Validamos que todos los campos estén completos.
         */
        if (
            $currentPassword === '' ||
            $newPassword === '' ||
            $passwordConfirmation === ''
        ) {
            $this->setFlash(
                'error',
                'Todos los campos de contraseña son obligatorios.'
            );

            header('Location: /account');
            exit;
        }

        $userModel = new User();

        /*
         * Recuperamos el usuario directamente desde la BD.
         */
        $user = $userModel->findById($userId);

        if ($user === false) {
            $_SESSION = [];
            session_destroy();

            header('Location: /login');
            exit;
        }

        /*
         * La cuenta debe continuar activa.
         */
        if ((int) $user['estado'] !== 1) {
            $_SESSION = [];
            session_destroy();

            header('Location: /login');
            exit;
        }

        /*
         * Obtenemos únicamente el hash de la contraseña.
         *
         * No utilizamos findById() para esto porque ese método
         * no devuelve información sensible de la cuenta.
         */
        $passwordHash = $userModel->getPasswordHash($userId);

        if ($passwordHash === null) {
            $_SESSION = [];
            session_destroy();

            header('Location: /login');
            exit;
        }

        /*
         * Verificamos la contraseña actual contra el hash
         * almacenado en la base de datos.
         */
        if (!password_verify($currentPassword, $passwordHash)) {
            $this->setFlash(
                'error',
                'La contraseña actual no es correcta.'
            );

            header('Location: /account');
            exit;
        }

        /*
         * La nueva contraseña debe tener al menos 8 caracteres.
         */
        if (strlen($newPassword) < 8) {
            $this->setFlash(
                'error',
                'La nueva contraseña debe tener al menos 8 caracteres.'
            );

            header('Location: /account');
            exit;
        }

        /*
         * La nueva contraseña no puede coincidir con la actual.
         */
        if (password_verify($newPassword, $passwordHash)) {
            $this->setFlash(
                'error',
                'La nueva contraseña debe ser diferente de la actual.'
            );

            header('Location: /account');
            exit;
        }

        /*
         * Confirmamos que ambas contraseñas nuevas coincidan.
         */
        if ($newPassword !== $passwordConfirmation) {
            $this->setFlash(
                'error',
                'Las nuevas contraseñas no coinciden.'
            );

            header('Location: /account');
            exit;
        }

        /*
         * Generamos un nuevo hash.
         */
        $passwordHash = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        /*
         * Actualizamos exclusivamente la contraseña.
         */
        $updated = $userModel->updatePassword(
            $userId,
            $passwordHash
        );

        if (!$updated) {
            $this->setFlash(
                'error',
                'No se pudo actualizar la contraseña.'
            );

            header('Location: /account');
            exit;
        }

        /*
         * Regeneramos el ID de sesión después de cambiar
         * una credencial sensible.
         */
        session_regenerate_id(true);

        $this->setFlash(
            'success',
            'La contraseña se actualizó correctamente.'
        );

        header('Location: /account');
        exit;
    }
}