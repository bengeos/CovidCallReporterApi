<?php

namespace App\Http\Controllers\CallReports;

use App\Events\TollFreeCallReported;
use App\Http\Controllers\Controller;
use App\Libs\Repositories\CallReportRepository;
use App\Models\CallReport;
use App\Models\CallReportRumor;
use App\Models\CallRumorType;
use App\Models\City;
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
        $this->middleware('auth:api');
        $this->callReportsRepo = $repository;
    }

    public function getRumorTypes()
    {
        try {
            $callRumorTypes = CallRumorType::all();
            return response()->json(['status' => true, 'message' => 'rumor_types fetched successfully', 'result' => $callRumorTypes, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getCallReports()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new City());
            $thisUser = Auth::guard('api')->user();
            $reports = $this->callReportsRepo->getAllByUserPaginated($thisUser->id, $PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'call-reports fetched successfully', 'result' => $reports, 'error' => null], 200);
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
                event(new TollFreeCallReported($credential));
                return response()->json(['status' => true, 'message' => 'call-reports created successfully', 'result' => $newReport, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateCallReport()
    {
        try {
            $this->authorize('update', new CallReport());
            $thisUser = Auth::guard('api')->user();
            $credential = request()->only('id', 'region_id', 'zone_id', 'city_id', 'sub_city_id', 'kebele_id', 'full_name', 'age', 'phone', 'second_phone', 'occupation', 'address', 'callerType', 'other', 'report_type', 'description');
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $credential['created_by'] = $thisUser->id;
            $updatedCallReportStatus = $this->callReportsRepo->updateItem($credential['id'], $credential);
            if ($updatedCallReportStatus) {
                $updatedCallReport = $this->callReportsRepo->getItem($credential['id']);
                return response()->json(['status' => true, 'message' => 'call-reports updated successfully 0', 'result' => $updatedCallReport, 'error' => null], 200);
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
            $queryData['created_by!=:V'] = $thisUser->id;
            $status = $this->callReportsRepo->deleteItem($id, $queryData);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'call-report deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this call-report', 'result' => null, 'error' => 'failed to delete the call-report'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }
}
