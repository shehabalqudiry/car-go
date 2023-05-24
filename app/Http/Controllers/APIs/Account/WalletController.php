<?php

namespace App\Http\Controllers\APIs\Account;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\returnData;
use function App\Helpers\returnSuccessMessage;
use function App\Helpers\returnValidationError;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->wallet) {
            $request->user()->wallet()->create([
                'user_id' => $request->user()->id,
            ]);
        }
        $data['balance'] = "0";
        $data['last_operations'] = [];
        if ($request->user()->wallet) {
            $data['balance'] = $request->user()->wallet->balance ?? "0";
            $data['last_operations'] = Payment::where('wallet_id', $request->user()->wallet->id)->latest()->get() ?? [];
        }

        return returnData('data', $data, __('Done.'));
    }

    public function last_operations(Request $request)
    {
        $data = $request->user()->wallet();

        return returnData('data', $data, __('Done.'));
    }

    public function add_credit(Request $request)
    {
        $rules = [
            "value"                              => "required",
            "payment_method_id"                  => "required|exists:payment_methods,id",
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return returnValidationError("N/A", $validator);
        }

        // foreach (User::get() as $value) {
        //     if (!$value->wallet) {
        //         $value->wallet()->create([
        //             'user_id' => $value->id
        //         ]);
        //     }
        // }

        $data = $request->user()->wallet;

        $data->payments()->create([
            "value"             => $request->value,
            "type"              => 1,
            "user_id"           => $request->user()->id,
            "payment_method_id" => $request->payment_method_id,
            "description"       => $request->description,
        ]);
        return returnSuccessMessage(__('Payment Successful'));
    }
}
