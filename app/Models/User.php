<?php

declare(strict_types=1);

namespace App\Models;

class User extends Model
{
    /**
     * Nombre de la tabla utilizada por el modelo.
     */
    protected string $table = 'usuarios';


    /**
     * Busca un usuario mediante su correo electrónico.
     *
     * Devuelve los datos del usuario encontrado o false
     * si no existe un usuario con ese correo.
     */
    public function findByEmail(string $email): array|false
    {
        $sql = "
            SELECT *
            FROM usuarios
            WHERE email = :email
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'email' => $email
        ]);

        return $statement->fetch();
    }


    /**
     * Busca un usuario mediante su ID.
     *
     * Devuelve los datos del usuario encontrado o false
     * si no existe un usuario con ese identificador.
     */
    public function findById(int $id): array|false
    {
        $sql = "
            SELECT
                id,
                nombre,
                apellido,
                username,
                email,
                rol,
                estado,
                created_at
            FROM usuarios
            WHERE id = :id
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'id' => $id
        ]);

        return $statement->fetch();
    }


    /**
     * Comprueba si un email ya está registrado.
     */
    public function emailExists(string $email): bool
    {
        $sql = "
            SELECT id
            FROM usuarios
            WHERE email = :email
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'email' => $email
        ]);

        return $statement->fetch() !== false;
    }


    /**
     * Comprueba si un email pertenece a otro usuario.
     *
     * Se utiliza durante la edición para permitir que el usuario
     * conserve su propio email sin considerarlo duplicado.
     */
    public function emailExistsForOtherUser(
        string $email,
        int $userId
    ): bool {
        $sql = "
            SELECT id
            FROM usuarios
            WHERE email = :email
            AND id != :id
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'email' => $email,
            'id'    => $userId
        ]);

        return $statement->fetch() !== false;
    }


    /**
     * Comprueba si un username ya está registrado.
     */
    public function usernameExists(string $username): bool
    {
        $sql = "
            SELECT id
            FROM usuarios
            WHERE username = :username
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'username' => $username
        ]);

        return $statement->fetch() !== false;
    }


    /**
     * Obtiene todos los usuarios.
     *
     * Este método se conserva para mantener compatibilidad
     * con otras partes del sistema que puedan utilizarlo.
     */
    public function getAllUsers(): array
    {
        $sql = "
            SELECT
                id,
                nombre,
                apellido,
                username,
                email,
                rol,
                estado,
                created_at
            FROM usuarios
            ORDER BY created_at DESC
        ";

        $statement = $this->db->query($sql);

        return $statement->fetchAll();
    }


    /**
     * Obtiene una página de usuarios.
     *
     * El parámetro $limit indica cuántos usuarios se devolverán.
     * El parámetro $offset indica desde qué registro comenzar.
     *
     * Ejemplo:
     *
     * Página 1:
     * LIMIT 15 OFFSET 0
     *
     * Página 2:
     * LIMIT 15 OFFSET 15
     *
     * Página 3:
     * LIMIT 15 OFFSET 30
     */
    public function getPaginatedUsers(
        int $limit,
        int $offset
    ): array {
        $sql = "
            SELECT
                id,
                nombre,
                apellido,
                username,
                email,
                rol,
                estado,
                created_at
            FROM usuarios
            ORDER BY created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $statement = $this->db->prepare($sql);

        $statement->bindValue(
            ':limit',
            $limit,
            \PDO::PARAM_INT
        );

        $statement->bindValue(
            ':offset',
            $offset,
            \PDO::PARAM_INT
        );

        $statement->execute();

        return $statement->fetchAll();
    }


    /**
     * Obtiene la cantidad total de usuarios.
     */
    public function countAllUsers(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM usuarios
        ";

        $statement = $this->db->query($sql);

        return (int) $statement->fetchColumn();
    }


    /**
     * Obtiene la cantidad de usuarios administradores.
     *
     * Se utiliza antes de eliminar un administrador para evitar
     * que el sistema quede sin ninguna cuenta administrativa.
     */
    public function countAdmins(): int
    {
        $sql = "
            SELECT COUNT(*)
            FROM usuarios
            WHERE rol = 'admin'
        ";

        $statement = $this->db->query($sql);

        return (int) $statement->fetchColumn();
    }


    /**
     * Actualiza los datos de un usuario.
     */
    public function update(
        int $id,
        array $data
    ): bool {
        $sql = "
            UPDATE usuarios
            SET
                nombre = :nombre,
                apellido = :apellido,
                email = :email,
                rol = :rol,
                estado = :estado
            WHERE id = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id'       => $id,
            'nombre'   => $data['nombre'],
            'apellido' => $data['apellido'],
            'email'    => $data['email'],
            'rol'      => $data['rol'],
            'estado'   => $data['estado']
        ]);
    }


    /**
     * Actualiza los datos personales de la cuenta.
     *
     * Este método está destinado al usuario autenticado.
     *
     * Por seguridad, solamente permite modificar:
     * - nombre
     * - apellido
     * - username
     * - email
     *
     * No permite modificar:
     * - rol
     * - estado
     */
    public function updateAccount(
        int $id,
        string $nombre,
        string $apellido,
        string $username,
        string $email
    ): bool {
        $sql = "
            UPDATE usuarios
            SET
                nombre = :nombre,
                apellido = :apellido,
                username = :username,
                email = :email
            WHERE id = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id'       => $id,
            'nombre'   => $nombre,
            'apellido' => $apellido,
            'username' => $username,
            'email'    => $email
        ]);
    }


    /**
     * Crea un nuevo usuario.
     */
    public function create(
        array $data
    ): bool {
        $sql = "
            INSERT INTO usuarios
            (
                nombre,
                apellido,
                username,
                email,
                password,
                rol,
                estado
            )
            VALUES
            (
                :nombre,
                :apellido,
                :username,
                :email,
                :password,
                :rol,
                :estado
            )
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'nombre'   => $data['nombre'],
            'apellido' => $data['apellido'],
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
            'rol'      => $data['rol'],
            'estado'   => $data['estado']
        ]);
    }


    /**
     * Actualiza la contraseña de un usuario.
     *
     * Recibe únicamente el hash de la nueva contraseña.
     */
    public function updatePassword(
        int $id,
        string $passwordHash
    ): bool {
        $sql = "
            UPDATE usuarios
            SET password = :password
            WHERE id = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id'       => $id,
            'password' => $passwordHash
        ]);
    }


    /**
     * Obtiene únicamente el hash de contraseña de un usuario.
     *
     * Se utiliza para operaciones sensibles que necesitan
     * verificar la contraseña actual.
     */
    public function getPasswordHash(int $id): ?string
    {
        $sql = "
            SELECT password
            FROM usuarios
            WHERE id = :id
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'id' => $id
        ]);

        $result = $statement->fetch();

        return $result !== false
            ? $result['password']
            : null;
    }


    /**
     * Elimina un usuario mediante su ID.
     *
     * Las validaciones de permisos, CSRF y reglas de negocio
     * se realizan previamente en el controlador.
     */
    public function delete(int $id): bool
    {
        $sql = "
            DELETE FROM usuarios
            WHERE id = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $id
        ]);
    }


    /**
     * Comprueba si un username pertenece a otro usuario.
     *
     * Se utiliza durante la edición de la cuenta para permitir
     * que el usuario conserve su propio username sin considerarlo
     * duplicado.
     */
    public function usernameExistsForOtherUser(
        string $username,
        int $userId
    ): bool {
        $sql = "
            SELECT id
            FROM usuarios
            WHERE username = :username
            AND id != :id
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'username' => $username,
            'id'       => $userId
        ]);

        return $statement->fetch() !== false;
    }


    /*
     * ============================================================
     * RECUPERACIÓN DE CONTRASEÑA
     * ============================================================
     */


    /**
     * Invalida todos los desafíos de recuperación existentes
     * para un usuario.
     *
     * Esto garantiza que solamente exista un desafío activo
     * para una cuenta.
     */
    public function deletePasswordResetsForUser(
        int $userId
    ): bool {
        $sql = "
            DELETE FROM password_resets
            WHERE usuario_id = :usuario_id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'usuario_id' => $userId
        ]);
    }


    /**
     * Crea un nuevo desafío de recuperación.
     *
     * Solamente se almacena el hash del código.
     *
     * $expiresAt debe recibirse como una fecha válida
     * en formato compatible con MySQL DATETIME.
     */
    public function createPasswordReset(
        int $userId,
        string $tokenHash,
        string $expiresAt
    ): bool {
        $sql = "
            INSERT INTO password_resets
            (
                usuario_id,
                token_hash,
                attempts,
                expires_at
            )
            VALUES
            (
                :usuario_id,
                :token_hash,
                0,
                :expires_at
            )
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'usuario_id' => $userId,
            'token_hash' => $tokenHash,
            'expires_at' => $expiresAt
        ]);
    }


    /**
     * Busca el desafío de recuperación activo de un usuario.
     *
     * Se utiliza cuando el código ingresado todavía no fue
     * validado, para poder controlar los intentos incorrectos.
     *
     * El desafío debe:
     * - pertenecer al usuario indicado;
     * - seguir vigente;
     * - no haber alcanzado 5 intentos.
     */
    public function findActivePasswordResetByUser(
        int $userId
    ): array|false {
        $sql = "
            SELECT
                id,
                usuario_id,
                token_hash,
                attempts,
                expires_at,
                created_at
            FROM password_resets
            WHERE usuario_id = :usuario_id
              AND expires_at > NOW()
              AND attempts < 5
            ORDER BY created_at DESC
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'usuario_id' => $userId
        ]);

        return $statement->fetch();
    }


    /**
     * Busca un desafío de recuperación válido.
     *
     * La búsqueda se realiza mediante:
     * - usuario_id
     * - hash del token
     *
     * Además:
     * - debe estar vigente;
     * - no puede haber alcanzado el máximo de intentos.
     *
     * De esta manera, un token de un usuario no puede utilizarse
     * para recuperar la cuenta de otro usuario.
     */
    public function findValidPasswordReset(
        int $userId,
        string $tokenHash
    ): array|false {
        $sql = "
            SELECT
                id,
                usuario_id,
                token_hash,
                attempts,
                expires_at,
                created_at
            FROM password_resets
            WHERE usuario_id = :usuario_id
              AND token_hash = :token_hash
              AND expires_at > NOW()
              AND attempts < 5
            LIMIT 1
        ";

        $statement = $this->db->prepare($sql);

        $statement->execute([
            'usuario_id' => $userId,
            'token_hash' => $tokenHash
        ]);

        return $statement->fetch();
    }


    /**
     * Incrementa de forma atómica el contador de intentos
     * de un desafío de recuperación.
     *
     * El contador nunca puede superar 5.
     */
    public function incrementPasswordResetAttempts(
        int $resetId
    ): bool {
        $sql = "
            UPDATE password_resets
            SET attempts = attempts + 1
            WHERE id = :id
              AND attempts < 5
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $resetId
        ]);
    }


    /**
     * Elimina un desafío de recuperación.
     *
     * Se utiliza después de completar correctamente el cambio
     * de contraseña para garantizar que el código sea de un
     * solo uso.
     */
    public function deletePasswordReset(
        int $resetId
    ): bool {
        $sql = "
            DELETE FROM password_resets
            WHERE id = :id
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            'id' => $resetId
        ]);
    }


    /**
     * Elimina los desafíos de recuperación expirados.
     *
     * Es una limpieza opcional que puede ejecutarse antes
     * de crear un nuevo desafío o mediante una tarea periódica.
     */
    public function deleteExpiredPasswordResets(): bool
    {
        $sql = "
            DELETE FROM password_resets
            WHERE expires_at <= NOW()
        ";

        $statement = $this->db->prepare($sql);

        return $statement->execute();
    }
}