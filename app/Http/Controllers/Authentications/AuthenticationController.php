<?php

namespace App\Http\Controllers\Authentications;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{

    /**
     * AuthenticationController constructor.
     */
    public function __construct()
    {

    }

    public function authenticate()
    {
        try {
            $credentials = request()->only('email', 'password');
            $rules = ['email' => 'required|email', 'password' => 'required|min:4'];
            $validator = Validator::make($credentials, $rules);

            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'result' => null, 'message' => null, 'error' => $error], 500);
            }
            if (!auth()->guard('web')->attempt($credentials)) {
                return response()->json(['status' => false, 'result' => null, 'message' => 'whoops! invalid admin credential has been used!', 'error' => 'invalid credential'], 401);
            }
            $adminUser = Auth::guard('web')->user();
            if ($adminUser instanceof User) {
                $token = $adminUser->createToken('web')->accessToken;
                return response()->json(['status' => true, 'message' => 'authenticated successful', 'result' => $adminUser, 'token' => $token, 'user_type' => 1], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'result' => null, 'message' => 'whoops! exception has occurred', 'error' => $exception->getMessage()], 500);
        }
    }

    public function register()
    {
        try {
            $credentials = request()->only('full_name', 'phone', 'email', 'password');
            $rules = [
                'full_name' => 'required|min:4|max:255',
                'email' => 'required|email',
                'password' => 'required|min:8'
            ];
            $validator = Validator::make($credentials, $rules);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => "Whoops! Invalid Input", 'error' => $error], 500);
            }
            $old_user = User::where('email', '=', $credentials['email'])->first();
            if ($old_user instanceof User) {
                return response()->json(['status' => false, 'message' => 'This phone or email is already taken!', 'error' => 'Invalid Email and Phone Number'], 500);
            } else {
                $new_user = new User();
                $new_user->full_name = $credentials['full_name'];
                $new_user->email = $credentials['email'];
                $new_user->password = bcrypt($credentials['password']);
                $new_user->phone = isset($credentials['phone']) ? $credentials['phone'] : null;
                $new_user->role_id = 1;
                if ($new_user->save()) {
                    return response()->json(['status' => true, 'message' => 'registered successfully', 'result' => $new_user], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'whoops! unable to create a user! please try again', 'result' => null, 'error' => null], 500);
                }
            }
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'result' => null, 'message' => 'whoops! exception has occurred', 'error' => $exception->getMessage()], 500);
        }
    }
}
