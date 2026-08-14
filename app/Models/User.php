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

}