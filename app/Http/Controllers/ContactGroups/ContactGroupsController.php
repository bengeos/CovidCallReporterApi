<?php

namespace App\Http\Controllers\ContactGroups;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\ContactGroupsRepository;
use App\Models\CallReport;
use App\Models\CallReportRumor;
use App\Models\CallRumorType;
use App\Models\City;
use App\Models\ContactGroup;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactGroupsController extends Controller
{
    protected $contactGroupsRepo;

    /**
     * ContactGroupsController constructor.
     * @param ContactGroupsRepository $repository
     */
    public function __construct(ContactGroupsRepository $repository)
    {
        $this->middleware('auth:api');
        $this->contactGroupsRepo = $repository;
    }

    public function getContacts($group_id)
    {
        try {
            $this->authorize('view', new ContactGroup());
            $thisUser = Auth::guard('api')->user();
            $query = array();
            $query['id'] = $group_id;
            $query['created_by'] = $thisUser->id;

            $contactGroups = $this->contactGroupsRepo->getAllPaginated($query);
            return response()->json(['status' => true, 'message' => 'contact groups fetched successfully', 'result' => $contactGroups, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getContactGroups()
    {
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

    public function getContactGroupsPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new ContactGroup());
            $thisUser = Auth::guard('api')->user();
            $query = array();
            $query['created_by'] = $thisUser->id;
            $contactGroups = $this->contactGroupsRepo->getAllPaginated($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'contact groups fetched successfully', 'result' => $contactGroups, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createContactGroup()
    {
        try {
            $this->authorize('create', new ContactGroup());
            $thisUser = Auth::guard('api')->user();
            $credential = request()->all();
            $rule = ['name' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $credential['created_by'] = $thisUser->id;
            $newContactGroup = $this->contactGroupsRepo->addNew($credential);
            if ($newContactGroup instanceof ContactGroup) {
                return response()->json(['status' => true, 'message' => 'contact-group created successfully', 'result' => $newContactGroup, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateContactGroup()
    {
        try {
            $this->authorize('update', new ContactGroup());
            $thisUser = Auth::guard('api')->user();
            $credential = request()->only('id', 'city_id', 'sub_city_id', 'kebele_id', 'sub_city_id', 'kebele_id', 'name', 'description');
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $credential['created_by'] = $thisUser->id;
            $updatedContactGroupStatus = $this->contactGroupsRepo->updateItem($credential['id'], $credential);
            if ($updatedContactGroupStatus) {
                $updatedContactGroup = $this->contactGroupsRepo->getItem($credential['id']);
                return response()->json(['status' => true, 'message' => 'contact-group updated successfully 0', 'result' => $updatedContactGroup, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function deleteContactGroup($id)
    {
        try {
            $this->authorize('delete', new ContactGroup());
            $thisUser = Auth::guard('api')->user();
            $queryData = array();
            $queryData['created_by'] = $thisUser->id;
            $status = $this->contactGroupsRepo->deleteItem($id, $queryData);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'contact-group deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this contact-group', 'result' => null, 'error' => 'failed to delete the call-report'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }
}
