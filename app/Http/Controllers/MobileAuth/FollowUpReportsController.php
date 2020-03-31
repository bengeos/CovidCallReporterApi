<?php


namespace App\Http\Controllers\MobileAuth;


use App\Models\AssignedCallReport;
use App\Models\CallReport;
use App\Models\ContactGroup;
use Validator;

class FollowUpReportsController
{

    /**
     * FollowUpReportsController constructor.
     */
    public function __construct()
    {

    }

    public function getFollowUpCallReports()
    {
        try {
            $credential = array();
            $credential['UNIQUE_CODE'] = request()->input('UNIQUE_CODE') ? request()->input('UNIQUE_CODE') : null;
            $credential['PAGINATE_SIZE'] = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;

            $rule = ['UNIQUE_CODE' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $contactGroup = ContactGroup::where('unique_code', '=', $credential['UNIQUE_CODE'])->first();
            if ($contactGroup instanceof ContactGroup) {
                $assignedCallReportIds = AssignedCallReport::where('contact_group_id', '=', $contactGroup->id)->select('call_report_id')->get();
                $callReports = CallReport::with('region', 'zone', 'wereda', 'city', 'sub_city', 'kebele', 'created_by', 'rumor_types')
                    ->whereIn('id', $assignedCallReportIds)
                    ->paginate($credential['PAGINATE_SIZE']);
                return response()->json(['status' => true, 'message' => 'assigned call-reports fetched successfully', 'result' => $callReports, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to find team', 'result' => null, 'error' => ''], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'exception! ' . $exception->getMessage(), 'result' => null, 'error' => $exception->getMessage()], 500);
        }
    }
}
