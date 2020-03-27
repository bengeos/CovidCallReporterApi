<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use App\Models\CallReport;
use App\Models\ContactGroupUser;
use App\Models\DripFeed;
use App\Models\GroupedContact;
use App\Models\GroupMessage;
use App\Models\MessagePort;
use App\Models\MessagePortUser;
use App\Models\ReceivedMessage;
use App\Models\Region;
use App\Models\SentMessage;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardsController extends Controller
{

    /**
     * DashboardsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getDashboardCountData() {
        try{
            $thisUser = Auth::user();
            $countData = array();
            $countData['call_reports_count'] = CallReport::count();
            $countData['community_reports_count'] = 0;
            $countData['travel_reports_count'] = 0;
            $countData['medial_reports_count'] = 0;
            return response()->json(['status' => true, 'message' => 'dashboard count-data fetched successfully', 'result' => $countData, 'error' => null], 200);
        }catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'whoops! something went wrong', 'result' => null, 'error' => $exception->getMessage()], 200);
        }
    }

    public function getDailyReportsHistoryCount($ref_day) {
        try{
            $thisUser = Auth::user();
            if($thisUser instanceof User) {
                $cur_time = Carbon::now();
                $cur_time->subDay($ref_day + 15);
                $messageData = array();
                $messageData['start_date'] = $cur_time->format('M/d/Y');
                $messageData['call_reports_count'] = array();
                $messageData['community_reports_count'] = array();
                $messageData['travel_reports_count'] = array();
                $messageData['medical_reports_count'] = array();
                $messageData['record_date'] = array();
                for($i = 15; $i >= 0; $i--) {
                    $callReports = CallReport::whereDate('created_at', '=', $cur_time->toDateString())->count();
                    $messageData['call_reports_count'][] = $callReports;
                    $messageData['community_reports_count'][] = 0;
                    $messageData['travel_reports_count'][] = 0;;
                    $messageData['medical_reports_count'][] = 0;
//                    $messageData['medical_reports_count'][] = random_int(0, 5);
                    $messageData['record_date'][] = $cur_time->format('M d');
                    $cur_time->addDay(1);
                }
                return response()->json(['status' => true, 'message' => 'dashboard reports history fetched', 'result' => $messageData, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unauthorized user', 'result' => null, 'error' => "unauthorized user"], 200);
            }
        }catch (\Exception $exception){
            return response()->json(['status' => false, 'message' => 'whoops! something went wrong', 'result' => null, 'error' => $exception->getMessage()], 200);
        }
    }
    public function getRegionalCallReports() {
        try{
            $regions = Region::all();
            $regionReports = array();
            foreach ($regions as $region) {
                $data = array();
                $data['region'] = $region;
                $data['call_reports'] = CallReport::where('region_id', '=', $region->id)->count();
                $lastReport = CallReport::where('region_id', '=', $region->id)->orderBy('created_at')->first();
                if($lastReport instanceof CallReport) {
                    $data['last_report_date'] = $lastReport->created_at;
                } else {
                    $data['last_report_date'] = 'UNKNOWN';
                }
                $regionReports[] = $data;
            }
            return response()->json(['status' => true, 'message' => 'dashboard regional reports fetched', 'result' => $regionReports, 'error' => null], 200);
        }catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'whoops! something went wrong', 'result' => null, 'error' => $exception->getMessage()], 200);
        }
    }
}
