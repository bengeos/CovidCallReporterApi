<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCity extends Model
{
    use SoftDeletes;

    public function city() {
        return $this->belongsTo(City::class);
    }
}
