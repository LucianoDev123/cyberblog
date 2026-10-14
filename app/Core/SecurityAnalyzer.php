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
                'El servicio de análisis no está configurado.'
            );
        }

        $payload = json_encode([
            'type' => $type,
            'indicator' => $indicator
        ]);

        if ($payload === false) {
            throw new \RuntimeException(
                'No se pudo preparar la solicitud de análisis.'
            );
        }

        $ch = curl_init($this->webhookUrl);

        if ($ch === false) {
            throw new \RuntimeException(
                'No se pudo inicializar la solicitud de análisis.'
            );
        }

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);

            curl_close($ch);

            throw new \RuntimeException(
                'No fue posible comunicarse con el servicio de análisis. Intentá nuevamente más tarde.'
            );
        }

        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $data = json_decode($response, true);

        /*
         * Primero comprobamos el código HTTP.
         * Si hay un error, intentamos utilizar el mensaje
         * que n8n incluyó en el JSON.
         */
        if ($httpCode < 200 || $httpCode >= 300) {
            if (is_array($data)) {
                $error = $data['error'] ?? null;
                $message = $data['message'] ?? null;

                if (
                    $httpCode === 400 &&
                    $error === 'invalid_domain' &&
                    is_string($message) &&
                    $message !== ''
                ) {
                    throw new \RuntimeException($message);
                }

                if ($httpCode === 502) {
                    throw new \RuntimeException(
                        'No fue posible consultar el servicio de análisis. Intentá nuevamente más tarde.'
                    );
                }

                if ($httpCode === 503) {
                    throw new \RuntimeException(
                        'El servicio de análisis está temporalmente ocupado. Intentá nuevamente más tarde.'
                    );
                }
            }

            throw new \RuntimeException(
                'El servicio de análisis no pudo completar la solicitud. Código HTTP: ' . $httpCode
            );
        }

        /*
         * Una respuesta exitosa también debe contener
         * un JSON válido.
         */
        if (!is_array($data)) {
            throw new \RuntimeException(
                'El servicio de análisis devolvió una respuesta inesperada.'
            );
        }

        /*
         * Algunas implementaciones pueden devolver errores
         * de aplicación utilizando HTTP 200.
         * No debemos interpretarlos como análisis exitosos.
         */
        if (($data['success'] ?? null) === false) {
            $message = $data['message'] ?? null;

            if (is_string($message) && $message !== '') {
                throw new \RuntimeException($message);
            }

            throw new \RuntimeException(
                'No fue posible completar el análisis.'
            );
        }

        return $data;
    }
}