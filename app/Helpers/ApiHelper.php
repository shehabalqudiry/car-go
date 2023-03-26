<?php


namespace App\Helpers;


function returnError($errNum, $msg, $status = false)
{
    return response()->json([
        'status' => $status,
        'errNum' => $errNum,
        'msg' => $msg
    ]);
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
