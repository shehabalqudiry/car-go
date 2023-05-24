<?php

namespace App\Http\Controllers\APIs\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\returnData;
use function App\Helpers\returnError;
use function App\Helpers\returnSuccessMessage;
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

    public function update(Request $request)
    {
        $rules = [
            "address_id"        => "required",
            "city"              => "required|string",
            "district"          => "nullable|string",
            "street"            => "nullable|string",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return returnValidationError("N/A", $validator);
        }
        $address = $request->user()->addresses()->where('id', $request->address_id)->first();
        if (!$address) {
            return returnError('404', __('Not Found.'));
        }

        $address->update([
            "city"              => $request->city ? $request->city : $address->city,
            "district"          => $request->district ? $request->district : $address->district,
            "street"            => $request->street ? $request->street : $address->street,
            "description"       => $request->description ? $request->description : $address->description,
        ]);

        return returnData('data', $address, __('Updated Successful'));

    }

    public function destroy(Request $request)
    {
        $address = $request->user()->addresses()->where('id', $request->address_id)->first();

        if (!$address) {
            return returnError('404', __('Not Found.'));
        }

        $address->delete();

        return returnSuccessMessage(__('Deleted Successful'));
    }
}
