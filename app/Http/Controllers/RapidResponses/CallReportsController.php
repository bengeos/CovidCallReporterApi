<?php

namespace App\Http\Controllers\RapidResponses;

use App\Http\Controllers\Controller;
use App\Jobs\RapidResponseTasks\SendMessageToAssignedRapidResponseTeam;
use App\Libs\Repositories\CallReportRepository;
use App\Libs\Repositories\ContactGroupsRepository;
use App\Models\AssignedCallReport;
use App\Models\CallReport;
use App\Models\ContactGroup;
use Auth;
use Illuminate\Auth\Access\AuthorizationException;
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
            $reports = $this->callReportsRepo->getNotAssigned($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'rrt reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getOldRapidCallReportsPaginated()
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
            $reports = $this->callReportsRepo->getAssigned($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'rrt reports fetched successfully', 'result' => $reports, 'error' => null], 200);
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
            $callReport = $this->callReportsRepo->getItem($credential['call_report_id']);
            $contactGroup = $this->contactGroupRepo->getItem($credential['contact_group_id'], $query1);
            if ($callReport instanceof CallReport && $contactGroup instanceof ContactGroup) {
                $newAssignedCallReport = new AssignedCallReport();
                $newAssignedCallReport->call_report_id = $callReport->id;
                $newAssignedCallReport->contact_group_id = $contactGroup->id;
                $newAssignedCallReport->assignment_type = AssignedCallReport::ASSIGNMENT_TYPE['RAPID_RESPONSE_TEAM'];
                $newAssignedCallReport->created_by = $thisUser->id;
                if ($newAssignedCallReport->save()) {
                    dispatch(new SendMessageToAssignedRapidResponseTeam($newAssignedCallReport, $credential['message']));
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
