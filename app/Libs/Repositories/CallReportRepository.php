<?php


namespace App\Libs\Repositories;


use App\Libs\Interfaces\CallReportInterface;
use App\Models\AssignedCallReport;
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
        return CallReport::with('region', 'zone', 'wereda', 'city', 'sub_city', 'kebele', 'created_by', 'rumor_types')
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->orderBy('id', 'DESC')
            ->paginate($pagination_size);
    }

    public function getNewPaginated($pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return CallReport::with('region', 'zone', 'wereda', 'city', 'sub_city', 'kebele', 'created_by', 'rumor_types')
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->orderBy('id', 'DESC')
            ->paginate($pagination_size);
    }

    public function getNotAssigned($pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return CallReport::with('region', 'zone', 'wereda', 'city', 'sub_city', 'kebele', 'created_by', 'rumor_types')
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->whereNotIn('id', AssignedCallReport::select('call_report_id')->get())
            ->orderBy('id', 'DESC')
            ->paginate($pagination_size);
    }

    public function getAllByUserPaginated($user_id, $pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return CallReport::with('region', 'zone', 'wereda', 'city', 'sub_city', 'kebele', 'created_by', 'rumor_types')
            ->where('created_by', '=', $user_id)
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->orderBy('id', 'DESC')
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
        $newCallReport->report_type = isset($inputData['report_type']) ? $inputData['report_type'] : null;
        $newCallReport->full_name = isset($inputData['full_name']) ? $inputData['full_name'] : null;
        $newCallReport->age = isset($inputData['age']) ? $inputData['age'] : null;
        $newCallReport->gender = isset($inputData['gender']) ? $inputData['gender'] : null;
        $newCallReport->phone = isset($inputData['phone']) ? $inputData['phone'] : null;
        $newCallReport->second_phone = isset($inputData['second_phone']) ? $inputData['second_phone'] : null;
        $newCallReport->occupation = isset($inputData['occupation']) ? $inputData['occupation'] : null;
        $newCallReport->other = isset($inputData['other']) ? $inputData['other'] : null;
        $newCallReport->is_travel_hx = isset($inputData['is_travel_hx']) ? $inputData['is_travel_hx'] : false;
        $newCallReport->is_contacted_with_pt = isset($inputData['is_contacted_with_pt']) ? $inputData['is_contacted_with_pt'] : false;
        $newCallReport->is_visited_animal = isset($inputData['is_visited_animal']) ? $inputData['is_visited_animal'] : false;
        $newCallReport->is_visited_hf = isset($inputData['is_visited_hf']) ? $inputData['is_visited_hf'] : false;
        $newCallReport->created_by = isset($inputData['created_by']) ? $inputData['created_by'] : null;
        $newCallReport->save();
        return $newCallReport;
    }

    public function updateItem($id, $updateData, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return CallReport::where('id', '=', $id)->where(function ($query) use ($queryData) {
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
