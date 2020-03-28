<?php

namespace App\Http\Controllers\MobileAuth;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactGroup;
use App\Models\GroupedContact;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MobileAuthController extends Controller
{

    /**
     * MobileAuthController constructor.
     */
    public function __construct()
    {

    }

    public function authenticate() {
        try {
            $credentials = request()->only('unique_code', 'phone');
            $rule = ['unique_code' => 'required', 'phone' => 'required|min:4'];
            $validator = Validator::make($credentials, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'result' => null, 'message' => null, 'error' => $error], 500);
            }
            if (!auth()->guard('web')->attempt($credentials)) {
                return response()->json(['status' => false, 'result' => null, 'message' => 'whoops! invalid admin credential has been used!', 'error' => 'invalid credential'], 401);
            }
            $contactGroupsId = ContactGroup::where('unique_code', '=', $credentials['unique_code'])->select('id')->get();
            $contactsId = Contact::where('phone', '=', $credentials['phone'])->select('id')->get();
            $groupedContact = GroupedContact::whereIn('contact_id', $contactsId)->whereIn('contact_group_id', $contactGroupsId)->first();
            if ($groupedContact instanceof GroupedContact) {
                return response()->json(['status' => true, 'message' => 'authenticated successful', 'result' => $adminUser, 'token' => $token], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! inactive account', 'result' => null, 'error' => 'inactive account',], 500);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'result' => null, 'message' => 'whoops! exception has occurred', 'error' => $exception->getMessage()], 500);
        }
    }
}
