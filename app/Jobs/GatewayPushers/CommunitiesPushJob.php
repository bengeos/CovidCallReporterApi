<?php

namespace App\Jobs\GatewayPushers;

class CommunitiesPushJob extends ParentPushJob
{
    protected function endPoint(): string
    {
        return 'gateway/communities';
    }
}
