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
            $roles = Role::all();
            return response()->json(['status' => true, 'message' => 'roles fetched successfully', 'result' => $roles, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function getUsersList()
    {
        try {
            $this->authorize('view', new User());
            $users = $this->usersRepository->getAll();
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
            $users = $this->usersRepository->getAllPaginated($PAGINATE_NUM);
            return response()->json(['status' => true, 'message' => 'users fetched successfully', 'result' => $users, 'error' => null], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage(), 'result' => null, 'error' => $e->getCode()], 500);
        }
    }

    public function createUser()
    {
        try {
            $this->authorize('create', new User());
            $user = Auth::guard('admin_api')->user();
            $credential = request()->all();
            $rule = ['full_name' => 'required', 'email' => 'required|email', 'role_id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $selectedRole = Role::where('id', '=', $credential['role_id'])->first();
            if ($selectedRole instanceof Role) {
                $credential['user_id'] = $user->id;
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
            $user = Auth::guard('admin_api')->user();
            $credential = request()->all();
            $rule = ['id' => 'required'];
            $validator = Validator::make($credential, $rule);
            if ($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status' => false, 'message' => 'please provide necessary information', 'result' => null, 'error' => $error], 500);
            }
            $credential['user_id'] = $user->id;
            $updatedUser = $this->usersRepository->updateItem($credential['id'], $credential);
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

    public function deleteUser($id)
    {
        try {
            $this->authorize('delete', new User());
            $user = Auth::guard('admin_api')->user();
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
