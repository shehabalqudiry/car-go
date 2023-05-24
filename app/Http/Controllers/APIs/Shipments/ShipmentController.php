<?php

namespace App\Http\Controllers\APIs\Shipments;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShipmentResource;
use App\Models\Shipment;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\Coupon;
use App\Models\Weight;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use function App\Helpers\otp_generate;
use function App\Helpers\distance;
use function App\Helpers\returnData;
use function App\Helpers\returnError;
use function App\Helpers\returnValidationError;
use function App\Helpers\settings;
use function App\Helpers\shipment_number;

class ShipmentController extends Controller
{
    public function createShipment(Request $request)
    {

        $rules = [
            "type"                              => "required|in:0,1",
            "shipment_consignee_id"             => "nullable|exists:users,number",
        ];

        $validator = Validator::make($request->all(), $rules, attributes:[
            'shipment_consignee_id' => "الرقم التعريفي"
        ]);

        if ($validator->fails()) {
            return returnValidationError("N/A", $validator);
        }

        $kilometer = distance($request->shipment_from_lat, $request->shipment_from_long, $request->shipment_to_lat, $request->shipment_to_lat);
        $weight = Weight::where([['from', '<=', $request->shipment_weight],['to', '>=', $request->shipment_weight]])->first()->price ?? null;
        // $worker = $request->need_workers ? $request->need_workers * settings()->worker_cost : 0;
        $total = (floatval($kilometer) * ($weight ?? settings()->kilo_cost));
        $vat = 0.15 * $total;


        $data = [
            "user_id"                           => $request->user()->id,
            "type"                              => $request->type,
            "shipment_type"                     => $request->shipment_type,
            "delivery_date"                     => $request->delivery_date,
            "shipment_value"                    => $request->shipment_value,
            "shipment_weight"                   => $request->shipment_weight,
            "shipment_temperature"              => $request->shipment_temperature,
            "shipment_consignee_name"           => $request->shipment_consignee_name,
            "shipment_from_lat"                 => $request->shipment_from_lat,
            "shipment_from_long"                => $request->shipment_from_long,
            "shipment_from_address"             => $request->shipment_from_address,
            "shipment_to_lat"                   => $request->shipment_to_lat,
            "shipment_to_long"                  => $request->shipment_to_long,
            "shipment_consignee_city"           => $request->shipment_consignee_city,
            "shipment_consignee_address"        => $request->shipment_consignee_address,
            "shipment_consignee_address_short"  => $request->shipment_consignee_address_short,
            "shipment_consignee_address_url"    => $request->shipment_consignee_address_url,
            "shipment_port"                     => $request->shipment_port,
            "shipment_bill_of_lading"           => $request->shipment_bill_of_lading,
            "shipment_bill_of_lading_file"      => $request->shipment_bill_of_lading_file,
            "shipment_annulment_number"         => $request->shipment_annulment_number,
            "shipment_annulment_file"           => $request->shipment_annulment_file,
            "description"                       => $request->description,
            "need_workers"                      => $request->need_workers,
            "workers_cost"                      => $request->need_workers ? $request->need_workers * settings()->worker_cost : null,
            "shipment_consignee_id"             => $request->shipment_consignee_id,
            "shipment_number"                   => shipment_number(),
            "total"                             => number_format($total, 2, '.', ''),
            "vat"                               => number_format($vat, 2, '.', ''),
        ];

        try{
            DB::beginTransaction();
            $shipment = Shipment::create($data);

            $shipment->status()->create([
                "status" => 0,
            ]);

            $data_response = new ShipmentResource($shipment);
            DB::commit();
            return returnData('data', $data_response, __('Shipment Created Successful'));
        }catch(\Exception $ex){
            DB::rollback();
            return returnError('ERROR_01', $ex->getMessage());
        }

    }

