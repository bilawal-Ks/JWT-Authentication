<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    public function SuccessResponse($result, $data, $message, $code = 200)
    {
    	$response = [
            'data'    => $result,
            'UserData'=> $data,
            'message' => $message,
            'code'=> $code,
        ];
        return response()->json($response, 200);
    }
    public function ErrorResponse($error, $errorMessages = [], $code)
    {
    	$response = [
            'message' => $error,
            'code'=>$code,
        ];


        // if(!empty($errorMessages)){
        //     $response['data'] = $errorMessages;
        // }


        return response()->json($response, $code);
    }
}