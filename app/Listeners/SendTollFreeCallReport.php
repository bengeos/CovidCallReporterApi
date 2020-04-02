<?php

namespace App\Listeners;

use App\Events\TollFreeCallReported;
use App\Jobs\GatewayPushers\TollFreePushJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTollFreeCallReport
{
    public function handle(TollFreeCallReported $event)
    {
        TollFreePushJob::dispatch($event->getMappedData());
    }
}
