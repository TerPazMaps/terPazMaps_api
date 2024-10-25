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

    public function getActivitiesbyArea(Request $request)
    {
        try {
            $geojson = $this->GeospatialService->getActivitiesbyArea($request);
            return ApiServices::statusCode200($geojson);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }

    public function getActivitiesbyAreaPG(Request $request)
    {
        try {
            $geojson = $this->GeospatialService->getActivitiesbyAreaPG($request);
            return ApiServices::statusCode200($geojson);
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

    public function getPointsOfInterest(Request $request)
    {
        try {
            $geojson = $this->GeospatialService->getPointsOfInterest($request);

            if ($geojson['features'] == []) {
                return ApiServices::statusCode404("Sem pontos de interesse próximo");
            }

            return ApiServices::statusCode200($geojson);
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

            return ApiServices::statusCode200($geojson);
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

            return ApiServices::statusCode200($buffer);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }

    public function getBufferSum(Request $request)
    {
        try {
            $buffer = $this->GeospatialService->getBufferSum($request);

            return ApiServices::statusCode200($buffer);
        } catch (Exception $e) {
            return ApiServices::statusCode500($e->getMessage());
        }
    }


}
