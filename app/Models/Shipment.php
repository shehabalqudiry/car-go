<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    
    protected $guarded = [];    
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusString()
    {
        switch ($this->status) {
            case 0:
                return __('Under Processing');
                break;
            
            case 1:
                return __('On the way to you');
                break;
            
            case 2:
                return __('The shipment has been received');
                break;
            
            default:
                # code...
                break;
        }
    }
}
