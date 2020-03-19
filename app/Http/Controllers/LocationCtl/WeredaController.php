<?php

namespace App\Http\Controllers\LocationCtl;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\WeredaRepository;
use App\Models\Wereda;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;

class WeredaController extends Controller
{
    protected $weredaRepoCtl;

    /**
     * WeredaController constructor.
     * @param WeredaRepository $weredaRepository
     */
    public function __construct(WeredaRepository $weredaRepository)
    {
        $this->weredaRepoCtl = $weredaRepository;
    }

    public function getZonesList()
    {
        try {
            $this->authorize('view', new Wereda());
            $regions = $this->weredaRepoCtl->getAll();
            return response()->json(['status' => true, 'message' => 'wereda fetched successfully', 'result' => $regions, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getZonesPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new Wereda());
            $countries = $this->weredaRepoCtl->getAllPaginated($PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'wereda fetched successfully', 'result' => $countries, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createZone()
    {
        try {
            $this->authorize('create', new Wereda());
            $credential = request()->all();
            $rule = ['name' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $newRegion = $this->weredaRepoCtl->addNew($credential);
            if ($newRegion) {
                return response()->json(['status' => true, 'message' => 'wereda created successfully', 'result' => $newRegion, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateWereda()
    {
        try {
            $this->authorize('update', new Wereda());
            $credential = request()->all();
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $updatedRegion = $this->weredaRepoCtl->updateItem($credential['id'], $credential);
            if ($updatedRegion instanceof User) {
                return response()->json(['status' => true, 'message' => 'wereda updated successfully', 'result' => $updatedRegion, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function deleteWereda($id)
    {
        try {
            $this->authorize('delete', new Wereda());
            $queryData = array();
            $status = $this->weredaRepoCtl->deleteItem($id, $queryData);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'wereda deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this region', 'result' => null, 'error' => 'failed to delete the region'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }
}
