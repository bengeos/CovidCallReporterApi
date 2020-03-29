<?php

namespace App\Http\Controllers\FollowupResponses;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\CallReportRepository;
use App\Libs\Repositories\ContactGroupsRepository;
use App\Models\CallReport;
use App\Models\CallReportRumor;
use App\Models\CallRumorType;
use App\Models\City;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CallReportsController extends Controller
{
    protected $callReportsRepo;
    protected $contactGroupRepo;

    /**
     * CallReportsController constructor.
     * @param CallReportRepository $repository
     * @param ContactGroupsRepository $contactGroupsRepository
     */
    public function __construct(CallReportRepository $repository, ContactGroupsRepository $contactGroupsRepository)
    {
        $this->middleware('auth:api');
        $this->callReportsRepo = $repository;
        $this->contactGroupRepo = $contactGroupsRepository;
    }


    public function getNewFollowupCallReports()
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
            $reports = $this->callReportsRepo->getNotAssigned($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getAssignedFollowupCallReports()
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
            $reports = $this->callReportsRepo->getNotAssigned($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function assignCallReport()
    {
        try {
            $this->authorize('create', new CallReport());
            $thisUser = Auth::guard('api')->user();
            $credential = request()->all();
            $rule = ['call_report_id' => 'required', 'contact_group_id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $query1 = array();
            $query1['created_by'] = $thisUser->id;
            $callReport = $this->callReportsRepo->getItem($credential['call_report_id'], $query1);
            $callReport = $this->callReportsRepo->getItem($credential['call_report_id'], $query1);
            $credential['created_by'] = $thisUser->id;
            $credential['report_region_id'] = $thisUser->region_id;
            $newReport = $this->callReportsRepo->addNew($credential);
            if ($newReport) {
                if (isset($credential['rumor_types'])) {
                    $rumors = $credential['rumor_types'];
                    foreach ($rumors as $rumor) {
                        $rumorType = CallRumorType::where('id', '=', $rumor['id'])->first();
                        if ($rumorType instanceof CallRumorType) {
                            $newCallReportRumor = new CallReportRumor();
                            $newCallReportRumor->call_report_id = $newReport->id;
                            $newCallReportRumor->call_rumor_type_id = $rumorType->id;
                            $newCallReportRumor->save();
                        }
                    }
                }
                return response()->json(['status' => true, 'message' => 'call-reports created successfully', 'result' => $newReport, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

}
