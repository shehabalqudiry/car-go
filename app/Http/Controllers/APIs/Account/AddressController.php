<?php

namespace App\Http\Controllers\APIs\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\returnData;
use function App\Helpers\returnValidationError;

class AddressController extends Controller
{
    public function index(Request $request)
    {
        $addresses = $request->user()->addresses()->latest()->get();
        return returnData('data', $addresses, __('Done.'));
    }
    
    public function store(Request $request)
    {
        $rules = [
            "city"              => "required|string",
            "district"          => "required|string",
            "street"            => "required|string",
        ];
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return returnValidationError("N/A", $validator);
        }

        $address = $request->user()->addresses()->create([
            "city"              => $request->city,
            "district"          => $request->district,
            "street"            => $request->street,
            "description"       => $request->description,
        ]);
        return returnData('data', $address, __('Created Successful'));

    }
}
