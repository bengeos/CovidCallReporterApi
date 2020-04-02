<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallReportFollowupSymptom extends Model
{
    public function symptoms() {
        return $this->belongsTo(SymptomType::class, 'symptom_type_id', 'id');
    }
}
