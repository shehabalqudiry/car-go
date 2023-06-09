<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'name'              => $this->name,
            'phone'             => $this->phone,
            'email'             => $this->email ?? "NA",
            'status'            => $this->status == 0 ? "غير مؤكد" : "تم تأكيد الحساب",
            'address'           => $this->address ?? "",
            'address_short'     => $this->address2 ?? "",
            'address_link'      => $this->address_link ?? "",
            'api_token'         => $this->api_token,
            'otp'               => $this->otp,
            'user_number'       => $this->number,
        ];
        return $data;
    }
}
