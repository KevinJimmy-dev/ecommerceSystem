<?php

namespace App\Api;

class ApiError{
    public static function errorMessage($message, $code){
        return [
            'data' => [
                'message' => $message,
                'code' => $code
            ]
        ];
    }
}