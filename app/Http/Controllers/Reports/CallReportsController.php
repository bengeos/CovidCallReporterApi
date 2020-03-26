<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\CallReportRepository;
use App\Models\CallReport;
use App\Models\CallReportRumor;
use App\Models\CallRumorType;
use App\Models\City;
use Illuminate\Auth\Access\AuthorizationException;
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
        $this->middleware('auth:api');
        $this->callReportsRepo = $repository;
    }

    public function getDashboardData()
    {
        try {
            $dashboardData = array();
            $dashboardData['total_call_reports'] = CallReport::count();
            $dashboardData['total_reports'] = CallReport::count();
        } catch (\Exception $exception) {

        }
    }

    public function getNewCallReports()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new CallReport());
            $thisUser = Auth::guard('api')->user();
            $query = array();
            if ($thisUser->role_id != 1) {
                $query['report_region_id'] = $thisUser->region_id;
            }
            $query['report_group_id'] = 0;
            $reports = $this->callReportsRepo->getAllPaginated($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'call-reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getAllCallReports()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new CallReport());
            $thisUser = Auth::guard('api')->user();
            $query = array();
            if ($thisUser->role_id != 1) {
                $query['report_region_id'] = $thisUser->region_id;
            }
            $query['report_group_id!=:v'] = 0;
            $reports = $this->callReportsRepo->getAllPaginated($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateCallReport()
    {
        try {
            $this->authorize('update', new CallReport());
            $credential = request()->only('id', 'report_group_id', 'remark_1');
            $rule = ['id' => 'required', 'report_group_id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $updatedCallReportStatus = $this->callReportsRepo->updateItem($credential['id'], $credential);
            if ($updatedCallReportStatus) {
                $updatedCallReport = $this->callReportsRepo->getItem($credential['id']);
                return response()->json(['status' => true, 'message' => 'report status updated successfully', 'result' => $updatedCallReport, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }
}
