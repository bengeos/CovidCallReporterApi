<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wereda extends Model
{
    use SoftDeletes;

    public function zone() {
        return $this->belongsTo(Zone::class);
    }
}
