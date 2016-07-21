<?php
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/util/Log.php");
require_once(__DIR__ . "/util/Cache.php");
require_once(__DIR__ . "/api/Auth.php");
require_once(__DIR__ . "/api/User.php");
require_once(__DIR__ . "/api/Message.php");
require_once(__DIR__ . "/api/ISVClass.php");
$event = $_POST["event"];
switch($event){
    case '':
        echo json_encode(array("error_code"=>"4000"));
        break;
    case 'send_to_conversation':
        $sender = $_POST['sender'];
        $cid = $_POST['cid'];
        $content = $_POST['content'];
        $corpId = $_POST['corpId'];
        $corpInfo = ISVClass::getCorpInfo($corpId);
        $accessToken = $corpInfo['corpAccessToken'];
        $option = array(
            "sender"=>$sender,
            "cid"=>$cid,
            "msgtype"=>"text",
            "text"=>array("content"=>$content)
        );
        $response = Message::sendToConversation($accessToken,$option);
        echo json_encode($response);
        break;
    case 'get_userinfo':
        $corpId = $_POST['corpId'];
        $corpInfo = ISVClass::getCorpInfo($corpId);
        $accessToken = $corpInfo['corpAccessToken'];
        $code = $_POST["code"];
        Log::i("accessToken:".$accessToken."][code".$code);
        $userInfo = User::getUserInfo($accessToken, $code);
        Log::i("[userinfo]".json_encode($userInfo));
        echo json_encode($userInfo);
        break;
    case 'user_get':
	    $corpId = $_POST['corpId'];
        $corpInfo = ISVClass::getCorpInfo($corpId);
        $accessToken = $corpInfo['corpAccessToken'];
	    $userId = $_POST['userId'];
	    $userInfo = User::get($accessToken,$userId);
	    echo json_encode($userInfo);
	    break;
    case 'user_update':
	    $corpId = $_POST['corpId'];
        $corpInfo = ISVClass::getCorpInfo($corpId);
        $accessToken = $corpInfo['corpAccessToken'];
        $userId = $_POST['userId'];
	    $userName = $_POST['userName'];
	    $userInfo = User::update($accessToken,$userId);
	break;
    case 'space_share':
	    $corpId = $_POST['corpId'];
    	$userId = $_POST['userId'];
	    $info = $_POST['info'];
    	$info = json_decode($info,true);
	    Log::i("[userinfo]".var_export($info,true));
	break;
}
