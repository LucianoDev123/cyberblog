<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\SecurityAnalyzer;

class SecurityAnalyzerController
{
    public function analyze(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $input = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!is_array($input)) {
            http_response_code(400);

            echo json_encode([
                'status' => 'error',
                'message' => 'JSON inválido.'
            ]);

            return;
        }

        $type = $input['type'] ?? null;
        $indicator = $input['indicator'] ?? null;

        if (!$type || !$indicator) {
            http_response_code(400);

            echo json_encode([
                'status' => 'error',
                'message' => 'Los campos type e indicator son obligatorios.'
            ]);

            return;
        }

        try {
            $securityAnalyzer = new SecurityAnalyzer();

            $result = $securityAnalyzer->analyze(
                $type,
                $indicator
            );

            echo json_encode($result);
        } catch (\Throwable $e) {
            http_response_code(500);

            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}