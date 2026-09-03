<?php

declare(strict_types=1);

namespace App\Models;

class User extends Model
{
    protected string $table = 'usuarios';


    /**
     * Busca un usuario mediante su correo electrónico.
     */
    public function findByEmail(string $email): array|false
    {
        $sql = "
            SELECT *
            FROM usuarios
            WHERE email = :email
            LIMIT 1
        ";

        $statement =
            $this->db->prepare($sql);

        $statement->execute([
            'email' => $email
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

        $statement =
            $this->db->prepare($sql);

        $statement->execute([
            'email' => $email
        ]);

        return $statement->fetch() !== false;
    }


    /**
     * Comprueba si un email pertenece a otro usuario.
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

        $statement =
            $this->db->prepare($sql);

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

        $statement =
            $this->db->prepare($sql);

        $statement->execute([
            'username' => $username
        ]);

        return $statement->fetch() !== false;
    }


    /**
     * Obtiene todos los usuarios.
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

        $statement =
            $this->db->query($sql);

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

        $statement =
            $this->db->query($sql);

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

        $statement =
            $this->db->prepare($sql);

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

        $statement =
            $this->db->prepare($sql);

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
}