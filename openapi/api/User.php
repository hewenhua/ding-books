<?php
class User
{
    public static function getUserInfo($accessToken, $code)
    {
        $response = Http::get("/user/getuserinfo", 
            array("access_token" => $accessToken, "code" => $code));
               $response_json = json_encode($response);
               $response_arr = json_decode($response_json,true);
               $userId = isset($response_arr['userid']) && !empty($response_arr['userid']) ? $response_arr['userid'] : 0;
               if(!empty($userId)){
                       $response = self::get($accessToken,$userId);
                       $response = json_decode($response,true);
               }
         return json_encode($response);
     }
 
    public static function get($accessToken, $userId)
    {
               $response = Http::get("/user/get",
            array("access_token" => $accessToken, "userid" => $userId));
        return json_encode($response);
    }


    public static function simplelist($accessToken,$deptId){
        $response = Http::get("/user/simplelist",
            array("access_token" => $accessToken,"department_id"=>$deptId));
        return $response->userlist;

    }
}
