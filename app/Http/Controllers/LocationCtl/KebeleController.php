<?php

namespace App\Http\Controllers\LocationCtl;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\KebeleRepository;
use App\Models\Kebele;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KebeleController extends Controller
{
    protected $kebbeleRepoCtl;

    /**
     * KebeleController constructor.
     * @param KebeleRepository $kebeleRepository
     */
    public function __construct(KebeleRepository $kebeleRepository)
    {
        $this->kebbeleRepoCtl = $kebeleRepository;
    }

    public function getKebelesList() {
        try {
            $this->authorize('view', new Kebele());
            $regions = $this->kebbeleRepoCtl->getAll();
            return response()->json(['status' => true, 'message' => 'kebeles fetched successfully', 'result' => $regions, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getKebelesPaginated() {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new Kebele());
            $countries = $this->regionCtrl->getAllPaginated($PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'kebeles fetched successfully', 'result' => $countries, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createKebele()
    {
        try {
            $this->authorize('create', new Kebele());
            $credential = request()->all();
            $rule = ['name' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $newKebele = $this->kebbeleRepoCtl->addNew($credential);
            if ($newKebele) {
                return response()->json(['status' => true, 'message' => 'kebele created successfully', 'result' => $newKebele, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateKebele()
    {
        try {
            $this->authorize('update', new Kebele());
            $credential = request()->all();
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $updatedKebele = $this->kebbeleRepoCtl->updateItem($credential['id'], $credential);
            if ($updatedKebele instanceof Kebele) {
                return response()->json(['status' => true, 'message' => 'kebele updated successfully', 'result' => $updatedKebele, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function deleteKebele($id)
    {
        try {
            $this->authorize('delete', new Kebele());
            $queryData = array();
            $status = $this->kebbeleRepoCtl->deleteItem($id, $queryData);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'kebele deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this kebele', 'result' => null, 'error' => 'failed to delete the kebele'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }
}
