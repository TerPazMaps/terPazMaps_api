<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\ApiServices;
use App\Services\GeospatialService;

class ServicesController
{
    protected $GeospatialService;

    public function __construct(GeospatialService $geospatial)
    {
        $this->GeospatialService = $geospatial;
    }

    public function getActivitiesbyAreaMS(Request $request)
    {
        try {
            $geojson = $this->GeospatialService->getActivitiesbyAreaMS($request);
            return response()->json($geojson);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }

    public function getActivitiesbyAreaPG(Request $request)
    {
        try {
            $geojson = $this->GeospatialService->getActivitiesbyAreaPG($request);
            return response()->json($geojson);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }

    public function getDistance(Request $request)
    {
        try {
            $formattedDistance =  $this->GeospatialService->getDistance($request);
            return ApiServices::statusCode200($formattedDistance);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }

    public function getPointsOfInterestMS(Request $request)
    {
        try {
            $geojson = $this->GeospatialService->getPointsOfInterestMS($request);

            if ($geojson['features'] == []) {
                return ApiServices::statusCode404("Sem pontos de interesse próximo");
            }
            return response()->json($geojson);

        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }

    public function getPointsOfInterestPG(Request $request)
    {
        try {
            $geojson = $this->GeospatialService->getPointsOfInterestPG($request);

            if ($geojson['features'] == []) {
                return ApiServices::statusCode404("Sem pontos de interesse próximo");
            }

            return response()->json($geojson);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }

    public function getLengthStreet(Request $request)
    {
        try {
            $formattedLengthMeters = $this->GeospatialService->getLengthStreet($request);

            return ApiServices::statusCode200($formattedLengthMeters);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }

    public function getBuffer(Request $request)
    {
        try {
            $buffer = $this->GeospatialService->getBuffer($request);

            return response()->json($buffer);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }

    public function getBufferSumPG(Request $request)
    {
        try {
            $buffer = $this->GeospatialService->getBufferSumPG($request);

            return response()->json($buffer);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }
 
    public function getBufferSumMS(Request $request)
    {
        try {
            $buffer = $this->GeospatialService->getBufferSumMS($request);

            return response()->json($buffer);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }


}
