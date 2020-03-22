<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\CallReportRepository;
use App\Models\CallReport;
use App\Models\City;
use App\Models\Role;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CallReportsController extends Controller
{
    protected $callReportsRepo;

    /**
     * CallReportsController constructor.
     * @param CallReportRepository $repository
     */
    public function __construct(CallReportRepository $repository)
    {
        $this->callReportsRepo = $repository;
    }

    public function getReportsByUserPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new City());
            $thisUser = Auth::guard('api')->user();
            $reports = $this->callReportsRepo->getAllByUserPaginated($thisUser->id, $PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getReportsPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new City());
            $thisUser = Auth::guard('api')->user();
            $reports = $this->callReportsRepo->getAllPaginated($PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createCallReport()
    {
        try {
            $this->authorize('create', new CallReport());
            $thisUser = Auth::guard('api')->user();
            $credential = request()->all();
            $rule = ['region_id' => 'required', 'phone' => 'required', 'gender' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $credential['created_by'] = $thisUser->id;
            $newUser = $this->callReportsRepo->addNew($credential);
            if ($newUser) {
                return response()->json(['status' => true, 'message' => 'report created successfully', 'result' => $newUser, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateCallReport()
    {
        try {
            $this->authorize('update', new CallReport());
            $thisUser = Auth::guard('api')->user();
            $credential = request()->all();
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $credential['created_by'] = $thisUser->id;
            $updatedUser = $this->callReportsRepo->updateItem($credential['id'], $credential);
            if ($updatedUser instanceof User) {
                return response()->json(['status' => true, 'message' => 'report updated successfully', 'result' => $updatedUser, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function deleteCallReport($id)
    {
        try {
            $this->authorize('delete', new CallReport());
            $thisUser = Auth::guard('api')->user();
            $queryData = array();
            $queryData['id!=:V'] = $thisUser->id;
            $status = $this->callReportsRepo->deleteItem($id, $queryData);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'report deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this report', 'result' => null, 'error' => 'failed to delete the report'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }
}
