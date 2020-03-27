<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Libs\Repositories\UsersRepository;
use App\Models\Role;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    protected $usersRepository;

    /**
     * UsersController constructor.
     * @param UsersRepository $repository
     */
    public function __construct(UsersRepository $repository)
    {
        $this->middleware('auth:api');
        $this->usersRepository = $repository;
    }

    public function getRoleList()
    {
        try {
            $this->authorize('view', new User());
            $user = Auth::guard('api')->user();
            if ($user->role_id != 1) {
                $roles = Role::where('id', '>', $user->role_id)->get();
            } else {
                $roles = Role::all();
            }

            return response()->json(['status' => true, 'message' => 'roles fetched successfully', 'result' => $roles, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getUsersList()
    {
        try {
            $this->authorize('view', new User());
            $user = Auth::guard('api')->user();
            $query = array();
            if ($user->role_id != 1) {
                $query['region_id'] = $user->region_id;
            }
            $users = $this->usersRepository->getAll($query);
            return response()->json(['status' => true, 'message' => 'users fetched successfully', 'result' => $users, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getUsersPaginated()
    {
        try {
            $PAGINATE_NUM = request()->input('PAGINATE_SIZE') ? request()->input('PAGINATE_SIZE') : 10;
            $this->authorize('view', new User());
            $user = Auth::guard('api')->user();
            $query = array();
            if ($user->role_id != 1) {
                $query['region_id'] = $user->region_id;
            }
            $users = $this->usersRepository->getAllPaginated($PAGINATE_NUM, $query);
            return response()->json(['status' => true, 'message' => 'users fetched successfully', 'result' => $users, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createUser()
    {
        try {
            $this->authorize('create', new User());
            $user = Auth::guard('api')->user();
            $credential = request()->all();
            $rule = ['full_name' => 'required', 'email' => 'required|email', 'role_id' => 'required', 'password' => 'required', 'region_id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            if ($user->role_id > 1) {
                $credential['region_id'] = $user->region_id;
                $credential['call_center'] = $user->call_center;
            }
            $selectedRole = Role::where('id', '=', $credential['role_id'])->where('id', '>', $user->role_id)->first();
            if ($selectedRole instanceof Role) {
                $newUser = $this->usersRepository->addNew($credential);
                if ($newUser) {
                    return response()->json(['status' => true, 'message' => 'admin users created successfully', 'result' => $newUser, 'error' => null], 200);
                } else {
                    return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => 'something went wrong! try again'], 500);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unknown role-id', 'result' => null, 'error' => 'unknown role-id'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateUsers()
    {
        try {
            $this->authorize('update', new User());
            $user = Auth::guard('api')->user();
            $credential = request()->all();
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $updateData = array();
            $updateData['full_name'] = isset($credential['full_name']) ? $credential['full_name'] : null;
            $updateData['email'] = isset($credential['email']) ? $credential['email'] : null;
            $updateData['phone'] = isset($credential['phone']) ? $credential['phone'] : null;
            $updateData['call_center'] = isset($credential['phone']) ? $credential['phone'] : $user->call_center;
            $updatedUser = $this->usersRepository->updateItem($credential['id'], $updateData);
            if ($updatedUser instanceof User) {
                return response()->json(['status' => true, 'message' => 'admin users updated successfully', 'result' => $updatedUser, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function updateUserStatus()
    {
        try {
            $this->authorize('update', new User());
            $user = Auth::guard('api')->user();
            $credential = request()->all('id', 'is_active');
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $updatedUserStatus = $this->usersRepository->updateItem($credential['id'], $credential);
            if ($updatedUserStatus) {
                return response()->json(['status' => true, 'message' => 'admin users status updated successfully', 'result' => $updatedUserStatus, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! something went wrong! try again', 'result' => null, 'error' => null], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function deleteUser($id)
    {
        try {
            $this->authorize('delete', new User());
            $user = Auth::guard('api')->user();
            $queryData = array();
            $queryData['id!=:V'] = $user->id;
            $status = $this->usersRepository->deleteItem($id, $queryData);
            if ($status) {
                return response()->json(['status' => true, 'message' => 'admin users deleted successfully', 'result' => null, 'error' => null], 200);
            } else {
                return response()->json(['status' => false, 'message' => 'whoops! unable to delete this user', 'result' => null, 'error' => 'failed to delete the user'], 500);
            }
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        } catch (\Throwable $e) {
        }
    }
}
