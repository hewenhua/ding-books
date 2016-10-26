<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function register()
	{	
		$result = $this->session->userdata('user_id');
		if(!empty($result) ){
			redirect('space/profile');
		}

		$this->load->view('include/header');
		$this->load->view('user/register');
		$this->load->view('include/footer');
	}

	public function login()
	{
		$result = $this->session->userdata('user_id');
		if(!empty($result)){
			redirect('share/book');
		}

		$this->load->view('include/header');
		$this->load->view('user/login');
		$this->load->view('include/footer');
	}

	public function processLogout(){
		$this->session->sess_destroy();
		redirect('user/logout');
	}

	public function logout(){
		$this->load->view('include/header');
		$this->load->view('user/logout');
		$this->load->view('include/footer');
	}

    public function getUserInfo(){
        $corpId = $_REQUEST['corpId'];
        $corpInfo = ISVClass::getCorpInfo($corpId);
        $accessToken = $corpInfo['corpAccessToken'];
        $code = $_REQUEST["code"];
        $userInfo = $this->getDingUserInfo($accessToken, $code);
        echo json_encode($userInfo);
    }

    public function getDingUserInfo($accessToken, $code)
    {
        $response = Http::get("/user/getuserinfo",
            array("access_token" => $accessToken, "code" => $code));
               $response_json = json_encode($response);
               $response_arr = json_decode($response_json,true);
               $userId = isset($response_arr['userid']) && !empty($response_arr['userid']) ? $response_arr['userid'] : 0;
               if(!empty($userId)){
                       $response = $this->get($accessToken,$userId);
                       $response = json_decode($response,true);
               }
         return json_encode($response);
     }

    public function get($accessToken, $userId)
    {
               $response = Http::get("/user/get",
            array("access_token" => $accessToken, "userid" => $userId));
        return json_encode($response);
    }

}
