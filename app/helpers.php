<?php
namespace App;

use Illuminate\Http\JsonResponse;

class MyHelper{
    public function custom_response($message,$data=null,$token=null,$status=200,$type="success") : JsonResponse
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
            $response = $response->withCookie(cookie('token',$token,60*24*365));
        }
        
        return $response;
    }

    public function getPublicIdFromUrl(string $url)
    {
        $publicId = explode('/',$url);
        $publicId[9] = explode('.',$publicId[9])[0];
        $publicId = implode("/",[$publicId[7],$publicId[8],$publicId[9]]);

        return $publicId;
    }
}
    
?>