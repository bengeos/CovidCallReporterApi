<?php


namespace App\Libs\Repositories;


use App\Libs\Interfaces\DefaultInterface;
use App\Models\GroupedContact;

class GroupedContactsRepository extends DefaultRepository implements DefaultInterface
{
    public function getItem($id, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return GroupedContact::with('contact', 'contact_group')
            ->where('id', '=', $id)
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
        return GroupedContact::with('contact', 'contact_group')
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->first();
    }

    public function getAll($queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return GroupedContact::with('contact', 'contact_group')
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->get();
    }

    public function getAllPaginated($pagination_size = 10, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        return GroupedContact::with('contact', 'contact_group')
            ->where(function ($query) use ($queryData) {
                $this->queryBuilder($query, $queryData);
            })
            ->paginate($pagination_size);
    }

    public function addNew($inputData)
    {
        $newGroupedGroup = new GroupedContact();
        $newGroupedGroup->contact_id = isset($inputData['contact_id']) ? $inputData['contact_id'] : null;
        $newGroupedGroup->contact_group_id = isset($inputData['contact_group_id']) ? $inputData['contact_group_id'] : null;
        $newGroupedGroup->save();
        return $newGroupedGroup;
    }

    public function updateItem($id, $updateData, $queryData = null)
    {
        if ($queryData == null) {
            $queryData = array();
        }
        $queryData['id'] = $id;
        return GroupedContact::where(function ($query) use ($queryData) {
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
        return GroupedContact::where(function ($query) use ($queryData) {
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
        return GroupedContact::where('id', '=', $id)->where(function ($query) use ($queryData) {
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
        $groupedContactsForDelete = GroupedContact::where(function ($query) use ($queryData) {
            if ($queryData) {
                $this->queryBuilder($query, $queryData);
            }
        }
        )->get();
        foreach ($groupedContactsForDelete as $groupedContacts) {
            if ($groupedContacts instanceof GroupedContact) {
                $groupedContacts->delete();
            }
        }
        return true;
    }
}
