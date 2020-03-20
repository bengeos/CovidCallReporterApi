<?php


namespace App\Libs\Repositories;


use App\Libs\Interfaces\CallReportInterface;
use App\Models\CallReport;

class CallReportRepository extends DefaultRepository implements CallReportInterface
{

    /**
     * CallReportRepository constructor.
     */
    public function __construct()
    {
    }

    public function getItem($id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return CallReport::where('id', '=', $id)
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
        return CallReport::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->first();
    }

    public function getAll($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return CallReport::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->get();
    }

    public function getAllPaginated($pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return CallReport::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->paginate($pagination_size);
    }

    public function addNew($inputData)
    {
        $newCallReport = new CallReport();
        $newCallReport->region_id = isset($inputData['region_id']) ? $inputData['region_id'] : null;
        $newCallReport->zone_id = isset($inputData['zone_id']) ? $inputData['zone_id'] : null;
        $newCallReport->wereda_id = isset($inputData['wereda_id']) ? $inputData['wereda_id'] : null;
        $newCallReport->city_id = isset($inputData['city_id']) ? $inputData['city_id'] : null;
        $newCallReport->sub_city_id = isset($inputData['sub_city_id']) ? $inputData['sub_city_id'] : null;
        $newCallReport->kebele_id = isset($inputData['kebele_id']) ? $inputData['kebele_id'] : null;
        $newCallReport->age = isset($inputData['age']) ? $inputData['age'] : null;
        $newCallReport->gender = isset($inputData['gender']) ? $inputData['gender'] : null;
        $newCallReport->phone = isset($inputData['phone']) ? $inputData['phone'] : null;
        $newCallReport->occupation = isset($inputData['occupation']) ? $inputData['occupation'] : null;
        $newCallReport->other = isset($inputData['other']) ? $inputData['other'] : null;
        $newCallReport->save();
        return $newCallReport;
    }

    public function updateItem($id, $updateData, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        $queryData['id'] = $id;
        return CallReport::where(function ($query) use ($queryData) {
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
        return CallReport::where(function ($query) use ($queryData) {
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
        return CallReport::where('id', '=', $id)->where(function ($query) use ($queryData) {
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
        $callReportsForDelete = CallReport::where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->get();
        foreach ($callReportsForDelete as $callReport) {
            if ($callReport instanceof CallReport) {
                $callReport->delete();
            }
        }
        return true;
    }
}
