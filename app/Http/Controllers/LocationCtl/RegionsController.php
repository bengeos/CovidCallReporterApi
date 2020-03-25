<?php

namespace App\Http\Controllers\LocationCtl;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\RegionRepository;
use App\Models\Country;
use App\Models\Region;
use App\Models\Role;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegionsController extends Controller
{
    protected $regionCtrl;

    /**
     * RegionsController constructor.
     * @param RegionRepository $regionRepository
     */
    public function __construct(RegionRepository $regionRepository)
    {
        $this->middleware('auth:api');
        $this->regionCtrl = $regionRepository;
    }

    public function getRegionsList()
    {
        try {
            $this->authorize('view', new Region());
            $regions = $this->regionCtrl->getAll();
            return response()->json(['status' => true, 'message' => 'regions fetched successfully', 'result' => $regions, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getRegionsPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new Region());
            $countries = $this->regionCtrl->getAllPaginated($PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'regions fetched successfully', 'result' => $countries, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createRegion()
    {
        try {
            $this->authorize('create', new Region());
            $credential = request()->all();
            $rule = ['name' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $newRegion = $this->regionCtrl->addNew($credential);
            if ($newRegion) {
                return response()->json(['status' => true, 'message' => 'region created successfully', 'result' => $newRegion, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateRegion()
    {
        try {
            $this->authorize('update', new Region());
            $credential = request()->all();
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $updatedRegionStatus = $this->regionCtrl->updateItem($credential['id'], $credential);
            if ($updatedRegionStatus) {
                $updatedRegion = $this->regionCtrl->getItem($credential['id']);
                return response()->json(['status' => true, 'message' => 'region updated successfully', 'result' => $updatedRegion, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function deleteRegion($id)
    {
        try {
            $this->authorize('delete', new Region());
            $queryData = array();
            $status = $this->regionCtrl->deleteItem($id, $queryData);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'region deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this region', 'result' => null, 'error' => 'failed to delete the region'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }
}
