<?php

namespace App\Http\Controllers\APIs\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\returnData;
use function App\Helpers\returnValidationError;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        // $user = $request->user();
        $data_response = new UserResource($request->user());
        return returnData('data', $data_response, __('Profile'));
    }


    public function update(Request $request)
    {
        $user = $request->user();
        $rules = [
            "name"          => "required|string|max:255",
            "phone"         => "required|max:255|unique:users,phone," . $user->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return returnValidationError("N/A", $validator);
        }
        
        $user->update($request->only([
            "name",
            "email",
            "phone",
            "address",
            "address2",
            "address_link",
        ]));
        $data_response = new UserResource($user);
        return returnData('data', $data_response, __('Profile Updated Successful'));
    }
}
