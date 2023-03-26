<?php

namespace App\Http\Controllers\APIs\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\returnValidationError;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            "name"          => "required|string|max:255",
            "phone"         => "required|max:255|unique:users,phone",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return returnValidationError("N/A", $validator);
        }

        $data = [
            'name'      => $request->name,
            'phone'     => $request->phone,
        ];

        $user = User::create($data);

        $credentials = $request->only(['phone', 'password']);
        $token = auth('user')->attempt($credentials);

        $token = $user->createToken($request->ip())->plainTextToken;
    }


    public function login(Request $request)
    {
        # code...
    }
}
