<?php

namespace App\Http\Controllers\LocationCtl;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\SubCitiesRepository;
use App\Models\SubCity;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;

class SubCitiesController extends Controller
{
    protected $subCitiesRepoCtl;

    /**
     * SubCitiesController constructor.
     * @param SubCitiesRepository $subCitiesRepository
     */
    public function __construct(SubCitiesRepository $subCitiesRepository)
    {
        $this->subCitiesRepoCtl = $subCitiesRepository;
    }

    public function getSubCitiesList()
    {
        try {
            $this->authorize('view', new SubCity());
            $cities = $this->subCitiesRepoCtl->getAll();
            return response()->json(['status' => true, 'message' => 'subCities fetched successfully', 'result' => $cities, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getSubCitiesPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new SubCity());
            $cities = $this->subCitiesRepoCtl->getAllPaginated($PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'subCities fetched successfully', 'result' => $cities, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createSubCity()
    {
        try {
            $this->authorize('create', new SubCity());
            $credential = request()->all();
            $rule = ['name' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $newCity = $this->subCitiesRepoCtl->addNew($credential);
            if ($newCity) {
                return response()->json(['status' => true, 'message' => 'subCities created successfully', 'result' => $newCity, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateSubCity()
    {
        try {
            $this->authorize('update', new SubCity());
            $credential = request()->all();
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $updatedCity = $this->subCitiesRepoCtl->updateItem($credential['id'], $credential);
            if ($updatedCity instanceof City) {
                return response()->json(['status' => true, 'message' => 'subCities updated successfully', 'result' => $updatedCity, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function deleteSubCity($id)
    {
        try {
            $this->authorize('delete', new SubCity());
            $queryData = array();
            $status = $this->subCitiesRepoCtl->deleteItem($id, $queryData);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'subCity deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this subCity', 'result' => null, 'error' => 'failed to delete the subCity'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }
}
