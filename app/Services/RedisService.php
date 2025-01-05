<?php

namespace App\Services;

use Illuminate\Http\Request;

class RedisService
{
    protected $redis_ttl = 3600;
    protected $redis_ttl_low = 1;

    public static function createKeyCacheFromRequest(String $keyCache, array $idsPrincipais, $request=null, array $namesRequest): string
    {
        // Adiciona os IDs principais ao início da chave de cache
        if (!empty($idsPrincipais)) {
            $keyCache .= "_" . implode("_", $idsPrincipais);
        }
        if($request){
            foreach ($namesRequest as $name) {
                // Verifica se o parâmetro está presente no request
                if ($request->has($name)) {
                    // Se for um array, agrupa os valores
                    if (is_array($request->$name)) {
                        // Junta os valores do array com underscore
                        $keyCache .= "_" . $name . "_" . implode("_", $request->$name);
                    } else {
                        // Para parâmetros únicos, apenas adiciona o valor
                        $keyCache .= "_" . $name . "_" . $request->$name;
                    }
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
