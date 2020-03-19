<?php


namespace App\Libs\Repositories;


use App\Libs\Interfaces\DefaultInterface;
use App\Libs\Interfaces\WeredaInterface;
use App\Models\Wereda;

class WeredaRepository extends DefaultRepository implements WeredaInterface
{

    /**
     * WeredaRepository constructor.
     */
    public function __construct()
    {
    }

    public function getItem($id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return Wereda::where('id', '=', $id)
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
        return Wereda::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->first();
    }

    public function getAll($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return Wereda::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->get();
    }

    public function getAllPaginated($pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return Wereda::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->paginate($pagination_size);
    }

    public function getAllByZone($zone_id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return Wereda::with('zone')->where('zone_id', '=', $zone_id)
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->get();
    }

    public function getAllByZonePaginated($zone_id, $pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return Wereda::with('zone')->where('zone_id', '=', $zone_id)
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->paginate($pagination_size);
    }


    public function addNew($inputData)
    {
        $newWereda = new Wereda();
        $newWereda->zone_id = isset($inputData['zone_id']) ? $inputData['zone_id'] : null;
        $newWereda->name = isset($inputData['name']) ? $inputData['name'] : null;
        $newWereda->latitude = isset($inputData['latitude']) ? $inputData['latitude'] : null;
        $newWereda->longitude = isset($inputData['longitude']) ? $inputData['longitude'] : null;
        $newWereda->description = isset($inputData['description']) ? $inputData['description'] : null;
        $newWereda->save();
        return $newWereda;
    }

    public function updateItem($id, $updateData, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        $queryData['id'] = $id;
        return Wereda::where(function ($query) use ($queryData) {
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
        return Wereda::where(function ($query) use ($queryData) {
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
        return Wereda::where('id', '=', $id)->where(function ($query) use ($queryData) {
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
        $weredasForDelete = Wereda::where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->get();
        foreach ($weredasForDelete as $wereda) {
            if ($wereda instanceof Wereda) {
                $wereda->delete();
            }
        }
        return true;
    }
}
