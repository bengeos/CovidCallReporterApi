<?php

namespace App\Http\Controllers\ContactGroups;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\ContactsRepository;
use App\Libs\Repositories\GroupedContactsRepository;
use App\Models\ContactGroup;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class GroupedContactsController extends Controller
{

    /**
     * GroupedContactsController constructor.
     * @param GroupedContactsRepository $groupedContactsRepository
     * @param ContactsRepository $contactsRepository
     */
    public function __construct(GroupedContactsRepository $groupedContactsRepository, ContactsRepository $contactsRepository)
    {
        $this->middleware('auth:api');
    }

    public function getContacts($id) {
        try {
            $this->authorize('view', new ContactGroup());
            $thisUser = Auth::guard('api')->user();
            $query = array();
            $query['created_by'] = $thisUser->id;
            $contactGroups = $this->contactGroupsRepo->getAll($query);
            return response()->json(['status' => true, 'message' => 'contact groups fetched successfully', 'result' => $contactGroups, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }
}