    public function shipments_pay(Request $request)
    {

        $rules = [
            "shipment_number"                   => "required|exists:shipments,shipment_number",
            "payment_method"                    => "required|exists:payment_methods,id",
            "coupon_code"                       => "nullable|exists:coupons,coupon_code",
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return returnValidationError("N/A", $validator);
        }

        try{
            DB::beginTransaction();
            $shipment = Shipment::where("shipment_number", $request->shipment_number)->first();

            $shipment->status()->create([
                "status" => 1,
            ]);

            $shipment->update([
                "all_total" => $request->all_total,
            ]);

            $total = $shipment->all_total;
            $wallet = $request->user()->wallet;
            $paymentMethod = PaymentMethod::where('id', $request->payment_method)->first();

            if ($paymentMethod and $paymentMethod->type == "wallet"){
                // dd($wallet);
                if ($wallet->balance >= $total){
                    $wallet->payments()->create([
                        "value"             => "-$total",
                        "type"              => 2,
                        "status"            => 1,
                        "user_id"           => $request->user()->id,
                        "payment_method_id" => $request->payment_method,
                        "description"       => "تم دفع $total لشحنة رقم $shipment->shipment_number",
                    ]);
                } else {
                  return returnError('ERROR_01', __('There is not enough balance'));
                }
            }

            $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();
            if ($coupon) {
                if ($coupon->number <= 0) {
                    return returnError(200, 'تم بلوغ الحد الاقصى من استخدام هذا الكوبون');
                }
                //check if user used this code before
                $checkUsed = DB::table('user_coupons')->where('user_id', $request->user()->id)->where('coupon_id', $coupon->id)->first();

                if (!$checkUsed and $total !== 0) {
                    //no quantity after update
                    $discount = $coupon->value;
                    $total = $total - $discount;
                    //update coupon times
                    $number = Coupon::find($coupon->id);
                    $number->update([
                        'number' => $coupon->number - 1
                    ]);
                    $shipment->update([
                        'coupon_id' => $coupon->id,
                        'total_discount' => $total
                    ]);
                    $checkUsed = DB::table('user_coupons')->insert(['user_id' => $request->user()->id, 'coupon_id' => $coupon->id]);
                } else {
                    return returnError(502, 'لقد قمت باستخدام هذا الكوبون من قبل');
                }
            }
            $data_response = new ShipmentResource($shipment);
            DB::commit();
            return returnData('data', $data_response, __('Shipment Created Successful'));
        }catch(\Exception $ex){
            DB::rollback();
            return returnError('ERROR_01', $ex->getMessage());
        }

    }

    public function my_shipments(Request $request)
    {

        $shipments = $request->user()->shipments()->latest()->get();
        $shipments_incoming = Shipment::where('shipment_consignee_id', $request->user()->number)->latest()->get();

        $data_response['outgoing'] = ShipmentResource::collection($shipments);
        $data_response['incoming'] = ShipmentResource::collection($shipments_incoming);
        // $data_response = $shipment;
        return returnData('data', $data_response, __('Done.'));
    }

    public function tracking_shipment(Request $request)
    {
        $shipment = $request->user()->shipments()->where('shipment_number', $request->shipment_number)->first();
        if(!$shipment){
            return returnError('404', __('No Results Found.'));
        }
        $data_response = new ShipmentResource($shipment);

        return returnData('data', $data_response, __('Done.'));
    }

     public function checkCoupon(Request $request)
        {
            //rules for validation
            $rules = [
                'coupon_code' => 'required',
            ];

            //validation
            $validator = validator()->make($request->all(), $rules);

            //validation failure

            if ($validator->fails()) {
                return returnError("0000", $validator->errors()->first());
            }

            $coupon = Coupon::where('coupon_code', $request->coupon_code)->first();

            if ($coupon) {
                //check if user used this code before
                $checkUsed = DB::table('user_coupons')->where('user_id', $request->user()->id)->where('coupon_id', $coupon->id)->first();

                if (!$checkUsed) {
                    //no quantity after update
                    $discount = $coupon->value;

                    return returnData('data', [
                        'discount' => $discount
                    ], __("Done."));
                } else {
                    return returnData('data', [
                        'discount' => 0
                    ], __('The provided coupon code is used.'));
                }
            } else {
                return returnData('data', [
                    'discount' => 0
                ], __('The provided coupon code is invalid.'));
            }
        } //end of check coupon
}
