<?php

declare(strict_types=1);

namespace App\Models;

class User extends Model
{
    // Indicamos qué tabla utiliza este modelo.
    protected string $table = 'usuarios';


    /**
     * Busca un usuario mediante su correo electrónico.
     */
    public function findByEmail(string $email): array|false
    {
        // Consulta SQL para buscar el usuario.
        $sql = "
            SELECT *
            FROM usuarios
            WHERE email = :email
            LIMIT 1
        ";

        // Preparamos la consulta.
        $statement = $this->db->prepare($sql);

        // Ejecutamos la consulta enviando el email.
        $statement->execute([
            'email' => $email
        ]);

        // Devolvemos el usuario encontrado.
        // Si no existe, devuelve false.
        return $statement->fetch();
    }


    /**
     * Comprueba si un email ya está registrado.
     */
    public function emailExists(string $email): bool
    {
        // Buscamos un usuario que tenga este email.
        $sql = "
            SELECT id
            FROM usuarios
            WHERE email = :email
            LIMIT 1
        ";

        // Preparamos la consulta.
        $statement = $this->db->prepare($sql);

        // Ejecutamos la consulta.
        $statement->execute([
            'email' => $email
        ]);

        // Si encontramos un registro, el email existe.
        return $statement->fetch() !== false;
    }


    /**
     * Comprueba si un email pertenece a otro usuario.
     *
     * Se utiliza cuando estamos editando un usuario.
     */
    public function emailExistsForOtherUser(
        string $email,
        int $userId
    ): bool {
        // Buscamos el email excluyendo al usuario actual.
        $sql = "
            SELECT id
            FROM usuarios
            WHERE email = :email
            AND id != :id
            LIMIT 1
        ";

        // Preparamos la consulta.
        $statement = $this->db->prepare($sql);

        // Ejecutamos la consulta.
        $statement->execute([
            'email' => $email,
            'id'    => $userId
        ]);

        // Si encontramos un registro,
        // otro usuario ya utiliza ese email.
        return $statement->fetch() !== false;
    }


    /**
     * Comprueba si un username ya está registrado.
     */
    public function usernameExists(string $username): bool
    {
        // Buscamos un usuario que tenga este username.
        $sql = "
            SELECT id
            FROM usuarios
            WHERE username = :username
            LIMIT 1
        ";

        // Preparamos la consulta.
        $statement = $this->db->prepare($sql);

        // Ejecutamos la consulta.
        $statement->execute([
            'username' => $username
        ]);

        // Si encontramos un registro, el username existe.
        return $statement->fetch() !== false;
    }


    /**
     * Obtiene todos los usuarios.
     */
    public function getAllUsers(): array
    {
        // Consulta todos los usuarios.
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

        // Ejecutamos la consulta.
        $statement = $this->db->query($sql);

        // Devolvemos todos los usuarios encontrados.
        return $statement->fetchAll();
    }


    /**
     * Actualiza los datos de un usuario.
     */
    public function update(int $id, array $data): bool
    {
        // Consulta SQL para actualizar el usuario.
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

        // Preparamos la consulta.
        $statement = $this->db->prepare($sql);

        // Ejecutamos la consulta con los datos recibidos.
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
    public function create(array $data): bool
    {
        // Consulta SQL para insertar el nuevo usuario.
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

        // Preparamos la consulta.
        $statement = $this->db->prepare($sql);

        // Ejecutamos el INSERT con los datos recibidos.
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