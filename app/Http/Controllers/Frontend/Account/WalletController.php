<?php

namespace App\Http\Controllers\Frontend\Account;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\returnData;
use function App\Helpers\returnSuccessMessage;
use function App\Helpers\returnValidationError;

class WalletController extends Controller
{

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


        $data = $request->user()->wallet;

        $data->payments()->create([
            "value"             => $request->value,
            "type"              => 1,
            "user_id"           => $request->user()->id,
            "payment_method_id" => $request->payment_method_id,
            "description"       => $request->description,
        ]);
        return back()->with('success',__('Payment Successful'));
    }
}
