<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Repositories\ActivitieRepository;
use Illuminate\Database\Eloquent\Builder;

class ActivitieService
{
    protected $activitieRepository;
    protected $activitieService;

    public function __construct(ActivitieRepository $activitieRepository)
    {
        $this->activitieRepository = $activitieRepository;
    }

    public function getAllWithRelationsAndGeometry(): Builder
    {
        return $this->activitieRepository->getAllWithRelationsAndGeometry();
    }

    public function filter(Request $request, Builder $query): Builder
    {
        if ($request->regions) {
            $regions_id = array_map('intval', explode(',', $request->regions));
            $query = $this->activitieRepository->filterQuery($query, 'region_id', $regions_id);
        }

        if ($request->subclasses) {
            $subclasses_id = array_map('intval', explode(',', $request->subclasses));
            $query = $this->activitieRepository->filterQuery($query, 'subclass_id', $subclasses_id);
        }
        
        if ($request->ids) {
            $ids = array_map('intval', explode(',', $request->ids));
            $query = $this->activitieRepository->filterQuery($query, 'id', $ids);
        }
        return $query;
    }
}
