<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShipmentResource;
use App\Models\City;
use App\Models\Contact;
use App\Models\PaymentMethod;
use App\Models\Setting;
use App\Models\Shipment;
use App\Models\SliderWeb;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\returnData;
use function App\Helpers\returnSuccessMessage;
use function App\Helpers\returnValidationError;

class HomeController extends Controller
{
    public function home(Request $request)
    {

        $latest_shipments = Shipment::where('type', 0)->latest()->take(10)->get();
        $latest_customs_clearance = Shipment::where('type', 1)->latest()->take(10)->get();

        $sliders                           = SliderWeb::latest()->get();
        $total_shipments                   = Shipment::count();
        $on_the_way_shipments              = Shipment::where('status', 1)->count();
        $delivered_shipments               = Shipment::where('status', 2)->count();

        return view('frontend.index', compact('sliders','latest_shipments', 'latest_customs_clearance', 'total_shipments', 'on_the_way_shipments', 'delivered_shipments'));
    }


    public function payment_methods(Request $request)
    {
        $data = PaymentMethod::where('status', 1)->get();
        return returnData('data', $data, __('Done.'));
    }

    public function terms(Request $request)
    {
        $data = Term::where('status', 1)->get();
        return view('frontend.pages.terms', compact('data'));
    }

    public function get_contact_page(Request $request)
    {
        $setting = Setting::first();

        return view('frontend.pages.contact-us', compact('setting'));
    }

    public function about_us(Request $request)
    {
        $setting = Setting::first();

        return view('frontend.pages.about-us', compact('setting'));
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
            return back()->withErrors($validator)->withInput();
        }

        $data = [
            "name"                      => $request->name,
            "phone"                     => $request->phone,
            "email"                     => $request->email,
            "subject"                   => $request->subject,
            "msg"                       => $request->message,
        ];

        Contact::create($data);

        return back()->with('success', __('Send Done'));
    }

}
