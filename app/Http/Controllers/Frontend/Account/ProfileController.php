<?php

namespace App\Http\Controllers\Frontend\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\returnData;
use function App\Helpers\returnSuccessMessage;
use function App\Helpers\returnValidationError;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        // $user = $request->user();
        $data['info'] = $request->user();
        $data['addresses'] = $request->user()->addresses()->latest()->get();

        $data['balance'] = $request->user()->wallet->balance ?? "0";
        $data['last_operations'] = Payment::where('wallet_id', $request->user()->wallet->id)->latest()->get();
        return view('frontend.profile.index', compact('data'));
    }


    public function update(Request $request)
    {
        $user = $request->user();
        $rules = [
            "name"          => "required|string|max:255",
            "phone"         => "required|string|max:255",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->with('tab', 'account')->withErrors($validator)->withInput();
        }

        $user->update($request->only([
            "name",
            "email",
            "phone",
            "address",
            "address2",
            "address_link",
        ]));
        return back()->with('success', __('Profile Updated Successful'));
    }

    public function delete_account(Request $request)
    {
        $user = $request->user();

        $user->update($request->only([
            "deleted_at" => now(),
        ]));
        return returnSuccessMessage(__('Deleted Successful'));
    }
}
