<?php


namespace App\Libs\Repositories;


use App\Libs\Interfaces\DefaultInterface;
use App\Models\ContactGroup;

class ContactGroupsRepository extends DefaultRepository implements DefaultInterface
{

    /**
     * ContactGroupsRepository constructor.
     */
    public function __construct()
    {

    }

    public function getItem($id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return ContactGroup::where('id', '=', $id)
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
        return ContactGroup::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->first();
    }

    public function getAll($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return ContactGroup::where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->get();
    }

    public function getAllPaginated($pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return ContactGroup::with('contacts')->where(function ($query) use ($queryData) {
            $this->queryBuilder($query, $queryData);
        })
            ->paginate($pagination_size);
    }

    public function addNew($inputData)
    {
        $newContactGroup = new ContactGroup();
        $newContactGroup->city_id = isset($inputData['city_id']) ? $inputData['city_id'] : null;
        $newContactGroup->sub_city_id = isset($inputData['sub_city_id']) ? $inputData['sub_city_id'] : null;
        $newContactGroup->kebele_id = isset($inputData['kebele_id']) ? $inputData['kebele_id'] : null;
        $newContactGroup->created_by = isset($inputData['created_by']) ? $inputData['created_by'] : null;
        $newContactGroup->created_by = isset($inputData['created_by']) ? $inputData['created_by'] : null;
        $newContactGroup->name = isset($inputData['name']) ? $inputData['name'] : null;
        $newContactGroup->description = isset($inputData['description']) ? $inputData['description'] : null;
        $newContactGroup->unique_code = $this->getRandomString(8);
        $newContactGroup->save();
        return $newContactGroup;
    }

    public function updateItem($id, $updateData, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        $queryData['id'] = $id;
        return ContactGroup::where(function ($query) use ($queryData) {
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
        return ContactGroup::where(function ($query) use ($queryData) {
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
        return ContactGroup::where('id', '=', $id)->where(function ($query) use ($queryData) {
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
        $contactGroupsForDelete = ContactGroup::where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->get();
        foreach ($contactGroupsForDelete as $contactGroup) {
            if ($contactGroup instanceof ContactGroup) {
                $contactGroup->delete();
            }
        }
        return true;
    }

    private function getRandomString($size)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = "";
        for ($i = 0; $i < $size; $i++) {
            $randomString = $randomString . $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}
