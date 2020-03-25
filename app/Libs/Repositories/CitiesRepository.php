<?php


namespace App\Libs\Repositories;


use App\Libs\Interfaces\CityInterface;
use App\Libs\Interfaces\DefaultInterface;
use App\Models\City;
use App\Models\Wereda;
use App\Models\Zone;

class CitiesRepository extends DefaultRepository implements CityInterface
{

    /**
     * CitiesRepository constructor.
     */
    public function __construct()
    {
    }

    public function getItem($id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return City::with('wereda')->where('id', '=', $id)
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->first();
    }

    public function getItemBy($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return City::with('wereda')->where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->first();
    }

    public function getAll($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return City::with('wereda')->where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->get();
    }

    public function getAllPaginated($pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return City::with('wereda')->where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->paginate($pagination_size);
    }

    public function getAllByRegion($region_id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        $zones = Zone::where('region_id', '=', $region_id)->select('id')->get();
        $weredas = Wereda::whereIn('zone_id', $zones)->select('id')->get();
        return City::with('wereda')->whereIn('wereda_id', $weredas)
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->get();
    }
    public function addNew($inputData)
    {
        $newCity = new City();
        $newCity->wereda_id = isset($inputData['wereda_id']) ? $inputData['wereda_id'] : null;
        $newCity->name = isset($inputData['name']) ? $inputData['name'] : null;
        $newCity->latitude = isset($inputData['latitude']) ? $inputData['latitude'] : null;
        $newCity->longitude = isset($inputData['longitude']) ? $inputData['longitude'] : null;
        $newCity->description = isset($inputData['description']) ? $inputData['description'] : null;
        $newCity->save();
        return $newCity;
    }

    public function updateItem($id, $updateData, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        $queryData['id'] = $id;
        return City::where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->update($updateData);
    }

    public function updateItemBy($queryData, $updateData)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return City::where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->update($updateData);
    }

    public function deleteItem($id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return City::where('id', '=', $id)->where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->delete();
    }

    public function deleteItemBy($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        $citiesForDelete = City::where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->get();
        foreach ($citiesForDelete as $city) {
            if ($city instanceof City) {
                $city->delete();
            }
        }
        return true;
    }
}
