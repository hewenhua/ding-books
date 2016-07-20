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
        $department = $this->input->get_post('department');
        $latitude = $this->input->get_post('latitude');
        $longitude = $this->input->get_post('longitude');
        $current_date = date('Y-m-d H:i:s');

		$input = array(
						'cellphone' => $userid,
						'username' => $user_name,
						'password' => $userid,
						'userid' => $userid,
						'corpid' => $corp_id,
                        'department' => json_encode($department),
                        'login_time' => $current_date,
                        'score' => 1,
					  );

        if(!empty($latitude)){
            $input['latitude'] = $latitude;
        }
        if(!empty($longitude)){
            $input['longitude'] = $longitude;
        }

		$query = $this->db->query("SELECT * FROM user WHERE userid = '$userid' AND corpid = '$corp_id' ");

		$this->load->model('user_model');
        $first_login = 0;
        if($query->num_rows() == 0){
			list($result , $msg) = $this->user_model->create($input);
	        if($result == FALSE){
	            echoFail($msg);
	            return FALSE;
	        }
            $first_login = 1;
	
		}else{
            unset($input['score']);
			$row = $query->first_row();
			$id = $row->id;	
            unset($input['password']);
            if(date("Y-m-d",strtotime($row->login_time)) != date("Y-m-d",strtotime($current_date))){
                $input['score'] = 1+intval($row->score);
                $first_login = 1;
            }
            $flag = $this->user_model->updateProfile($input,$id);
		}

        $login_user_id = $this->session->userdata('user_id');
        $process_login= false;
        if(empty($login_user_id)||$login_user_id!=$row->id){
		    $process_login = $this->user_model->processLogin($input['cellphone'],$input['corpid']);
        }
        echo json_encode(json_encode(array('process_login'=>$process_login,'first_login'=>$first_login)));
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

    public function spaceShake(){
		$userid = $this->input->get_post('userId');
        $corpId = $this->input->get_post('corpId');

        $data['search_data'] = array(
            'keyword' => $this->input->get_post('keyword'),
            'item_status' => array(4) ,
            'order_time' => 1,
            'order_name' => $this->input->get_post('order_name'),
        );
        $limit = 40;
        $offset = 0;
        if($this->session->userdata('user_id')){
            $user_id = $this->session->userdata('user_id');
            $data['search_data']['no_user_id'] = $user_id;
        }

        list( $data['total'] , $data['items']) = $this->query_model->queryItem( $data['search_data'] , $limit , $offset );
        $book_id = 0;
        $item_id = 0;
        $title = "";
        $i = rand(0,($data['total']-1));
        
        foreach ($data["items"] as $key => $item) {
            if($key == $i){
                $book_id = $item['book_id'];
                $item_id = $item['item_id'];
                $title = $item['title'];
            }
        }
        $msg_arr = array(
            "漂流币可以当钱花啦~1漂流币等于1毛钱",
            "每日登陆可奖励1漂流币~连续登陆有惊喜哦",
            "放漂(扫描书背后的条形码)即可获得漂流币哦~",
            "接受对方的求漂~可获得与书价相应的漂流币哦",
        );
        $k = rand(0,(count($msg_arr)-1));
        
        $msg = $msg_arr[$k];

        $output = array(
            'errcode' => 0,
            'result' => 1,
            'book_id' => $book_id,
            'item_id' => $item_id,
            'msg' => $msg,
            );
        echo json_encode(json_encode($output));
        return TRUE;
    }

	public function spaceShare(){
		$info = $this->input->get_post('info');
		$userid = $this->input->get_post('userId');
		$username = $this->input->get_post('userName');
        $address = $this->input->get_post('address');
        $latitude = $this->input->get_post('latitude');
        $longitude = $this->input->get_post('longitude');

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
        //$score = intval(intval($data['price'])/2);
        $score = 0;
        if($item_id = $this->share_model->isDuplicateItem($book_id , $user_id)){
            $data = array('latitude'=>$latitude,'longitude'=>$longitude,'location'=>$address,'status'=>1);
            $this->share_model->updateItem($item_id,$data);
        }else{
            $score = 2;
            $this->load->model('user_model');
        	$item_id = $this->share_model->createItem( $book_id , $user_id , $description , $latitude , $longitude , $address);
            if(!empty($item_id)){
                $this->user_model->addScore($user_id,$score);
            }
		}

        $output = array(
            'errcode' => 0,
			'result' => 1,
            'book_id' => $book_id,
			'item_id' => $item_id,
            'score' => $score,
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
        $row = $query->first_row();

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
        if(in_array($update_data['status'],array(2,3))&&$row->status == 1){
            $this->user_model->reduceScore($user_id,1);
        }elseif($update_data['status']==1){
            //$this->user_model->addScore($user_id,10);
        }
    

		echoSucc('update succ');
		return TRUE;
	}

	protected function sendOAMessage($userid,$oa=array()){
		if(empty($this->corpId)||empty($oa)){return false;}
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
			echoFail('书已被收漂!');
			return FALSE;
		}

		$row = $query->first_row();
		if($row->user_id == $user_id){
			echoFail('这本书是你的了!');
			return FALSE;
		}

        $user_score = $this->user_model->getScore($user_id);
        $this->load->model('books_model');
        $book_row = $this->books_model->getBookInfo($row->book_id);
        $price = 0;
        if(isset($book_row->price) && !empty($book_row->price)){
            $price = intval($book_row->price/2);
        }
        if(!empty($price) && $user_score<$price){
            echoFail('需要'.$price.'漂流币，你目前有'.$user_score.'漂流币，先放漂一本书再来求漂吧！');
            return FALSE;
            $item_query = $this->db->query("SELECT * FROM item WHERE user_id = $user_id and status in (1,4)");
            $item_num = $item_query->num_rows();
            $trade_query = $this->db->query("SELECT * FROM trade WHERE user_id = $user_id and status in (1,2)");
            $trade_num = $trade_query->num_rows();
            if($item_num < ($trade_num+1) && $user_id!=3){
                echoFail('你放漂了'.$item_num.'本，求漂了'.$trade_num.'本，先放漂一本书再来求漂吧！');
                return FALSE;
            }
        }
        
		//create a new trade entry in trade table
		$this->load->model("share_model");
		$insert_id = $this->share_model->createTrade($user_id , $item_id);

		if(!empty($insert_id)){
			$user_query = $this->db->query("SELECT * FROM user WHERE id = '$row->user_id' ");
			if($user_query->num_rows() != 1){
				return FALSE;
			}
            $this->user_model->reduceScore($user_id,intval($price));

            $oa = $this->getOA($row->book_id,"ask");
            $user_row = $user_query->first_row();
			$flag = $this->sendOAMessage($user_row->userid,$oa);
		}
        echoSucc('求漂成功~');
		return TRUE;

	}

    protected function getOA($book_id,$op='ask'){
        $op_text = array(
            'ask' => '向你发起了求漂',
            'accept' => '接受了你的求漂',
            'deny' => '拒绝了你的求漂',
        );
        if(!in_array($op,array_keys($op_text))){
            return false;
        }
        $user_name = $this->session->userdata('name');
        $book_query = $this->db->query("SELECT * FROM book WHERE id = $book_id ");
        if($book_query->num_rows() != 1){
            return FALSE;
        }
        $book_row = $book_query->first_row();
        $book_title = $book_row->title;

        if($op == "ask"){
            $oa['message_url'] = "http://120.26.118.14/space/shared?corpId=".$this->corpId;
        }else{
            $oa['message_url']  = "http://120.26.118.14/space/borrow?corpId=".$this->corpId;
        }
        $oa['head'] = array(
                        "bgcolor" => "38adff",
                        "text" => "闲书漂流"
                        );
        $oa['body'] = array(
                        "title" => $user_name.$op_text[$op],
                        "content" => "《".$book_row->title."》",
                        "image" => $book_row->image_url,
                        );
        $oa['body']['author'] = "闲书漂流";//$user_name.$op_text[$op];

        return $oa;
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
        $trade_row = $this->share_model->getTrade($input['trade_id']);
		$current_status = $trade_row->status;
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
			case 'lost':
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
		}
        
        $item_id = $trade_row->item_id;
        $trade_user_id = $trade_row->user_id;
        $trade_user_info = $this->user_model->getUserInfo($trade_user_id);
        $trade_userid = $trade_user_info['userid'];
        $query = $this->db->query("SELECT * FROM item WHERE id = $item_id ");
		if($query->num_rows() != 1){
			echoFail('Unknown error');
			return FALSE;
		}
		$row = $query->first_row();
        $oa = $this->getOA($row->book_id,$input['trade_op']);
        $this->sendOAMessage($trade_userid,$oa);

        $book_query = $this->db->query("SELECT * FROM book WHERE id = $row->book_id ");
        if($book_query->num_rows() != 1){
			echoFail('Unknown error');
            return FALSE;
        }
        $book_row = $book_query->first_row();

        $score = intval(intval($book_row->price)/2);
        if(in_array($input['trade_op'],array('return'))){
            $this->user_model->addScore($user_id,5);
            echoSucc('确认书已归还！系统奖励5漂流币~');
            return TRUE;
        }
        if(in_array($input['trade_op'],array('accept'))){
            $this->user_model->addScore($user_id,$score);
            echoSucc('你同意了求漂！系统奖励'.$score.'漂流币~');
            return TRUE;
        }
        if(in_array($input['trade_op'],array('cancel'))){
            $this->user_model->addScore($user_id,$score);
            echoSucc('你取消了放漂！系统退还'.$score.'漂流币~');
            return TRUE;
        }
        
        echoSucc('request succ');
        return TRUE;

	}

}
