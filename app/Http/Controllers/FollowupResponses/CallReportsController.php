<?php

namespace App\Http\Controllers\FollowupResponses;

use App\Http\Controllers\Controller;
use App\Jobs\FollowupReportTasks\SendMessageToAssignedFollowUpReportTask;
use App\Libs\Repositories\CallReportRepository;
use App\Libs\Repositories\ContactGroupsRepository;
use App\Models\AssignedCallReport;
use App\Models\CallReport;
use App\Models\CallReportRumor;
use App\Models\CallRumorType;
use App\Models\City;
use App\Models\ContactGroup;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

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
            $reports = $this->callReportsRepo->getAssigned($PAGINATE_NUM, $query);
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
            $contactGroup = $this->contactGroupRepo->getItem($credential['contact_group_id'], $query1);
            if ($callReport instanceof CallReport && $contactGroup instanceof ContactGroup) {
                $newAssignedCallReport = new AssignedCallReport();
                $newAssignedCallReport->call_report_id = $callReport->id;
                $newAssignedCallReport->contact_group_id = $contactGroup->id;
                $newAssignedCallReport->assignment_type = AssignedCallReport::ASSIGNMENT_TYPE['FOLLOWUP_RESPONSE_TEAM'];
                $newAssignedCallReport->created_by = $thisUser->id;
                if ($newAssignedCallReport->save()) {
                    if (isset($credential['message'])) {
                        dispatch(new SendMessageToAssignedFollowUpReportTask($newAssignedCallReport, $credential['message']));
                    }
                    return response()->json(['status' => true, 'message' => 'call-report assigned successfully', 'result' => $newAssignedCallReport, 'error' => null], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to find the call-report and contact-group', 'result' => null, 'error' => 'unable to find the call-report and contact-group'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

}
