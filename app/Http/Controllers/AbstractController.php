<?php

namespace App\Http\Controllers;

abstract class AbstractController
{
    protected function sendResponse(string $response, int $code = 200): void
    {
        http_response_code($code);
        print $response;
        exit;
    }

    protected function validate(array $data): void
    {
        $errors = [];
        foreach ($data as $name => $value) {
            if (empty($value)) {
                $errors[$name] = $name . ' is required!';
            }
        }

        if (count($errors) > 0) {
            $message = json_encode([
                'errors' => $errors
            ]);

            $this->sendResponse($message, 422);
        }
    }
}
