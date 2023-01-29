<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\UserCategoryInput;
use Illuminate\Http\Request;
use App\Services\AvatarUploader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function updateAvatar(Request $request, AvatarUploader $avatarUploader)
    {
        try {
            $user = $request->user();
            $image = $request->get('image');
            $avatar = $avatarUploader->upload($user, $image);

        } catch (\Exception $exception) {
            return response([
                'message' => "We can't process this file. Try another one.",
                'status' => 'error',
                'data' => null
            ], 422);
        }

        return response([
            'message' => 'Avatar was successfully updated',
            'status' => 'success',
            'data' => $avatar
        ]);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user()->toArray();
        $data = array_only($user, [
            'email',
            'first_name',
            'last_name',
            'age',
            'avatar',
            'location',
            'gender',
            'description'
        ]);

        return [
            'message' => '',
            'status' => 'success',
            'data' => $data
        ];
    }

    public function updateProfile(Request $request)
    {

        $categoryInputs = $request->categoryInputs;

        DB::beginTransaction();

        $data = $request->only([
            'first_name',
            'last_name',
            'age',
            'location',
            'gender',
            'description'
        ]);

        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Check form fields',
                'status' => 'success',
                'data' => $validator->messages()
            ],422);
        }

        $user = $request->user();
        $user->update($data);

        foreach (['description'] as $field) {
            $user->data()->updateOrCreate([
                'code' => $field
            ], [
                'value' => isset($data[$field]) ? $data[$field] : ''
            ]);
        }
        DB::commit();

        if(request()->has('categoryInputs')){
            $categoryInputs = request('categoryInputs');
            foreach ($categoryInputs as $index => $categoryInput){
                if($categoryInput !== null) {
                    UserCategoryInput::updateOrCreate([
                        'user_id' => Auth::id(),
                        'post_category_id' => $index],
                    ['input' => $categoryInput]);
                }
            }
        }


        return [
            'message' => 'Profile was successfully updated',
            'status' => 'success',
            'data' => null
        ];
    }

    public function updateEmail(Request $request)
    {
        $data = $request->only(['email']);

        $validator = Validator::make($data, [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Check form fields',
                'status' => 'success',
                'data' => $validator->messages()
            ],422);
        }

        $user = $request->user();
        $user->update($data);

        return [
            'message' => 'Email was successfully updated',
            'redirect' => url('settings/account'),
            'status' => 'success',
            'data' => null
        ];
    }

    public function updatePassword(Request $request)
    {
        $data = $request->only(['current_password', 'new_password', 'new_password_confirmation']);

        $validator = Validator::make($data, [
            'current_password' => 'required|string',
            'new_password' => 'required|string|confirmed',
            'new_password_confirmation' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Check form fields',
                'status' => 'success',
                'data' => $validator->messages()
            ],422);
        }

        $user = $request->user();

        if (!Hash::check($data['current_password'], $user->password)) {
            return response([
                'message' => 'Check form fields',
                'status' => 'success',
                'data' => ['current_password' => 'Current password is invalid.']
            ],422);
        }

        $user->password = bcrypt($data['new_password']);
        $user->save();

        return [
            'message' => 'Password was successfully changed',
            'redirect' => url('settings/account'),
            'status' => 'success',
            'data' => null
        ];
    }
}
