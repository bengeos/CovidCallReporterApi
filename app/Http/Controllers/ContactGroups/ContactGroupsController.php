<?php

namespace App\Http\Controllers\ContactGroups;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContactGroupsController extends Controller
{

    /**
     * ContactGroupsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getContactGroups() {

    }
}
