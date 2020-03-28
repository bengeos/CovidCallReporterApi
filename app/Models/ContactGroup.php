<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactGroup extends Model
{
    use SoftDeletes;

    public function contacts() {
        return $this->hasManyThrough(Contact::class, GroupedContact::class, 'contact_id', 'id');
    }
}
