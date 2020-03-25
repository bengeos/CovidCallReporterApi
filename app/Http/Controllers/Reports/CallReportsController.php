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

    public function getCallRumorTypes()
    {
        try {
            $this->authorize('view', new CallReport());
            $thisUser = Auth::guard('api')->user();
            $callRumorTypes = CallRumorType::all();
            return response()->json(['status' => true, 'message' => 'call-rumor_types fetched successfully', 'result' => $callRumorTypes, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
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

    public function getNewCallReportsPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new City());
            $query = array();
            $query['report_group_id'] = 0;
            $reports = $this->callReportsRepo->getAllPaginated($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }
    public function getAllCallReportsPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new City());
            $thisUser = Auth::guard('api')->user();
            $query = array();
            $query['report_group_id!=:v'] = 0;
            $reports = $this->callReportsRepo->getAllPaginated($PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getNewRapidCallReportsPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new City());
            $thisUser = Auth::guard('api')->user();
            $query = array();
            $query['report_group_id'] = 1;
            $reports = $this->callReportsRepo->getNewPaginated($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'reports fetched successfully', 'result' => $reports, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getNewFollowupCallReportsPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new City());
            $thisUser = Auth::guard('api')->user();
            $query = array();
            $query['report_group_id'] = 2;
            $reports = $this->callReportsRepo->getNewPaginated($PAGINATE_NUM, $query);
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
            $rule = ['region_id' => 'required', 'phone' => 'required', 'gender' => 'required', 'report_type' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $credential['created_by'] = $thisUser->id;
            logger('DARA', ['data' => $credential]);
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
                return response()->json(['status' => true, 'message' => 'report created successfully', 'result' => $newReport, 'error' => null], 200);
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
            $credential = request()->only('id', 'region_id', 'zone_id', 'city_id', 'sub_city_id', 'kebele_id', 'full_name', 'age', 'phone', 'occupation', 'callerType', 'other', 'description');
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $credential['created_by'] = $thisUser->id;
            $updatedCallReport = $this->callReportsRepo->updateItem($credential['id'], $credential);
            if ($updatedCallReport) {
                return response()->json(['status' => true, 'message' => 'report updated successfully', 'result' => $updatedCallReport, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateCallReportStatus()
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
            $oldCallReport = CallReport::where('id', '=', $credential['id'])->first();
            if ($oldCallReport instanceof CallReport) {
                $oldCallReport->report_group_id = isset($credential['report_group_id']) ? $credential['report_group_id'] : $oldCallReport->report_group_id;
                $oldCallReport->remark_1 = isset($credential['remark_1']) ? $credential['remark_1'] : $oldCallReport->remark_1;
                if ($oldCallReport->update()) {
                    return response()->json(['status' => true, 'message' => 'report status updated successfully', 'result' => $oldCallReport, 'error' => null], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => $credential, 'error' => null], 500);
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

    public function pullPayload()
    {
        $data = array();
        $data['id'] = 1;
        $data['user_id'] = 1;
        $data['full_name'] = 'suspect name';
        $data['phone'] = '251944272962';
        $data['gender'] = 'MALE';
        $data['age'] = '48';
        $data['is_confirmed'] = false;
        $data['is_recovered'] = false;
        $data['is_symptomatic'] = false;
        $data['rumor'] = [['symptom' => 'Fever'], ['symptom' => 'Cough'], ['symptom' => 'Runny Nose']];
        $data['occupation'] = '';
        $data['location'] = ['region' => 'Amhara', 'zone' => 'gojam', 'wereda' => '1', 'city' => 'Bahirdar'];
        $data['gps'] = ['latitude' => '8.234234234', 'longitude' => '42.435875834',];
        return response()->json(['message' => 'Sample Api response of the Data-Depo', 'result' => $data], 200);
    }
}
