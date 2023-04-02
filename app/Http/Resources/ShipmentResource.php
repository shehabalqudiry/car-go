<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        $data = [
            "id"                                => $this->id,
            "user"                              => $this->user->name ?? '',
            "type_string"                       => $this->type == 0 ? "نقل شحنة" : "تخليص جمركي",
            "type_int"                          => $this->type,
            "shipment_type"                     => $this->shipment_type,
            "delivery_date"                     => $this->delivery_date,
            "shipment_value"                    => $this->shipment_value,
            "shipment_weight"                   => $this->shipment_weight,
            "shipment_temperature"              => $this->shipment_temperature,
            "shipment_consignee_name"           => $this->shipment_consignee_name,
            "shipment_from_lat"                 => $this->shipment_from_lat,
            "shipment_from_long"                => $this->shipment_from_long,
            "shipment_to_lat"                   => $this->shipment_to_lat,
            "shipment_to_long"                  => $this->shipment_to_long,
            "shipment_consignee_city"           => $this->shipment_consignee_city,
            "shipment_consignee_address"        => $this->shipment_consignee_address,
            "shipment_consignee_address_short"  => $this->shipment_consignee_address_short,
            "shipment_consignee_address_url"    => $this->shipment_consignee_address_url,
            "shipment_port"                     => $this->shipment_port ?? '',
            "shipment_bill_of_lading"           => $this->shipment_bill_of_lading ?? '',
            "shipment_bill_of_lading_file"      => $this->shipment_bill_of_lading_file ?? '',
            "shipment_annulment_number"         => $this->shipment_annulment_number ?? '',
            "shipment_annulment_file"           => $this->shipment_annulment_file ?? '',
            "status_string"                     => $this->getStatusString(),
            "status_int"                        => $this->status,
            "payment_method"                    => $this->payment_method,
            "shipment_number"                   => $this->shipment_number,
            "total"                             => $this->total,
            "vat"                               => $this->vat,
            "customs_declaration_fees"          => $this->customs_declaration_fees ?? '',
            "delivery_authorization"            => $this->delivery_authorization ?? '',
            "customs_clearance"                 => $this->customs_clearance ?? '',
            "shipment_delivery"                 => $this->shipment_delivery ?? '',
            "translate"                         => $this->translate ?? '',
            "workers_wages"                     => $this->workers_wages ?? '',
            // "created_at"                        => "2023-03-31T20:57:06.000000Z",
            // "updated_at"                        => "2023-03-31T20:57:06.000000Z"
        ];
        return $data;
    }
}
