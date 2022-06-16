<?php

namespace App\Api;

class ApiSuccess{
    public static function successMessage($message, $collection){
        return [
            'data' => [
                'message' => $message,
                'collection' => $collection
            ]
        ];
    }
}