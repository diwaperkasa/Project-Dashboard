<?php

namespace App\Helpers;

use App\Exceptions\Custom\ErrorValidator;

class ResponseApi
{
    static public function getDefaultHeader()
    {
        return [
            'Access-Control-Allow-Origin'       => '*',
            'Access-Control-Allow-Methods'      => 'POST, GET, PUT, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers'      => 'Access-Control-Allow-Origin, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization, X-Forwarded-For, User-Agent',
        ];
    }

    static public function success($data, int $httpCode = 200, string $message = "Success", array $header = [])
    {
        $headerResponse = array_merge(self::getDefaultHeader(), $header);

        return response()->json([
            'result' => $data,
            'message' => $message,
        ], $httpCode, $headerResponse);
    }

    static public function errorHandle(\Throwable $th)
    {
        $headerResponse = self::getDefaultHeader();
        $message = $th->getMessage();
        $errorCode = $th->getCode();

        if ($th instanceof ErrorValidator) {
            $httpCode = $th->getStatusCode();
            $errorValidation = $th->getErrorValidation();
            
            return response()->json([
                'errorMessage' => $message,
                'errorCode' => $errorCode,
                'errorValidation' => $errorValidation,
            ], $httpCode >= 400 && $httpCode <= 500 ? $httpCode : 500, $headerResponse);
        }

        return response()->json([
            'errorMessage' => $message,
            'errorCode' => $errorCode,
        ], $errorCode >= 400 && $errorCode <= 500 ? $errorCode : 500, $headerResponse);
    }

    static public function error($message, int $httpCode = 400, int $errorCode = 0, array $header = [])
    {
        $headerResponse = array_merge(self::getDefaultHeader(), $header);

        return response()->json([
            'errorMessage' => $message,
            'errorCode' => $errorCode,
        ], $httpCode >= 400 && $httpCode <= 500 ? $httpCode : 500, $headerResponse);
    }
}