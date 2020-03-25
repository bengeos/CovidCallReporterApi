<?php

namespace App\Http\Controllers\LocationCtl;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\CitiesRepository;
use App\Models\City;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CitiesController extends Controller
{
    protected $citiesRepoCtl;

    /**
     * CitiesController constructor.
     * @param CitiesRepository $citiesRepository
     */
    public function __construct(CitiesRepository $citiesRepository)
    {
        $this->middleware('auth:api');
        $this->citiesRepoCtl = $citiesRepository;
    }

    public function getCitiesListByRegion($region_id)
    {
        try {
            $this->authorize('view', new City());
            $query = array();
            $cities = $this->citiesRepoCtl->getAllByRegion($region_id);
            return response()->json(['status' => true, 'message' => 'cities fetched successfully', 'result' => $cities, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }
    public function getCitiesList($wereda_id)
    {
        try {
            $this->authorize('view', new City());
            $query = array();
            $query['wereda_id'] = $wereda_id;
            $cities = $this->citiesRepoCtl->getAll($query);
            return response()->json(['status' => true, 'message' => 'cities fetched successfully', 'result' => $cities, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getCitiesPaginated($wereda_id)
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new City());
            $query['wereda_id'] = $wereda_id;
            $cities = $this->citiesRepoCtl->getAllPaginated($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'cities fetched successfully', 'result' => $cities, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createCity()
    {
        try {
            $this->authorize('create', new City());
            $credential = request()->all();
            $rule = ['wereda_id'=>'required', 'name' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $newCity = $this->citiesRepoCtl->addNew($credential);
            if ($newCity) {
                return response()->json(['status' => true, 'message' => 'cities created successfully', 'result' => $newCity, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateCity()
    {
        try {
            $this->authorize('update', new City());
            $credential = request()->all();
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $updatedCityStatus = $this->citiesRepoCtl->updateItem($credential['id'], $credential);
            if ($updatedCityStatus) {
                $updatedCity = $this->citiesRepoCtl->getItem($credential['id']);
                return response()->json(['status' => true, 'message' => 'cities updated successfully', 'result' => $updatedCity, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function deleteCity($id)
    {
        try {
            $this->authorize('delete', new City());
            $queryData = array();
            $status = $this->citiesRepoCtl->deleteItem($id, $queryData);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'city deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this city', 'result' => null, 'error' => 'failed to delete the city'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }
}
