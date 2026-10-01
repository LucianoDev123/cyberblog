<?php

declare(strict_types=1);

namespace App\Core;

class UsersApi
{
    private string $baseUrl;

    public function __construct()
    {
        $config = require __DIR__ . '/../Config/api.php';

        $this->baseUrl = rtrim(
            $config['users_api_url'],
            '/'
        );
    }

    public function login(
        string $email,
        string $password
    ): array {
        $url = $this->baseUrl . '/login';

        $data = json_encode([
            'email' => $email,
            'password' => $password
        ]);

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],

            CURLOPT_POSTFIELDS => $data,

            CURLOPT_TIMEOUT => 5
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);

            curl_close($ch);

            throw new \RuntimeException(
                'No se pudo conectar con Users API: ' . $error
            );
        }

        $statusCode = curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );

        curl_close($ch);

        $result = json_decode(
            $response,
            true
        );

        return [
            'status' => $statusCode,
            'data' => $result
        ];
    }
}