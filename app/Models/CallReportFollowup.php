<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallReportFollowup extends Model
{
//    use SoftDeletes;

    public function symptoms() {
        return $this->hasMany(CallReportFollowupSymptom::class)->with('symptoms');
    }
}
