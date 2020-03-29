<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupedContact extends Model
{
    use SoftDeletes;

    public function contact() {
        return $this->belongsTo(Contact::class);
    }

    public function contact_group() {
        return $this->belongsTo(ContactGroup::class);
    }
}
