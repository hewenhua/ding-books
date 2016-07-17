<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function example(){
	$CI =& get_instance();
	if($CI->session->userdata('msg') === FALSE)
	{
		return NULL;
	}
}

function echoFail($error_msg)
{
	$output = array(
		'result' => 0 ,
		'msg' => $error_msg
		);
    error_log(json_encode($output));
	echo json_encode($output);
}

function echoSucc($succ_msg)
{
	$output = array(
		'result' => 1 ,
		'msg' => $succ_msg
		);
	echo json_encode($output);
}

function isLogin(){
	$CI =& get_instance();
	$result = $CI->session->userdata('user_id');
	return !empty($result);
}


function activeButton($name){
	$CI =& get_instance();
	if($CI->uri->segment(2) == $name){
		echo $name;
	}else{
		$target_url = site_url("space/$name");
		echo "<a href='$target_url'>$name</a>";
	}
}

function isActive($name){
	$CI =& get_instance();
	return ($CI->uri->segment(2) == $name);
}

function echo_exist($var){
	if(!empty($var) AND isset($var))
		echo $var;
}


// if search does not specify limit , it will automatic add one - 50 
function addLimit( $limit , $offset )
{
	if(!empty($limit))
	{
		$limit = ($limit < 0)?0:$limit;
		if(!empty($offset) )
		{
			$offset = ($offset < 0)?0:$offset;
			return " LIMIT $offset , $limit " ;
		}
		else
			return " LIMIT 0 , $limit " ;
	}
	else
		return " LIMIT 0 , 10 " ;
}

function url_maker($search_data , $pre_url , $order )
{
	foreach ($search_data as $key => $value) {
		if(empty($value))
			unset($search_data[$key]);
	}
	$search_data[$order['name']] = $order['value'];
	return $pre_url.'?'.http_build_query($search_data);
}

function getTradeStatusName($statusNum){
	$convert_arr = array(
		1 => 'initial' ,
		2 => 'accepted' ,
		3 => 'denied' ,
		4 => 'canceled' ,
		5 => 'returned' ,
		6 => 'lost'
		);
	return $convert_arr[$statusNum];
}

function getItemStatusName($statusNum){
	$convert_arr = array(
		1 => 'sharing' ,
		2 => 'unshare' ,
		3 => 'deleted' ,
		4 => 'borrowed' ,
		);
	return $convert_arr[$statusNum];
}

function format_date($datetime){
        $time = strtotime($datetime);
            if ($time < 0 ) {
                return "";
            }

            $d = time() - intval($time);
            if($d<60){
                return "刚刚";
            }

            if( $d>=60 && $d< 30*60){
                $m = intval($d / 60);
                return "{$m}分钟前";
            }

            if( $d>= 30*60 && $d< 60*60){
                $m = intval($d / 60);
                return "半小时前";
            }

            if( $d>= 60*60 && $d< 2*60*60){
                return "1小时前";
            }

            //昨天
            $yesterday = date("Y-m-d 23:59:59",strtotime("-1 day"));
            $yesterday = strtotime($yesterday);
            if( $d>=2*3600 && $time > $yesterday){
                $h = intval($d/3600);
                return "今天 ".date("H:i",$time);
            }

            //前天
            $byesterday = $yesterday - 86400;

            if( $time>$byesterday && $time <= $yesterday){
                return "昨天 ".date("H:i",$time);
            }

            //今年
            $year = date("Y-01-01 00:00:00");
            $year = strtotime($year);
            if( $time>=$year && $time<$byesterday){
                return date("m-d H:i",$time);
            }

            return date("Y-m-d H:i",$time);
    }


