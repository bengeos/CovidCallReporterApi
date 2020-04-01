<?php

namespace App\Jobs\GatewayPushers;

class MedicalFacilitiesPushJob extends ParentPushJob
{
    protected function endPoint(): string
    {
        return 'gateway/medical-facilities';
    }
}
