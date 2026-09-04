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
}