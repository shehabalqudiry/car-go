<?php


namespace App\Helpers;

use App\Models\Shipment;
use App\Models\User;

function distance($lat1, $lon1, $lat2, $lon2) {
    $radius = 6371; // Earth's radius in kilometers
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $radius * $c; // Distance in kilometers
    return number_format($distance, 2);
}

function returnError($errNum, $msg, $status = false)
{
    return response()->json([
        'status' => $status,
        'errNum' => $errNum,
        'msg' => $msg
    ]);
}

function otp_generate()
{
    $number = mt_rand(1000, 9999);
    if (User::where('otp', $number)->exists()) {
        otp_generate();
    }
    return $number;
}


function shipment_number()
{
    $number = mt_rand(100000000, 999999999);
    if (Shipment::where('shipment_number', $number)->exists()) {
        otp_generate();
    }
    return $number;
}


function returnSuccessMessage($msg = "", $errNum = "S000", $status = true)
{
    return [
        'status' => $status,
        'errNum' => $errNum,
        'msg' => $msg
    ];
}

function returnData($key, $value, $msg = "", $status = true)
{
    return response()->json([
        'status' => $status,
        'errNum' => "S000",
        'msg' => $msg,
        $key => $value
    ]);
}

function returnValidationError($code = "N/A", $validator = null)
{
    return returnError($code, $validator->errors()->first());
}
