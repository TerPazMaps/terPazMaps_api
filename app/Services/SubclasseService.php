<?php

namespace App\Services;

use App\Models\Icon;
use App\Models\Subclasse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Interfaces\ServiceInterface;
use Illuminate\Database\Eloquent\Builder;


class SubclasseService extends AbstractService implements ServiceInterface
{
    protected static $model = Subclasse::class;

    public function index($request): Collection
    {
        $subclassesQuery = Icon::with('subclasse')->has('subclasse');
        if ($request->name) {
            $subclassesQuery = $this->filterName($subclassesQuery, $request->name);
        }

        return $subclassesQuery->get();
    }

    public function transform($subclassesQuery): array
    {
        return [
            "geojson" => $subclassesQuery->transform(function ($item) {
                $item['image_url'] = config('app.url') . "storage/" . substr($item->disk_name, 0, 3) . '/' . substr($item->disk_name, 3, 3) . '/' . substr($item->disk_name, 6, 3) . '/' . $item->disk_name;
                return $item;
            })
        ];
    }

    public function filterName($subclassesQuery, $name): Collection
    {
        return  $subclassesQuery->whereHas('subclasse', function ($query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        })->get();
    }
}
