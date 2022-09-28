<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Model\UserProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user_profile = User::query()
                ->leftJoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
                ->where('users.id', auth()->user()->id)
                ->select(
                    'user_profiles.id as user_profile_id',
                    'users.id as user_id',
                    'user_profiles.first_name',
                    'user_profiles.last_name',
                    'users.email',
                    'user_profiles.phone'
                )
                ->first();

            return response()->json(['code' => 200, 'message' => 'Record found successfully!', 'data' => $user_profile], 200);
        }
        return view('admin.profile.index');
    }

    public function update(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|string|max:255|unique:users,email,' . $request->user_id . ',id',
            'user_id' => 'required'
        ];

        if (isset($request->phone) && !empty($request->phone)) {
            $rules['phone'] = 'required|numeric|digits:10';
        }

        $request->validate($rules);

        if (isset($request->user_profile_id) && !empty($request->user_profile_id)) {
            $model = UserProfile::find($request->user_profile_id);
        } else {
            $model = new UserProfile();
            $model->user_id = $request->user_id;
        }

        $model->first_name = $request->first_name;
        $model->last_name = $request->last_name;
        $model->phone = (isset($request->phone) && !empty($request->phone)) ? $request->phone : null;
        $model->save();

        $user = User::find($request->user_id);
        $user->name =  $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->save();

        return response()->json(['code' => 200, 'message' => 'User profile updated successfully!', 'data' => $model], 200);
    }

    public function password()
    {
        return view('admin.profile.password');
    }

    public function passwordUpdate(Request $request)
    {

        $request->validate([
            'old_password' => 'required',
            'password' => 'confirmed|required|min:8|string|different:old_password'
        ]);

        if (!(Hash::check($request->old_password, Auth::user()->password))) {
            return response()->json(['code' => 400, 'errors' => ['old_password' => 'Your current password does not matches with the password.']], 400);
        }

        if (strcmp($request->old_password, $request->password) == 0) {
            return response()->json(['code' => 400, 'errors' => ['password' => 'New Password cannot be same as your current password.']], 400);
        }

        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['code' => 200, 'message' => 'Password changed successfully!', 'data' => $user], 200);
    }
}
