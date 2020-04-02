<?php


namespace App\Http\Controllers\MobileAuth;


use App\Models\AssignedCallReport;
use App\Models\CallReport;
use App\Models\CallReportFollowup;
use App\Models\CallReportFollowupSymptom;
use App\Models\ContactGroup;
use App\Models\SymptomType;
use Illuminate\Auth\Access\AuthorizationException;
use Validator;

class FollowUpReportsController
{

    /**
     * FollowUpReportsController constructor.
     */
    public function __construct()
    {

    }

    public function getSymptoms()
    {
        try {
            $symptoms = SymptomType::all();
            return response()->json(['status' => true, 'message' => 'symptoms fetched successfully', 'result' => $symptoms, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
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
                $callReports = CallReport::with('region', 'zone', 'wereda', 'city', 'sub_city', 'kebele', 'created_by', 'rumor_types', 'followups')
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

    public function createCallReportFollowup()
    {
        try {
            $credential = array();
            $credential['UNIQUE_CODE'] = request()->input('UNIQUE_CODE') ? request()->input('UNIQUE_CODE') : null;
            $credential['call_report_id'] = request()->input('call_report_id') ? request()->input('call_report_id') : null;
            $credential['symptom_type_ids'] = request()->input('symptom_type_ids') ? request()->input('symptom_type_ids') : [];
            $credential['has_symptom'] = request()->input('has_symptom') ? request()->input('has_symptom') : null;
            $credential['temperature'] = request()->input('temperature') ? request()->input('temperature') : null;
            $credential['other'] = request()->input('other') ? request()->input('other') : null;
            $rule = ['UNIQUE_CODE' => 'required', 'call_report_id' => 'required', 'symptom_type_ids' => 'required', 'has_symptom' => 'required', 'temperature' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $contactGroup = ContactGroup::where('unique_code', '=', $credential['UNIQUE_CODE'])->first();
            if ($contactGroup instanceof ContactGroup) {
                $assignedCallReportIds = AssignedCallReport::where('contact_group_id', '=', $contactGroup->id)->select('call_report_id')->get();
                $callReport = CallReport::whereIn('id', $assignedCallReportIds)->where('id', '=', $credential['call_report_id'])->first();
                if ($callReport instanceof CallReport) {
                    $newCallReportFollowUp = new CallReportFollowup();
                    $newCallReportFollowUp->call_report_id = $callReport->id;
                    $newCallReportFollowUp->has_symptom = isset($credential['has_symptom']) ? $credential['has_symptom'] : null;
                    $newCallReportFollowUp->temperature = isset($credential['temperature']) ? $credential['temperature'] : null;
                    $newCallReportFollowUp->other = isset($credential['other']) ? $credential['other'] : null;
                    if ($newCallReportFollowUp->save()) {
                        if (isset($credential['symptom_type_ids'])) {
                            $symptoms = isset($credential['symptom_type_ids']) ? $credential['symptom_type_ids'] : [];
                            foreach ($symptoms as $symptom) {
                                $callReportFollowUpSymptom = new CallReportFollowupSymptom();
                                $callReportFollowUpSymptom->call_report_followup_id = $newCallReportFollowUp->id;
                                $callReportFollowUpSymptom->symptom_type_id = $symptom['id'];
                                $callReportFollowUpSymptom->save();
                            }
                        }
                        return response()->json(['status' => true, 'message' => 'assigned call-reports fetched successfully', 'result' => $newCallReportFollowUp, 'error' => null], 200);
                    } else {
                        return response()->json(['status' => false, 'message' => 'whoops! unable to save followup report', 'result' => null, 'error' => 'unable to save followup report'], 500);
                    }
                } else {
                    return response()->json(['status' => false, 'message' => 'whoops! unable to find call-report', 'result' => null, 'error' => 'unable to find call-report'], 500);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to find team', 'result' => null, 'error' => 'unable to find team'], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'exception! ' . $exception->getMessage(), 'result' => null, 'error' => $exception->getMessage()], 500);
        }
    }
}
