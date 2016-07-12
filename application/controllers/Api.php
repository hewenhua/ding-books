<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

	public function index()
	{
		echo "success";exit;
		echoSucc('success!');
		//$this->load->view('welcome_message');
	}
	
	public function userUpdate()
	{
		$userid = $this->input->get_post('userId'); 
		$user_name = $this->input->get_post('userName');
		$corp_id = $this->input->get_post('corpId');
		$input = array(
						'cellphone' => $userid,
						'username' => $user_name,
						'password' => $userid,
						'userid' => $userid,
						'corpid' => $corp_id,
					  );

		$query = $this->db->query("SELECT * FROM user WHERE username = '$user_name' AND userid = '$userid' AND corpid = '$corp_id' ");

		$this->load->model('user_model');
        if($query->num_rows() == 0){
			list($result , $msg) = $this->user_model->create($input);
	        if($result == FALSE){
	            echoFail($msg);
	            return FALSE;
	        }
	
		}else{
			/*$row = $query->first_row();
			$id = $row->id;	
			unset($input['password']);
			$this->user_model->updateProfile($input,$id);*/
		}

		$this->user_model->processLogin($input['cellphone']);
		echoSucc('register succ');
        return TRUE;
	}

	public function userRegister()
	{
		$input = array(
			'cellphone' => $this->input->get_post('cellphone') ,
			'password' => $this->input->get_post('password') ,
			'confirm' => $this->input->get_post('confirm') ,
			);

		if(empty($input['cellphone'])){
			echoFail('Cellphone field is empty');
			return FALSE;
		}

		if(empty($input['password'])){
			echoFail('Password field is empty');
			return FALSE;
		}

		if(empty($input['confirm'])){
			echoFail('Confirm field is empty');
			return FALSE;
		}

		if($input['password'] != $input['confirm']){
			echoFail('Password and confirm does not match');
			return FALSE;
		}
		$this->load->model('user_model');
		if($this->user_model->isCellphoneDuplicated($input['cellphone']) == TRUE ){
			echoFail('Cellphone has been registered');
			return FALSE;
		}

		list($result , $msg) = $this->user_model->create($input);

		if($result == FALSE){
			echoFail($msg);
			return FALSE;
		}

		$this->user_model->processLogin($input['cellphone']);

		echoSucc('register succ');
		return TRUE;

	}

	public function userLogin()
	{
		$input = array(
			'cellphone' => $this->input->get_post('cellphone') ,
			'password' => $this->input->get_post('password') ,
			);

		if(empty($input['cellphone'])){
			echoFail('Cellphone field is empty');
			return FALSE;
		}

		if(empty($input['password'])){
			echoFail('Password field is empty');
			return FALSE;
		}

		$this->load->model('user_model');
		if($this->user_model->isCellphonePasswordMatched($input) == FALSE){
			echoFail('Cellphone or password is wrong');
			return FALSE;
		}

		$this->user_model->processLogin($input['cellphone']);

		echoSucc('login succ');
		return TRUE;
	}

	public function userLogout(){
		$this->session->sess_destroy();
		echoSucc();
	}

	public function spaceShare(){
		$info = $this->input->get_post('info');
		$userid = $this->input->get_post('userId');
		$username = $this->input->get_post('userName');
        $info = json_decode($info,true);
		$isbn = $info['text'];
		
		$book_id = 0;
		if(!empty($isbn)){
            $isbn = trim($isbn);
			$this->load->model('books_model');
            $data = $this->books_model->getBookInfoByISBN($isbn);
            if($data == FALSE)
                $data['msg'] = 2;
            else
                $data['msg'] = 1;

            $data['isbn'] = $isbn;
			$book_id = isset($data['id']) ? $data['id'] : 0;
        }
		
		if(isLogin() == FALSE){
            echoFail('Have not logined yet ');
            return FALSE;
        }
        $user_id = $this->session->userdata('user_id');

        $this->load->model('share_model');
		$description = isset($data['title']) ? $data['title'] : "";
        if($item_id = $this->share_model->isDuplicateItem($book_id , $user_id)){
        }else{
        	$item_id = $this->share_model->createItem( $book_id , $user_id , $description );
		}

        $output = array(
            'errcode' => 0,
			'result' => 1,
            'book_id' => $book_id,
			'item_id' => $item_id,
			'description' => $description
            );
        echo json_encode(json_encode($output));
        return TRUE;
	}

	public function shareBook()
	{
		$input = array(
			'book_id' => $this->input->get_post('book_id') ,
			'description' => $this->input->get_post('description') ,
			);

		if(empty($input['book_id'])){
			echoFail('Book information is lost');
			return FALSE;
		}

		if(empty($input['description'])){
			echoFail('Description field is empty');
			return FALSE;
		}

		if(isLogin() == FALSE){
			echoFail('Have not logined yet ');
			return FALSE;
		}
		$user_id = $this->session->userdata('user_id');

		$this->load->model('share_model');
		if($this->share_model->isDuplicateItem($input['book_id'] , $user_id) == TRUE){
			echoFail('You can only upload one copy of the same book');
			return FALSE;
		}
		
		$item_id = $this->share_model->createItem( $input['book_id'] , $user_id , $input['description'] );

		$output = array(
			'result' => 1 ,
			'item_id' => $item_id
			);
		echo json_encode($output);
		return TRUE;
	}

	public function updateProfile()
	{
		$input = array(
			'username' => $this->input->get_post('username') ,
			'cellphone' => $this->input->get_post('cellphone') ,
			'email' => $this->input->get_post('email') ,
			);

		if(empty($input['username'])){
			echoFail('username is empty');
			return FALSE;
		}

		if(empty($input['cellphone'])){
			echoFail('cellphone is empty');
			return FALSE;
		}

		if(empty($input['email'])){
			echoFail('email is empty');
			return FALSE;
		}

		$username = $input['username'];
		$cellphone = $input['cellphone'];
		$email = $input['email'];

		$this->load->model('user_model');
		$user_id = $this->session->userdata('user_id');

		$query = $this->db->query("SELECT * FROM user WHERE cellphone = '$cellphone' AND id != $user_id ");
		if($query->num_rows() != 0){
			echoFail('cellphone is duplicated');
			return FALSE;
		}

		$query = $this->db->query("SELECT * FROM user WHERE username = '$username' AND id != $user_id ");
		if($query->num_rows() != 0){
			echoFail('username is duplicated');
			return FALSE;
		}

		$query = $this->db->query("SELECT * FROM user WHERE email = '$email' AND id != $user_id ");
		if($query->num_rows() != 0){
			echoFail('email is duplicated');
			return FALSE;
		}
		
		if($this->user_model->updateProfile($input , $user_id) == FALSE){
			echoFail('Fail to change profile');
			return FALSE;
		}

		echoSucc('login succ');
		return TRUE;
	}

	public function updateItem(){
		$input = array(
			'item_id' => $this->input->get_post('item_id') ,
			'description' => $this->input->get_post('description') ,
			'status' => $this->input->get_post('status') 
		);

		if(empty($input['item_id'])){
			echoFail('item_id is empty');
			return FALSE;
		}

		$this->load->model('user_model');
		$user_id = $this->session->userdata('user_id');
		$item_id = $input['item_id'];
		$query = $this->db->query("SELECT * FROM item WHERE id = $item_id AND status != 3 ");
		if($query->num_rows() == 0){
			echoFail('item_id does not exist');
			return FALSE;
		}

		$this->load->model('share_model');
		$update_data = array();
		if(!empty($input['description']) ){
			$update_data['description'] = $input['description'];
		}
		if(!empty($input['status']) ){
			$update_data['status'] = $input['status'];
		}

		if($this->share_model->updateItem($input['item_id'] , $update_data) == FALSE){
			echoFail('Fail to update item description');
			return FALSE;
		}

		echoSucc('update succ');
		return TRUE;
	}

	protected function sendOAMessage($userid,$oa=array()){
		if(empty($this->corpId)){return false;}
		$corpInfo = ISVClass::getCorpInfo($this->corpId);
		$suiteTicket = Cache::getSuiteTicket();
		$suiteAccessToken = Service::getSuiteAccessToken($suiteTicket);
		$authInfo = Service::getAuthInfo($suiteAccessToken, $corpInfo['corp_id'], $corpInfo['permanent_code']);
		$agentid = isset($authInfo->agent[0]->agentid) && !empty($authInfo->agent[0]->agentid) ? $authInfo->agent[0]->agentid : 0;
		$accessToken = $corpInfo['corpAccessToken'];

		$opt = array(
						"touser" => $userid,
						"agentid" => $agentid,
						"msgtype" => "oa",
						"oa" => $oa
					);

		$flag = Message::send($accessToken,$opt);
		return $flag;
	}

	public function requestBorrow(){
		$input = array(
			'item_id' => $this->input->get_post('item_id') ,
		);


		if(empty($input['item_id'])){
			echoFail('item_id is empty');
			return FALSE;
		}


		$this->load->model('user_model');
		$user_id = $this->session->userdata('user_id');
		$user_name = $this->session->userdata('name');

		//only sharing item can be borrowed
		$item_id = $input['item_id'];
		$query = $this->db->query("SELECT * FROM item WHERE id = $item_id AND status = 1 ");
		if($query->num_rows() != 1){
			echoFail('Item is not in sharing status');
			return FALSE;
		}

		$row = $query->first_row();
		if($row->user_id == $user_id){
			echoFail('Can not borrow your own book');
			return FALSE;
		}

		//create a new trade entry in trade table
		$this->load->model("share_model");
		$insert_id = $this->share_model->createTrade($user_id , $item_id);

		echoSucc('request succ');
		if(!empty($insert_id)){
			$user_query = $this->db->query("SELECT * FROM user WHERE id = '$row->user_id' ");
			if($user_query->num_rows() != 1){
				return FALSE;
			}
			$user_row = $user_query->first_row();

			$book_query = $this->db->query("SELECT * FROM book WHERE id = $row->book_id ");
			if($book_query->num_rows() != 1){
				return FALSE;
			}
			$book_row = $book_query->first_row();

			$oa = array(
				'message_url' => "http://120.26.118.14/index.php/space/shared?corpId=".$this->corpId,
				'head' => array(
					"bgcolor" => "38adff",
					"text" => "图书漂流"
				),
				'body' => array(
					"title" => "《".$book_row->title."》",
					"content" => "",
					"image" => $book_row->image_url,
					"author" => $user_name."向你求漂 "
				)
			);


			$this->sendOAMessage($user_row->userid,$oa);
		}
		return TRUE;

	}


	public function updateTrade(){
		$input = array(
			'trade_id' => $this->input->get_post('trade_id') ,
			'trade_op' => $this->input->get_post('trade_op') ,
		);

		if(empty($input['trade_id'])){
			echoFail('trade id is empty');
			return FALSE;
		}

		if(empty($input['trade_op'])){
			echoFail('trade operation is empty');
			return FALSE;
		}

		$this->load->model('user_model');
		$user_id = $this->session->userdata('user_id');
		$this->load->model("share_model");
		$current_status = $this->share_model->getTradeStatus($input['trade_id']);
		if($current_status == 0){
			echoFail('Trade is does not correct');
			return FALSE;
		}
		switch ($input['trade_op']) {
			case 'accept':
				if($current_status != 1){
					echoFail('Only inital status can be accepted');
					return FALSE;
				}
				break;
			case 'deny':
				if($current_status != 1){
					echoFail('Only inital status can be denied');
					return FALSE;
				}
				break;
			case 'cancel':
				if($current_status != 1){
					echoFail('Only inital status can be canceled');
					return FALSE;
				}
				break;
			case 'return':
				if($current_status != 2){
					echoFail('Only accept status can be canceled');
					return FALSE;
				}
				break;
			default:
				echoFail('Trade operation is unknown');
				return FALSE;
				break;
		}

		//create a new trade entry in trade table
		$insert_id = $this->share_model->updateTrade($input['trade_id'] , $input['trade_op']);

		if($insert_id == FALSE){
			echoFail('Unknown error');
			return FALSE;
		}else{
			echoSucc('request succ');
			return TRUE;
		}

	}

}
