<?php

namespace App\Services;

class RedisServices
{
    public static function createKeyCacheFromRrequest($request): string
    {
        $chaveCache = "ActivitieController_index";

        if ($request->regions) {
            $chaveCache .= "_regions_" . $request->regions;
        }

        if ($request->subclasses) {
            $chaveCache .= "_subclasses_" . $request->subclasses;
        }

        if ($request->ids) {
            $chaveCache .= "_ids_" . $request->ids;
        }

        if ($request->only_references) {
            $chaveCache .= "_only_references_" . $request->only_references;
        }

        return $chaveCache;
    }

}
