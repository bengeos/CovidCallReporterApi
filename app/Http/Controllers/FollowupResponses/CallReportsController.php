<?php

namespace App\Http\Controllers\FollowupResponses;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\CallReportRepository;
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


    public function getNewFollowupCallReportsPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new City());
            $thisUser = Auth::guard('api')->user();
            $query = array();
            if ($thisUser->role_id != 1) {
                $query['report_region_id'] = $thisUser->region_id;
            }
            $query['report_group_id'] = 2;
            $reports = $this->callReportsRepo->getNewPaginated($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

}
