<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentStatus extends Model
{
    use HasFactory;

    protected $table = 'shipment_status';
    protected $guarded = [];
    protected $appends = ['status_string'];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function getStatusStringAttribute()
    {
        switch ($this->status) {
            case 0:
                return __('Awaiting Payment');
                break;

            case 1:
                return __('Under Processing');
                break;

            case 2:
                return __('On the way to you');
                break;

            case 3:
                return __('The shipment has been received');
                break;

            default:
                return __('The shipment has been received');
                # code...
                break;
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function getUpdatedAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }
}
