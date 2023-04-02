<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShipmentResource;
use App\Models\Contact;
use App\Models\Shipment;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\returnData;
use function App\Helpers\returnSuccessMessage;
use function App\Helpers\returnValidationError;

class HomeController extends Controller
{
    public function home(Request $request)
    {
        $sliders = Slider::latest()->get();
        $latest_shipments = Shipment::where('type', 0)->latest()->take(10)->get();
        $latest_customs_clearance = Shipment::where('type', 1)->latest()->take(10)->get();
        $data = [
            'sliders'       => $sliders,
            'latest_shipments'       => ShipmentResource::collection($latest_shipments),
            'latest_customs_clearance'       => ShipmentResource::collection($latest_customs_clearance),
        ];
        return returnData('data', $data, __('Done.'));
    }
    public function contact(Request $request)
    {
        $rules = [
            "name"                      => "required|string",
            "phone"                     => "required|string",
            "email"                     => "nullable|string|email",
            "subject"                   => "required|string",
            "message"                   => "required|string",
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return returnValidationError("N/A", $validator);
        }

        $data = [
            "name"                      => $request->name,
            "phone"                     => $request->phone,
            "email"                     => $request->email,
            "subject"                   => $request->subject,
            "msg"                       => $request->message,
        ];

        Contact::create($data);

        return returnSuccessMessage(__('Send Done'));
    }

}
