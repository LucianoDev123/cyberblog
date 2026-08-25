<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\RoleMiddleware;

class UploadController extends Controller
{
    /**
     * Directorio físico donde se guardarán
     * las imágenes insertadas dentro del contenido.
     */
    private const UPLOAD_DIRECTORY =
        __DIR__ . '/../../public/uploads/articles/content/';


    /**
     * Tamaño máximo permitido: 5 MB.
     */
    private const MAX_IMAGE_SIZE =
        5 * 1024 * 1024;


    /**
     * Recibe una imagen desde el editor.
     *
     * Esta imagen puede provenir de:
     * - Clipboard.
     * - Captura de pantalla.
     * - Imagen copiada desde otra aplicación.
     */
    public function uploadImage(): void
    {
        /*
         * Indicamos desde el principio que
         * la respuesta será JSON.
         */
        header(
            'Content-Type: application/json; charset=utf-8'
        );


        /*
         * Solo administradores y editores
         * pueden subir imágenes.
         */
        RoleMiddleware::handle([
            'admin',
            'editor'
        ]);


        /*
         * Validamos el token CSRF.
         *
         * El token llega dentro del FormData
         * enviado por editor.js.
         */
        $this->verifyCsrfToken();


        /*
         * Verificamos que exista el archivo.
         */
        if (
            !isset($_FILES['image'])
        ) {
            $this->jsonResponse(
                false,
                'No se recibió ninguna imagen.',
                400
            );

            return;
        }


        $file =
            $_FILES['image'];


        /*
         * Verificamos que PHP haya recibido
         * correctamente la subida.
         */
        if (
            !isset($file['error']) ||
            $file['error'] !== UPLOAD_ERR_OK
        ) {
            $this->jsonResponse(
                false,
                'Ocurrió un error durante la subida de la imagen.',
                400
            );

            return;
        }


        /*
         * Verificamos que exista el archivo
         * temporal creado por PHP.
         */
        if (
            !isset($file['tmp_name']) ||
            !is_uploaded_file($file['tmp_name'])
        ) {
            $this->jsonResponse(
                false,
                'El archivo recibido no es una subida válida.',
                400
            );

            return;
        }


        /*
         * Validamos el tamaño.
         */
        if (
            !isset($file['size']) ||
            $file['size'] <= 0
        ) {
            $this->jsonResponse(
                false,
                'La imagen está vacía.',
                400
            );

            return;
        }


        if (
            $file['size'] > self::MAX_IMAGE_SIZE
        ) {
            $this->jsonResponse(
                false,
                'La imagen no puede superar los 5 MB.',
                400
            );

            return;
        }


        /*
         * Detectamos el tipo MIME real del archivo.
         *
         * No confiamos en la extensión enviada
         * por el navegador.
         */
        $finfo =
            new \finfo(
                FILEINFO_MIME_TYPE
            );


        $mimeType =
            $finfo->file(
                $file['tmp_name']
            );


        /*
         * Tipos permitidos.
         */
        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png'  => 'png',
            'image/webp' => 'webp'
        ];


        /*
         * Rechazamos formatos no permitidos.
         */
        if (
            !is_string($mimeType) ||
            !array_key_exists(
                $mimeType,
                $allowedTypes
            )
        ) {
            $this->jsonResponse(
                false,
                'Formato no permitido. Solo se aceptan JPG, PNG y WEBP.',
                400
            );

            return;
        }


        /*
         * Validación adicional:
         * comprobamos que realmente sea una imagen.
         */
        $imageInfo =
            @getimagesize(
                $file['tmp_name']
            );


        if (
            $imageInfo === false
        ) {
            $this->jsonResponse(
                false,
                'El archivo no contiene una imagen válida.',
                400
            );

            return;
        }


        /*
         * Creamos el directorio si no existe.
         */
        if (
            !is_dir(
                self::UPLOAD_DIRECTORY
            )
        ) {
            if (
                !mkdir(
                    self::UPLOAD_DIRECTORY,
                    0755,
                    true
                )
                &&
                !is_dir(
                    self::UPLOAD_DIRECTORY
                )
            ) {
                $this->jsonResponse(
                    false,
                    'No fue posible crear el directorio de imágenes.',
                    500
                );

                return;
            }
        }


        /*
         * Generamos un nombre aleatorio.
         *
         * No utilizamos el nombre original
         * enviado por el usuario.
         */
        try {

            $fileName =
                bin2hex(
                    random_bytes(16)
                )
                . '.'
                . $allowedTypes[$mimeType];

        } catch (\Throwable $exception) {

            $this->jsonResponse(
                false,
                'No fue posible generar un nombre seguro para la imagen.',
                500
            );

            return;
        }


        /*
         * Ruta física final.
         */
        $destination =
            self::UPLOAD_DIRECTORY
            . $fileName;


        /*
         * Guardamos la imagen.
         */
        if (
            !move_uploaded_file(
                $file['tmp_name'],
                $destination
            )
        ) {
            $this->jsonResponse(
                false,
                'No fue posible guardar la imagen.',
                500
            );

            return;
        }


        /*
         * Permisos restrictivos para el archivo.
         */
        @chmod(
            $destination,
            0644
        );


        /*
         * URL pública que será insertada
         * dentro del editor.
         */
        $url =
            '/incuyo/cyberblog/public/uploads/articles/content/'
            . rawurlencode($fileName);


        /*
         * Devolvemos el resultado.
         */
        $this->jsonResponse(
            true,
            'Imagen subida correctamente.',
            200,
            [
                'url' => $url
            ]
        );
    }


    /**
     * Genera una respuesta JSON estándar.
     */
    private function jsonResponse(
        bool $success,
        string $message,
        int $statusCode,
        array $data = []
    ): void {
        http_response_code(
            $statusCode
        );


        echo json_encode(
            array_merge(
                [
                    'success' => $success,
                    'message' => $message
                ],
                $data
            ),
            JSON_UNESCAPED_UNICODE
        );
    }
}