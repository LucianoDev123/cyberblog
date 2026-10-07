<?php

declare(strict_types=1);

namespace App\Core;

class SecurityAnalyzer
{
    private string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = getenv('SECURITY_ANALYZER_WEBHOOK_URL') ?: '';
    }

    public function analyze(string $type, string $indicator): array
    {
        if ($this->webhookUrl === '') {
            throw new \RuntimeException(
                'SECURITY_ANALYZER_WEBHOOK_URL no está configurada.'
            );
        }

        $payload = json_encode([
            'type' => $type,
            'indicator' => $indicator
        ]);

        $ch = curl_init($this->webhookUrl);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);

            curl_close($ch);

            throw new \RuntimeException(
                'Error al comunicarse con n8n: ' . $error
            );
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $data = json_decode($response, true);

        if (!is_array($data)) {
            throw new \RuntimeException(
                'n8n devolvió una respuesta inválida.'
            );
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \RuntimeException(
                'n8n respondió con HTTP ' . $httpCode
            );
        }

        return $data;
    }
}