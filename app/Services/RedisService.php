<?php

namespace App\Services;

use Illuminate\Http\Request;

class RedisService
{
    public static function createKeyCacheFromRequest(Request $request, String $keyCache, array $namesRequest): string
{
    foreach ($namesRequest as $name) {
        if ($request->$name) {
            $keyCache .= "_" . $name . "_" . $request->$name;
        }
    }
    return $keyCache;
}


}
