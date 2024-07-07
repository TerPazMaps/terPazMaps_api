<?php

namespace App\Services;

class ApiServices
{

    public static function statusCode200($data)
    {

        return response()->json([
            "success" => [
                "status" => "200",
                "title" => "OK",
                "detail" => $data
            ]
        ], 200);
    }

    public static function statusCode201($data)
    {
        return response()->json([
            "success" => [
                "status" => "201", "title" => "Created", "detail" => $data
            ]
        ], 201);
    }

    public static function statusCode242($data)
    {
        // code..
    }

    public static function statusCode400($data)
    {
        return response()->json([
            "error" => [
                "status" => "400", "title" => "Bad Request", "detail" => $data
            ]
        ], 400);
    }

    public static function statusCode403($data)
    {
        return response()->json([
            "error" => [
                "status" => "403",
                "title" => "Forbidden",
                "detail" => $data
            ]
        ], 403);
    }

    public static function statusCode404($data)
    {
        return response()->json([
            "error" => [
                "status" => "404",
                "title" => "Not Found",
                "detail" => $data,
            ]
        ], 404);
    }

    public static function statusCode422($data)
    {
        return response()->json([
            "error" => [
                "status" => "422",
                "title" => "Unprocessable Entity",
                "detail" => $data
            ]
        ]);
    }

    public static function statusCode500($data)
    {
        return response()->json([
            "error" => [
                "status" => "500",
                "title" => "Internal Server Error",
                "detail" => $data,
            ]
        ], 500);
    }

    public static function statusCodeCusto(String $title, $data, int $statusCode)
    {
        return response()->json([
            "error" => [
                "status" => "$statusCode",
                "title" => $title,
                "detail" => $data,
            ]
        ], $statusCode);
    }

    public static function statusCodeWithoutHeader($data, int $statusCode)
    {
        return response()->json($data, $statusCode);
    }
}
