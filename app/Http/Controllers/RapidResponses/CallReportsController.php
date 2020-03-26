<?php

namespace App\Http\Controllers\RapidResponses;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\CallReportRepository;
use App\Models\CallReport;
use App\Models\City;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function getNewRapidCallReportsPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new CallReport());
            $thisUser = Auth::guard('api')->user();
            $query = array();
            if ($thisUser->role_id != 1) {
                $query['report_region_id'] = $thisUser->region_id;
            }
            $query['report_group_id'] = 1;
            $reports = $this->callReportsRepo->getAllPaginated($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'rrt reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }
}
