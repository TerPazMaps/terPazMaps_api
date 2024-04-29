<?php

namespace App\Utils;

class GeoJsonValidator
{
    public static function validate($data)
    {
        $json = json_decode($data);

        // Verificar se o JSON é válido
        if ($json === null) {
            return false;
        }

        // Verificar se é um FeatureCollection
        if (!isset($json->type) || $json->type !== 'FeatureCollection') {
            return false;
        }

        // Verificar se há features
        if (!isset($json->features) || !is_array($json->features)) {
            return false;
        }

        // Validar cada feature
        foreach ($json->features as $feature) {
            // Verificar se é uma feature válida
            if (!isset($feature->type) || $feature->type !== 'Feature' ||
                !isset($feature->geometry) || !isset($feature->properties)) {
                return false;
            }

            // Verificar o tipo de geometria (apenas suportando Polygon)
            if (!isset($feature->geometry->type) || $feature->geometry->type !== 'Polygon') {
                return false;
            }

            // Verificar se as coordenadas são um array
            if (!isset($feature->geometry->coordinates) || !is_array($feature->geometry->coordinates)) {
                return false;
            }

            // Verificar se as coordenadas são válidas (um array de arrays de arrays de números)
            foreach ($feature->geometry->coordinates as $coordinates) {
                if (!is_array($coordinates)) {
                    return false;
                }
                foreach ($coordinates as $point) {
                    if (!is_array($point) || count($point) !== 2 ||
                        !is_numeric($point[0]) || !is_numeric($point[1])) {
                        return false;
                    }
                }
            }

            // Verificar se as propriedades são um objeto
            if (!is_object($feature->properties)) {
                return false;
            }
        }

        // Se passou por todas as verificações, o GeoJSON é válido
        return true;
    }
}
