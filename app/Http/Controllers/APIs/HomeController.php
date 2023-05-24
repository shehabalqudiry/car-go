<?php

namespace App\Http\Controllers\APIs;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShipmentResource;
use App\Models\City;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\Shipment;
use App\Models\Slider;
use App\Models\Term;
use App\Models\Weight;
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
        $latest_shipments = Shipment::where('user_id', $request->user()->id)->latest()->take(4)->get();
        $data = [
            'sliders'                           => $sliders,
            'latest_shipments'                  => ShipmentResource::collection($latest_shipments),
            'total_shipments'                   => Shipment::where('user_id', $request->user()->id)->count(),
            'on_the_way_shipments'              => Shipment::where('user_id', $request->user()->id)->where('status', 1)->count(),
            'received_shipments'                => Shipment::where('shipment_consignee_id', $request->user()->number)->where('status', 1)->count(),
            'delivered_shipments'               => Shipment::where('user_id', $request->user()->id)->where('status', 2)->count(),
        ];
        return returnData('data', $data, __('Done.'));
    }

    public function cities(Request $request)
    {
        $data = City::where('status', 1)->get();
        return returnData('data', $data, __('Done.'));
    }

    public function createCoupon(Request $request)
    {
        Coupon::create([
            "coupon_code" => $request->coupon_code,
            "value" => $request->coupon_value,
            "number" => 10,
        ]);

        $data = Coupon::get();
        return returnData('data', $data, __('Done.'));
    }
    public function weights(Request $request)
    {
        $data = Weight::get();
        return returnData('data', $data, __('Done.'));
    }

    public function payment_methods(Request $request)
    {
        $data = PaymentMethod::where('status', 1)->get();
        return returnData('data', $data, __('Done.'));
    }

    public function terms(Request $request)
    {
        $data = Term::where('status', 1)->get();
        return returnData('data', $data, __('Done.'));
    }

    public function support(Request $request)
    {
        $setting = Setting::first();
        $data = [];
        if ($setting) {
            $data = [
                'whatsapp' => $setting->whatsapp,
                'twitter' => $setting->twitter,
                'phone' => $setting->phone,
                'email' => $setting->email,
                'google_play_url' => $setting->google_play_url,
                'app_store_url' => $setting->app_store_url,
            ];
        }
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
