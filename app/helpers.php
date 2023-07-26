<?php
namespace App\Helper;

use Illuminate\Http\JsonResponse;

    function custom_response($message,$data=null,$token=null,$status=200,$type="success") : JsonResponse
    {
        $response = response()->json(
            [
                'type' => 'success',
                'message' => $message,
                'status' => $status,
                'data' => $data
            ],
            $status
        );
        if($token){
            $response = $response->cookie('token',$token);
        }
        
        return $response;
    }
?>