<?php

namespace App\Http\Controllers\Frontend\Account;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\returnData;
use function App\Helpers\returnValidationError;

class AddressController extends Controller
{

    public function store(Request $request)
    {
        $rules = [
            "city"              => "required|string",
            "district"          => "required|string",
            "street"            => "required|string",
        ];

        $attributes = [
            "city"              => __('City'),
            "district"          => __('District'),
            "street"            => __('Street'),
        ];

        $validator = Validator::make($request->all(), $rules, attributes:$attributes);

        if ($validator->fails()) {
            return back()->with('tab', 'address')->withErrors($validator)->withInput();
        }

        $addresses = $request->user()->addresses()->count();
        if ($addresses >= 3) {
            return back()->with(['error'=> __("You can't create more than 3 Addresses"), 'tab' => 'address']);
        }
        $address = $request->user()->addresses()->create([
            "city"              => $request->city,
            "district"          => $request->district,
            "street"            => $request->street,
            "description"       => $request->description,
        ]);
        return back()->with(['success'=> __('Created Successful'), 'tab' => 'address']);

    }

    public function edit(Request $request, $address)
    {
        $address = $request->user()->addresses()->findOrFail($address);
        return view('frontend.addresses.edit', compact('address'));
    }

    public function update(Request $request, $address)
    {
        $rules = [
            "city"              => "required|string",
            "district"          => "required|string",
            "street"            => "required|string",
        ];

        $attributes = [
            "city"              => __('City'),
            "district"          => __('District'),
            "street"            => __('Street'),
        ];

        $validator = Validator::make($request->all(), $rules, attributes:$attributes);

        if ($validator->fails()) {
            return back()->with('tab', 'address')->withErrors($validator)->withInput();
        }

        $address = $request->user()->addresses()->findOrFail($address);
        $address->update([
            "city"              => $request->city,
            "district"          => $request->district,
            "street"            => $request->street,
            "description"       => $request->description,
        ]);
        return redirect()->route('front.profile.index')->with(['success'=> __('Updated Successful'), 'tab' => 'address']);

    }

    public function destroy(Request $request , $address)
    {
        $address = $request->user()->addresses()->findOrFail($address);
        // $address->update(['deleted_at' => now()]);
        $address->delete();
        return back()->with(['success'=> __('Deleted Successful'), 'tab' => 'address']);
    }
}
