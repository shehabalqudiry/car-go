<?php

namespace App\Http\Controllers\APIs\Shipments;

use App\Http\Controllers\Controller;
use App\Http\Resources\ShipmentResource;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function App\Helpers\distance;
use function App\Helpers\returnData;
use function App\Helpers\returnValidationError;
use function App\Helpers\shipment_number;

class ShipmentController extends Controller
{
    public function createShipment(Request $request)
    {
        $rules = [
            "type"                              => "required|in:0,1",
            "delivery_date"                     => "required_if:type,1",
            // "shipment_value"                    => "",
            // "shipment_weight"                   => "",
            // "shipment_temperature"              => "",
            // "shipment_consignee_name"           => "",
            // "shipment_from_lat"                      => "",
            // "shipment_from_long"                     => "",
            // "shipment_to_lat"                      => "",
            // "shipment_to_long"                     => "",
            // "shipment_consignee_city"           => "",
            // "shipment_consignee_address"        => "",
            // "shipment_consignee_address_short"  => "",
            // "shipment_consignee_address_url"    => "",
            // "shipment_port"                     => "",
            // "shipment_bill_of_lading"           => "",
            // "shipment_bill_of_lading_file"      => "",
            // "shipment_annulment_number"         => "",
            // "shipment_annulment_file"           => "",
            // "payment_method"                    => "",
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return returnValidationError("N/A", $validator);
        }

        $kilometer = distance($request->shipment_from_lat, $request->shipment_from_long, $request->shipment_to_lat, $request->shipment_to_lat);
        $total = $kilometer * 1;
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
            "payment_method"                    => $request->payment_method,
            "shipment_number"                   => shipment_number(),
            "total"                             => number_format($total, 2, '.', ''),
            "vat"                               => number_format($vat, 2, '.', ''),
            // "customs_declaration_fees"          => "",
            // "delivery_authorization"            => "",
            // "customs_clearance"                 => "",
            // "shipment_delivery"                 => "",
            // "translate"                         => "",
            // "workers_wages"                     => "",
        ];

        $shipment = Shipment::create($data);

        $data_response = new ShipmentResource($shipment);
        // $data_response = $shipment;
        return returnData('data', $data_response, __('Shipment Created Successful'));
    }

    public function my_shipments(Request $request)
    {
        $shipments = $request->user()->shipments()->latest()->get();


        $data_response = ShipmentResource::collection($shipments);
        // $data_response = $shipment;
        return returnData('data', $data_response, __('Done.'));
    }
}
