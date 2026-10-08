<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class AuthController extends Controller
{
    public function login(): void
    {
        $this->view('auth/login', [
            'title'        => 'Iniciar sesión',
            'flashError'   => $this->getFlash('error'),
            'flashSuccess' => $this->getFlash('success')
        ]);
    }


    public function register(): void
    {
        $this->view('auth/register', [
            'title' => 'Crear cuenta'
        ]);
    }


    /**
     * Muestra el formulario para solicitar
     * una recuperación de contraseña.
     */
    public function forgotPassword(): void
    {
        $this->view('auth/forgot', [
            'title'        => 'Recuperar contraseña',
            'csrfToken'    => $this->getCsrfToken(),
            'flashError'   => $this->getFlash('error'),
            'flashSuccess' => $this->getFlash('success')
        ]);
    }


    /**
     * Procesa la solicitud inicial de recuperación.
     *
     * Genera un código de recuperación y lo envía
     * mediante el servidor SMTP configurado.
     */
    public function processForgotPassword(): void
    {
        $this->verifyCsrfToken();

        $email = trim($_POST['email'] ?? '');

        if ($email === '') {
            $this->setFlash(
                'error',
                'Ingresá tu correo electrónico.'
            );

            header('Location: /forgot-password');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash(
                'error',
                'El correo electrónico no es válido.'
            );

            header('Location: /forgot-password');
            exit;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        /*
         * No revelamos si el correo existe o no.
         */
        $genericMessage =
            'Si existe una cuenta asociada a ese correo, recibirás ' .
            'instrucciones para restablecer tu contraseña.';

        if ($user === false || (int) $user['estado'] !== 1) {
            $this->setFlash('success', $genericMessage);

            header('Location: /forgot-password');
            exit;
        }

        $userId = (int) $user['id'];

        /*
         * Invalidamos cualquier código anterior.
         */
        $userModel->deletePasswordResetsForUser($userId);

        /*
         * Limpiamos desafíos expirados.
         */
        $userModel->deleteExpiredPasswordResets();

        /*
         * Generamos un código alfanumérico de 12 caracteres.
         *
         * Evitamos caracteres que puedan confundirse fácilmente:
         * 0/O y 1/I.
         */
        $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        $token = '';

        for ($i = 0; $i < 12; $i++) {
            $token .= $alphabet[
                random_int(0, strlen($alphabet) - 1)
            ];
        }

        /*
         * Formato visual:
         *
         * K7M4-X9QP-2R8T
         */
        $tokenDisplay =
            substr($token, 0, 4) . '-' .
            substr($token, 4, 4) . '-' .
            substr($token, 8, 4);

        /*
         * Nunca almacenamos el código en texto plano.
         */
        $tokenHash = hash('sha256', $token);

        /*
         * El código tiene una validez de 15 minutos.
         */
        $expiresAt = date(
            'Y-m-d H:i:s',
            time() + (15 * 60)
        );

        $created = $userModel->createPasswordReset(
            $userId,
            $tokenHash,
            $expiresAt
        );

        if (!$created) {
            $this->setFlash('success', $genericMessage);

            header('Location: /forgot-password');
            exit;
        }

        /*
         * Configuración SMTP.
         *
         * En desarrollo:
         *
         * MAIL_HOST=mailpit
         * MAIL_PORT=1025
         *
         * En producción podremos reemplazar estas variables
         * por las del servidor SMTP real.
         */
        $mailHost = getenv('MAIL_HOST') ?: 'mailpit';
        $mailPort = (int) (getenv('MAIL_PORT') ?: 1025);
        $mailUsername = getenv('MAIL_USERNAME') ?: '';
        $mailPassword = getenv('MAIL_PASSWORD') ?: '';
        $mailEncryption = strtolower(
            trim(getenv('MAIL_ENCRYPTION') ?: '')
        );

        try {
            $mail = new PHPMailer(true);

            $mail->isSMTP();

            $mail->Host = $mailHost;
            $mail->Port = $mailPort;

            if ($mailUsername !== '') {
                $mail->SMTPAuth = true;
                $mail->Username = $mailUsername;
                $mail->Password = $mailPassword;
            } else {
                $mail->SMTPAuth = false;
            }

            if ($mailEncryption === 'tls') {
                $mail->SMTPSecure =
                    PHPMailer::ENCRYPTION_STARTTLS;
            } elseif ($mailEncryption === 'ssl') {
                $mail->SMTPSecure =
                    PHPMailer::ENCRYPTION_SMTPS;
            }

            $mail->CharSet = 'UTF-8';

            $mail->setFrom(
                'no-reply@cyberblog.local',
                'CyberBlog'
            );

            $mail->addAddress(
                $email,
                trim(
                    ($user['nombre'] ?? '') . ' ' .
                    ($user['apellido'] ?? '')
                )
            );

            $mail->isHTML(false);

            $mail->Subject =
                'Código de recuperación de CyberBlog';

            $mail->Body = implode("\n", [
                'Hola ' . $user['nombre'] . ',',
                '',
                'Recibimos una solicitud para restablecer ' .
                'la contraseña de tu cuenta de CyberBlog.',
                '',
                'Tu código de recuperación es:',
                '',
                $tokenDisplay,
                '',
                'Este código es válido durante 15 minutos.',
                'Solo puede utilizarse una vez.',
                '',
                'Si no solicitaste este cambio, podés ignorar este correo.',
                '',
                'CyberBlog'
            ]);

            $mail->send();

        } catch (Exception $e) {
            /*
             * Si el correo no pudo enviarse, eliminamos el código
             * para evitar dejar un token válido que el usuario
             * nunca recibió.
             */
            $userModel->deletePasswordResetsForUser($userId);
        }

        /*
         * La respuesta es siempre genérica.
         *
         * Una vez generado y enviado el código,
         * llevamos al usuario directamente al formulario
         * donde deberá introducirlo.
         */
        $this->setFlash('success', $genericMessage);

        header('Location: /reset-password');
        exit;
    }


    /**
     * Muestra el formulario para introducir:
     *
     * - correo electrónico;
     * - código de recuperación;
     * - nueva contraseña;
     * - confirmación de contraseña.
     */
    public function resetPassword(): void
    {
        $this->view('auth/reset-password', [
            'title'        => 'Restablecer contraseña',
            'csrfToken'    => $this->getCsrfToken(),
            'flashError'   => $this->getFlash('error'),
            'flashSuccess' => $this->getFlash('success')
        ]);
    }


    /**
     * Procesa el restablecimiento de contraseña.
     *
     * El código se valida contra el usuario identificado
     * mediante el correo electrónico proporcionado.
     */
    public function processResetPassword(): void
    {
        $this->verifyCsrfToken();

        $email = trim($_POST['email'] ?? '');

        $token = strtoupper(
            preg_replace(
                '/[^A-Z0-9]/',
                '',
                strtoupper(trim($_POST['token'] ?? ''))
            )
        );

        $password = $_POST['password'] ?? '';

        $passwordConfirmation =
            $_POST['password_confirmation'] ?? '';

        /*
         * Validación básica del email.
         */
        if ($email === '') {
            $this->setFlash(
                'error',
                'Ingresá tu correo electrónico.'
            );

            header('Location: /reset-password');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setFlash(
                'error',
                'El correo electrónico no es válido.'
            );

            header('Location: /reset-password');
            exit;
        }

        /*
         * El código original tiene 12 caracteres.
         *
         * Permitimos que el usuario lo ingrese:
         *
         * K7M4-X9QP-2R8T
         *
         * o:
         *
         * K7M4X9QP2R8T
         */
        if (!preg_match('/^[A-Z0-9]{12}$/', $token)) {
            $this->setFlash(
                'error',
                'El código de recuperación no es válido.'
            );

            header('Location: /reset-password');
            exit;
        }

        /*
         * Validación de la nueva contraseña.
         */
        if ($password === '') {
            $this->setFlash(
                'error',
                'Ingresá una nueva contraseña.'
            );

            header('Location: /reset-password');
            exit;
        }

        if (strlen($password) < 8) {
            $this->setFlash(
                'error',
                'La contraseña debe tener al menos 8 caracteres.'
            );

            header('Location: /reset-password');
            exit;
        }

        if ($password !== $passwordConfirmation) {
            $this->setFlash(
                'error',
                'Las contraseñas no coinciden.'
            );

            header('Location: /reset-password');
            exit;
        }

        $userModel = new User();

        /*
         * Buscamos el usuario mediante el email.
         *
         * Esto vincula el código exclusivamente con esa cuenta.
         */
        $user = $userModel->findByEmail($email);

        if ($user === false || (int) $user['estado'] !== 1) {
            $this->setFlash(
                'error',
                'El código de recuperación no es válido o ha expirado.'
            );

            header('Location: /reset-password');
            exit;
        }

        $userId = (int) $user['id'];

        /*
         * Buscamos el desafío activo de ese usuario.
         *
         * Esta consulta no utiliza todavía el código ingresado.
         * Esto es necesario para poder contabilizar intentos
         * incorrectos.
         */
        $reset = $userModel->findActivePasswordResetByUser(
            $userId
        );

        if ($reset === false) {
            $this->setFlash(
                'error',
                'El código de recuperación no es válido o ha expirado.'
            );

            header('Location: /reset-password');
            exit;
        }

        /*
         * Generamos el hash del código proporcionado.
         */
        $tokenHash = hash('sha256', $token);

        /*
         * Comparamos hashes mediante comparación segura.
         */
        if (!hash_equals(
            $reset['token_hash'],
            $tokenHash
        )) {
            $userModel->incrementPasswordResetAttempts(
                (int) $reset['id']
            );

            $this->setFlash(
                'error',
                'El código de recuperación no es válido.'
            );

            header('Location: /reset-password');
            exit;
        }

        /*
         * Verificación adicional utilizando el método
         * específico de desafío válido.
         *
         * Esto confirma:
         *
         * - usuario correcto;
         * - código correcto;
         * - código vigente;
         * - menos de 5 intentos.
         */
        $validReset = $userModel->findValidPasswordReset(
            $userId,
            $tokenHash
        );

        if ($validReset === false) {
            $this->setFlash(
                'error',
                'El código de recuperación no es válido o ha expirado.'
            );

            header('Location: /reset-password');
            exit;
        }

        /*
         * Generamos el hash de la nueva contraseña.
         */
        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        if ($passwordHash === false) {
            $this->setFlash(
                'error',
                'No se pudo actualizar la contraseña.'
            );

            header('Location: /reset-password');
            exit;
        }

        /*
         * Actualizamos la contraseña.
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

            header('Location: /reset-password');
            exit;
        }

        /*
         * El código se elimina inmediatamente después
         * de utilizarse correctamente.
         *
         * Esto garantiza que sea de un solo uso.
         */
        $userModel->deletePasswordReset(
            (int) $validReset['id']
        );

        /*
         * No iniciamos sesión automáticamente.
         *
         * El usuario deberá autenticarse nuevamente
         * con su nueva contraseña.
         */
        $this->setFlash(
            'success',
            'Tu contraseña fue restablecida correctamente. ' .
            'Ahora podés iniciar sesión con tu nueva contraseña.'
        );

        header('Location: /login');
        exit;
    }


    public function storeRegister(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $apellido = trim($_POST['apellido'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirmation =
            $_POST['password_confirmation'] ?? '';

        if (
            $nombre === '' ||
            $apellido === '' ||
            $username === '' ||
            $email === '' ||
            $password === '' ||
            $passwordConfirmation === ''
        ) {
            die('Todos los campos son obligatorios.');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die('El correo electrónico no es válido.');
        }

        if (strlen($password) < 8) {
            die('La contraseña debe tener al menos 8 caracteres.');
        }

        if ($password !== $passwordConfirmation) {
            die('Las contraseñas no coinciden.');
        }

        $userModel = new User();

        if ($userModel->emailExists($email)) {
            die('El correo electrónico ya está registrado.');
        }

        if ($userModel->usernameExists($username)) {
            die('El nombre de usuario ya está registrado.');
        }

        $passwordHash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $created = $userModel->create([
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'username' => $username,
            'email'    => $email,
            'password' => $passwordHash,
            'rol'      => 'usuario',
            'estado'   => 1
        ]);

        if (!$created) {
            die('No se pudo crear la cuenta.');
        }

        header('Location: /login');
        exit;
    }


    public function authenticate(): void
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user === false) {
            die('Usuario o contraseña incorrectos.');
        }

        if (!password_verify($password, $user['password'])) {
            die('Usuario o contraseña incorrectos.');
        }

        session_regenerate_id(true);

        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nombre'] = $user['nombre'];
        $_SESSION['usuario_rol'] = $user['rol'];

        if (
            $user['rol'] === 'admin' ||
            $user['rol'] === 'editor'
        ) {
            header('Location: /admin');
        } else {
            header('Location: /');
        }

        exit;
    }


    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        header('Location: /login');
        exit;
    }
}