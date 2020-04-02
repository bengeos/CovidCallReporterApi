<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallReport extends Model
{
    use SoftDeletes;
    const GENDER = [
        "MALE" => "MALE",
        "FEMALE" => "FEMALE",
    ];

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function zone() {
        return $this->belongsTo(Zone::class);
    }

    public function wereda() {
        return $this->belongsTo(Wereda::class);
    }

    public function city() {
        return $this->belongsTo(City::class);
    }
    public function sub_city() {
        return $this->belongsTo(SubCity::class);
    }

    public function kebele() {
        return $this->belongsTo(Kebele::class);
    }

    public function created_by() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function rumor_types() {
        return $this->hasManyThrough(CallRumorType::class, CallReportRumor::class, 'call_report_id', 'id');
    }

    public function followups() {
        return $this->hasMany(CallReportFollowup::class)->with('symptoms');
    }


}
