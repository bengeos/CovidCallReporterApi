<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallReportRumor extends Model
{
    public function call_rumor_type() {
        return $this->belongsTo(CallRumorType::class, 'call_rumor_type_id', 'id');
    }
}
