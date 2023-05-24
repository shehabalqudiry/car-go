<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function getLogin(Request $request)
    {
        return view('admin.auth.login');
    }
    public function login(Request $request)
    {
        $rules = [
            "email"             => "required|string",
            "password"          => "required|string",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $login = auth('admin')->attempt($request->only('email', 'password'), $request->remember ? true : false);

        if(!$login){
            return back()->withErrors(__('Register failed'));
        }

        return to_route('admin.index');

    }

    public function register(Request $request)
    {
        $admin = Admin::create([
            'name'      => 'admin',
            'email'     => 'admin@admin.com',
            'password'  => Hash::make('password'),
        ]);
        if (!$admin) {
            return "Done";
        }
        return to_route('admin.login');
    }
}
