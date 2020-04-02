<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CallReportFollowup extends Model
{
//    use SoftDeletes;

    public function symptom() {
        return $this->belongsTo(SymptomType::class, 'symptom_type_id', 'id');
    }
}
