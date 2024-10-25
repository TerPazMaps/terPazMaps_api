<?php

namespace App\Services;

use Illuminate\Http\Request;

class RedisService
{
    protected $redis_ttl = 3600;
    protected $redis_ttl_low = 1;

    public static function createKeyCacheFromRequest(Request $request, String $keyCache, array $namesRequest): string
    {
        foreach ($namesRequest as $name) {
            // Verifica se o parâmetro está presente no request
            if ($request->has($name)) {
                // Se for um array (como no caso de subclasses[]), lida com múltiplos valores
                if (is_array($request->$name)) {
                    foreach ($request->$name as $value) {
                        $keyCache .= "_" . $name . "_" . $value;
                    }
                } else {
                    // Para parâmetros únicos, apenas adiciona o valor como antes
                    $keyCache .= "_" . $name . "_" . $request->$name;
                }
            }
        }
        return $keyCache;
    }

    
    // Retorna o TTL padrão
    public function getRedisTtl(): int
    {
        return $this->redis_ttl;
    }

    // Retorna o TTL baixo (curto prazo)
    public function getRedisTtlLow(): int
    {
        return $this->redis_ttl_low;
    }

}
