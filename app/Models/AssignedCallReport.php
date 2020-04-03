<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignedCallReport extends Model
{
    use SoftDeletes;
    const ASSIGNMENT_TYPE = [
        'FOLLOWUP_RESPONSE_TEAM' => 'FOLLOWUP_RESPONSE_TEAM'
        , 'RAPID_RESPONSE_TEAM' => 'RAPID_RESPONSE_TEAM'
        , 'DISCARDED_RESPONSE_TEAM' => 'DISCARDED_RESPONSE_TEAM'];

    public function contact_group() {
        return $this->belongsTo(ContactGroup::class);
    }
}
