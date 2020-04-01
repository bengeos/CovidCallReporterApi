<?php

namespace App\Jobs\RapidResponseTasks;

use App\Http\Controllers\Controller;
use App\Models\AssignedCallReport;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\GroupedContact;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessageToAssignedRapidResponseTeam implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $assignedCallReport;
    protected $message;
    protected $myController;

    /**
     * Create a new job instance.
     *
     * @param AssignedCallReport $assignedCallReport
     * @param $message
     */
    public function __construct(AssignedCallReport $assignedCallReport, $message)
    {
        $this->assignedCallReport = $assignedCallReport;
        $this->message = $message;
        $this->myController = new Controller();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            logger('SendMessageToAssignedRapidResponseReportTask');
            $negaritUrl = env('NEGARIT_API_URL') . 'api_request/sent_message';
            $contacts = Contact::whereIn('id', GroupedContact::where('contact_group_id', '=', $this->assignedCallReport->contact_group_id)->select('contact_id')->get())->get();
            $contactGroup = ContactGroup::where('id', '=', $this->assignedCallReport->contact_group_id)->first();
            if ($contactGroup instanceof ContactGroup) {
                foreach ($contacts as $contact) {
                    if ($contact instanceof Contact) {
                        $requestData = array();
                        $requestData['API_KEY'] = env('NEGARIT_API_KEY');
                        $requestData['campaign_id'] = env('NEGARIT_CAMPAIGN_ID');
                        $requestData['message'] = "Dear " . $contact->full_name . " you are assigned to new rapid response report.\nUNIQUE CODE: " . $contactGroup->unique_code . "\n" . $this->message;
                        $requestData['sent_to'] = $contact->phone;
                        $response = $this->myController->sendPostRequest($negaritUrl, json_encode($requestData));
                        logger('SendMessageToAssignedFollowUpReportTask', ['response' => $response, 'request_data'=>$requestData]);
                    }
                }
            }
        } catch (\Exception $exception) {
            logger('SendMessageToAssignedFollowUpReportTask', ['exception' => $exception->getMessage()]);
        }
    }
}
