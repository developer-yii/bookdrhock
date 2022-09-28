<?php

namespace App\Http\Controllers\Admin;

use App\User;
use DataTables;
use App\Model\UserRole;
use App\Model\UserProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->user_role == 1) {
            if ($request->ajax()) {
                $users = User::query()
                    ->leftJoin(
                        'user_profiles',
                        function ($join) {
                            $join->on('user_profiles.user_id', '=', 'users.id');
                            $join->whereNull('user_profiles.deleted_at');
                        }
                    )
                    ->join('user_roles', 'user_roles.id', '=', 'users.user_role')
                    ->select(
                        'user_profiles.id as user_profile_id',
                        'user_profiles.first_name',
                        'user_profiles.last_name',
                        'users.id as id',
                        'users.email',
                        'user_profiles.phone',
                        'user_roles.role',
                        'user_roles.id as user_role_id',
                        'users.status'
                    )
                    ->groupBy('id')
                    ->get();

                return DataTables::of($users)
                    ->escapeColumns([])
                    ->toJson();
            }
            $roles = UserRole::all();
            $login_user_id = Auth::id();
            return view('admin.user.index', compact('roles', 'login_user_id'));
        } else {
            return redirect()->route('admin');
        }
    }

    public function userStatus(Request $request)
    {
        if (isset($request->id) && !empty($request->id)) {

            $user = User::find($request->id);
            if (isset($user->status) && ($user->status == true || $user->status == false)) {
                $user->status = !$user->status;
                $user->update();
            }

            if ($user->status == true) {
                $message = 'User status enable successfully!';
            } else {
                $message = 'User status disable successfully!';
            }

            return response()->json(['response' => 'success', 'message' => $message], 200);
        } else {
            return response()->json(['error' => 'emptyid', 'message' => 'Something is wrong! <br/>Please try again'], 400);
        }
    }

    public function createorupdate(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => (($request->user_form_action == 'edit' || $request->user_form_action == 'changepassword') && isset($request->id) && !empty($request->id)) ? 'required|email|string|max:255|unique:users,email,' . $request->id . ',id' : 'required|email|string|max:255|unique:users,email',
            'role' => 'required',
            'password' => ($request->user_form_action == 'changepassword' || $request->user_form_action == 'add') ? 'confirmed|required|min:8|string|different:old_password' : ''
        ];

        if (isset($request->phone) && !empty($request->phone)) {
            $rules['phone'] = 'required|numeric|digits:10';
        }

        $request->validate($rules);

        if (isset($request->id) && !empty($request->id) && isset($request->user_form_action) && !empty($request->user_form_action) && $request->user_form_action == 'changepassword') {
            $model = User::find($request->id);
            $model->password = bcrypt($request->password);
            $model->save();

            return response()->json(['response' => 'success', 'message' => 'User password updated successfully!'], 200);
        } else {
            if (isset($request->id) && !empty($request->id)) {
                $modelU = User::find($request->id);
            } else {
                $modelU = new User();
            }

            $modelU->name = $request->first_name . ' ' . $request->last_name;
            $modelU->email = $request->email;
            $modelU->user_role = $request->role;
            $modelU->status = true;

            if (!isset($request->id) && isset($request->password) && !empty($request->password) && isset($request->user_form_action) && !empty($request->user_form_action) && $request->user_form_action == 'add') {
                $modelU->password = bcrypt($request->password);
            }
            $modelU->save();

            if (isset($request->user_profile_id) && !empty($request->user_profile_id)) {
                $modelUP = UserProfile::find($request->user_profile_id);
            } else {
                $modelUP = new UserProfile();
            }

            $modelUP->user_id = $modelU->id;
            $modelUP->first_name = $request->first_name;
            $modelUP->last_name = $request->last_name;
            $modelUP->phone = $request->phone;
            $modelUP->save();
        }

        if (isset($request->user_form_action) && !empty($request->user_form_action) && $request->user_form_action == 'edit') {
            return response()->json(['response' => 'success', 'message' => 'User updated successfully!'], 200);
        } else {
            return response()->json(['response' => 'success', 'message' => 'User added updated successfully!'], 200);
        }
    }

    public function delete(Request $request)
    {
        User::find($request->id)->delete();

        $model = UserProfile::find($request->user_id);
        if (isset($model) && !empty($model) && count($model) > 0) {
            $model->delete();
        }

        return response()->json(['response' => 'success', 'message' => 'User deleted successfully!']);
    }
}
