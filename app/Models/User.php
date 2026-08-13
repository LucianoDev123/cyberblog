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
}