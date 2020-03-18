<?php


namespace App\Libs\Repositories;


use App\Libs\Interfaces\DefaultInterface;
use App\Models\Region;

class RegionRepository extends DefaultRepository implements DefaultInterface
{

    public function getItem($id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return Region::where('id', '=', $id)
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
        return Region::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->first();
    }

    public function getAll($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return Region::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->get();
    }

    public function getAllPaginated($pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return Region::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->paginate($pagination_size);
    }

    public function addNew($inputData)
    {
        $newCountry = new Region();
        $newCountry->name = isset($inputData['name']) ? $inputData['name'] : null;
        $newCountry->latitude = isset($inputData['latitude']) ? $inputData['latitude'] : null;
        $newCountry->longitude = isset($inputData['longitude']) ? $inputData['longitude'] : null;
        $newCountry->description = isset($inputData['description']) ? $inputData['description'] : null;
        $newCountry->save();
        return $newCountry;
    }

    public function updateItem($id, $updateData, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        $queryData['id'] = $id;
        return Region::where(function ($query) use ($queryData) {
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
        return Region::where(function ($query) use ($queryData) {
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
        return Region::where('id', '=', $id)->where(function ($query) use ($queryData) {
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
        $countriesForDelete = Region::where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->get();
        foreach ($countriesForDelete as $country) {
            if ($country instanceof Region) {
                $country->delete();
            }
        }
        return true;
    }
}
