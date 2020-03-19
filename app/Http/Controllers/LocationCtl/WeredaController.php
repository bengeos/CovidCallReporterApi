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
        $this->middleware('auth:api');
        $this->weredaRepoCtl = $weredaRepository;
    }

    public function getWeredasList($zone_id)
    {
        try {
            $this->authorize('view', new Wereda());
            $weredas = $this->weredaRepoCtl->getAllByZone($zone_id);
            return response()->json(['status' => true, 'message' => 'weredas fetched successfully', 'result' => $weredas, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getWeredasPaginated($zone_id)
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new Wereda());
            $weredas = $this->weredaRepoCtl->getAllByZonePaginated($zone_id, $PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'weredas fetched successfully', 'result' => $weredas, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createWereda()
    {
        try {
            $this->authorize('create', new Wereda());
            $credential = request()->all();
            $rule = ['zone_id' => 'required', 'name' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $newWereda = $this->weredaRepoCtl->addNew($credential);
            if ($newWereda instanceof Wereda) {
                return response()->json(['status' => true, 'message' => 'wereda created successfully', 'result' => $newWereda, 'error' => null], 200);
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
            $updatedWeredaStatus = $this->weredaRepoCtl->updateItem($credential['id'], $credential);
            if ($updatedWeredaStatus) {
                $updatedWereda = $this->weredaRepoCtl->getItem($credential['id']);
                return response()->json(['status' => true, 'message' => 'wereda updated successfully', 'result' => $updatedWereda, 'error' => null], 200);
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
