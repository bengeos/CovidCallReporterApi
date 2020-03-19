<?php

namespace App\Http\Controllers\LocationCtl;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\ZoneRepository;
use App\Models\Zone;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller
{
    public $zoneRepositoryCtl;

    /**
     * ZoneController constructor.
     * @param $zoneRepositoryCtl
     */
    public function __construct(ZoneRepository $zoneRepositoryCtl)
    {
        $this->middleware('auth:api');
        $this->zoneRepositoryCtl = $zoneRepositoryCtl;
    }

    public function getZonesList($region_id)
    {
        try {
            $this->authorize('view', new Zone());
            $zones = $this->zoneRepositoryCtl->getAllByRegion($region_id);
            return response()->json(['status' => true, 'message' => 'zones fetched successfully', 'result' => $zones, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getZonesPaginated($region_id)
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new Zone());
            $zones = $this->zoneRepositoryCtl->getAllByRegionPaginated($region_id, $PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'zones fetched successfully', 'result' => $zones, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createZone()
    {
        try {
            $this->authorize('create', new Zone());
            $credential = request()->all();
            $rule = ['region_id' => 'required', 'name' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $newRegion = $this->zoneRepositoryCtl->addNew($credential);
            if ($newRegion) {
                return response()->json(['status' => true, 'message' => 'zone created successfully', 'result' => $newRegion, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateZone()
    {
        try {
            $this->authorize('update', new Zone());
            $credential = request()->all();
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $updatedZoneStatus = $this->zoneRepositoryCtl->updateItem($credential['id'], $credential);
            if ($updatedZoneStatus) {
                $updatedZone = $this->zoneRepositoryCtl->getItem($credential['id']);
                return response()->json(['status' => true, 'message' => 'zone updated successfully', 'result' => $updatedZone, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function deleteZone($id)
    {
        try {
            $this->authorize('delete', new Zone());
            $queryData = array();
            $status = $this->zoneRepositoryCtl->deleteItem($id, $queryData);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'zone deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this region', 'result' => null, 'error' => 'failed to delete the region'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }

}
