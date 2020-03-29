<?php

namespace App\Jobs\GatewayJobs;

use App\Models\CallReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NewCallReportCreatedTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @param CallReport $callReport
     */
    public function __construct(CallReport $callReport)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    }
}
