<?php

namespace App\Jobs\GatewayPushers;

class TollFreePushJob extends ParentPushJob
{
    protected function endPoint(): string
    {
        return 'gateway/toll-free';
    }
}
