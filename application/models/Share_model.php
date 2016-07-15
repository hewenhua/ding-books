<?php 
class Share_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	// item status 
	// 1 - sharing
	// 2 - unshare
	// 3 - deleted
	// 4 - borrowed

	// trade status
	// 1 - initial 
	// 2 - accepted 
	// 3 - denied 
	// 4 - canceled
	// 5 - returned 
	// 6 - lost

	function createItem($book_id , $user_id , $description , $latitude = null , $longitude = null , $address = null){
		$insert_arr = array(
			'book_id' => $book_id ,
			'user_id' => $user_id ,
			'description' => $description ,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'location' => $address,
			'status' => 1
			);
		$this->db->insert('item' , $insert_arr);
		$item_id = $this->db->insert_id();
		$this->afterCreateItem($item_id,$address);
		return $item_id;
	}

	function afterCreateItem($item_id , $address = null){
		$item_view = array();
		$item_query = $this->db->query("SELECT * FROM item WHERE id = $item_id ");
                if($item_query->num_rows() != 1)
                        return 0;
                $item_row = $item_query->first_row();
		$item_view['item_id'] = $item_id;
		$item_view['book_id'] = $book_id = $item_row->book_id;
		$item_view['item_status'] = $item_row->status;
		$item_view['user_id'] = $user_id =$item_row->user_id;
		$book_query = $this->db->query("SELECT * FROM book WHERE id = $book_id ");
		if($book_query->num_rows() != 1)
                        return 0;
		$book_row = $book_query->first_row();
		$item_view['title'] = $book_row->title;
		$item_view['publisher_id'] = $publisher_id = $book_row->publisher_id;
		$item_view['pubdate'] = $book_row->pubdate;
		$item_view['image_url'] = $book_row->image_url;
		$item_view['douban_url'] = $book_row->douban_url;
		$item_view['description'] = $book_row->description;
		$user_query = $this->db->query("SELECT * FROM user WHERE id = $user_id");
		if($user_query->num_rows() != 1)
                        return 0;
		$user_row = $user_query->first_row();
		$item_view['username'] = $user_row->username;
		$publisher_query = $this->db->query("SELECT * FROM publisher WHERE id = $publisher_id");
                if($publisher_query->num_rows() != 1)
                        return 0;
		$publisher_row = $publisher_query->first_row();
		$item_view['publisher_name'] = $publisher_row->name;

		$item_view['create_time'] = date('Y-m-d H:i:s');
        $item_view['corpid'] = $this->session->userdata('corpid');
		$item_view['department'] = $this->session->userdata('department');
        $item_view['location'] = $address;

		return $this->db->insert('item_view',$item_view);
	}


	function updateItem($item_id , $data){
		$this->db->where('id', $item_id);
		$this->db->update('item', $data);
		$this->afterUpdateItem($item_id);
		return TRUE;
	}

	function afterUpdateItem($item_id){
		$item_view = array();
                $item_query = $this->db->query("SELECT * FROM item WHERE id = $item_id ");
                if($item_query->num_rows() != 1)
                        return 0;
                $item_row = $item_query->first_row();
		$this->db->where('item_id',$item_id);
        $item_view['create_time'] = date('Y-m-d H:i:s');
		$item_view['item_status'] = $item_row->status;
		return $this->db->update('item_view',$item_view);
	}

	function isDuplicateItem($book_id , $user_id){
		$query = $this->db->query("SELECT * FROM item WHERE book_id = $book_id AND user_id = $user_id ");
		if($query->num_rows() == 0){
			return FALSE;
		}else{
			$row = $query->first_row();
			return $row->id;
		}
	}

	function createTrade($user_id , $item_id){
		$data = array(
        	   'user_id' => $user_id ,
        	   'item_id' => $item_id ,
        	   'status' => 1
        	);
        	$this->db->insert('trade', $data); 
        	$trade_id = $this->db->insert_id();
        	$this->updateItem($item_id , array('status' => 4));
		$this->afterCreateTrade($trade_id);

		$record_insert_arr = array(
				'trade_id' => $trade_id ,
				'op' => 1,	
				'create_time' => date('Y-m-d H:i:s'),
				);
		$this->db->insert('trade_record' , $record_insert_arr );

        	return $trade_id;
	}

	function afterCreateTrade($trade_id){
		$trade_view = array();
		$trade_view['trade_id'] = $trade_id;
                $trade_query = $this->db->query("SELECT * FROM trade WHERE id = $trade_id ");
                if($trade_query->num_rows() != 1)
                        return 0;
                $trade_row = $trade_query->first_row();
		$trade_view['item_id'] = $item_id = $trade_row->item_id;
		$trade_view['borrower_id'] = $borrower_id = $trade_row->user_id;
		$trade_view['trade_status'] = $trade_row->status;
                $item_query = $this->db->query("SELECT * FROM item WHERE id = $item_id ");
                if($item_query->num_rows() != 1)
                        return 0;
                $item_row = $item_query->first_row();
                $trade_view['owner_id'] = $owner_id =$item_row->user_id;
		$trade_view['item_description'] = $item_row->description;
		$book_id = $item_row->book_id;
		$book_query = $this->db->query("SELECT * FROM book WHERE id = $book_id ");
                if($book_query->num_rows() != 1)
                        return 0;
                $book_row = $book_query->first_row();
                $trade_view['item_title'] = $book_row->title;
		$trade_view['image_url'] = $book_row->image_url;
		$user_query = $this->db->query("SELECT * FROM user WHERE id in ($borrower_id,$owner_id)");
		if($item_query->num_rows() != 1)
                        return 0;
		$user_row = $user_query->result('array');
		$user_array = array();
		foreach($user_row as $key=>$row){
			$user_array[$row['id']] = $row;
		}
		$trade_view['borrower_name'] = $user_array[$borrower_id]['username'];
		$trade_view['borrower_cellphone'] = $user_array[$borrower_id]['cellphone'];
		$trade_view['borrower_email'] = $user_array[$borrower_id]['email'];
		$trade_view['owner_name'] = $user_array[$owner_id]['username'];
		$trade_view['owner_cellphone'] = $user_array[$owner_id]['cellphone'];
		$trade_view['owner_email'] = $user_array[$owner_id]['email'];

		$trade_view['create_time'] = date('Y-m-d H:i:s');

                return $this->db->insert('trade_view',$trade_view);
	}

	function getTradeStatus($trade_id){
		$query = $this->db->query("SELECT * FROM trade WHERE id = $trade_id ");
		if($query->num_rows() != 1)
			return 0;
		$row = $query->first_row();
		return $row->status;
	}

 	function getTrade($trade_id){
		$query = $this->db->query("SELECT * FROM trade WHERE id = $trade_id ");
		if($query->num_rows() != 1)
			return 0;
		$row = $query->first_row();
		return $row;
	}   

	function updateTrade($trade_id , $trade_op){
		//the validity of operation is checked in api , error message is returned in json format
		$target_transfer = array(
			'accept' => 2 ,
			'deny' => 3 ,
			'cancel' => 4 ,
			'return' => 5 ,
			'lost' => 6
			);
		$data = array(
			'status' => $target_transfer[$trade_op]
			);
		$this->db->where('id', $trade_id);
		$this->db->update('trade', $data);
		//some operation will change other table status , too.
		if( in_array($trade_op, array('return','cancel','deny')) ){	
			$item_query = $this->db->query("SELECT item_id FROM trade WHERE id = $trade_id");
			$row = $item_query->first_row();
			$item_id = $row->item_id;
			$this->db->query("UPDATE item SET status = 1 WHERE id = $item_id");// 1 - sharing
		}

		if( in_array($trade_op, array('lost')) ){	
			$item_query = $this->db->query("SELECT item_id FROM trade WHERE id = $trade_id");
			$row = $item_query->first_row();
			$item_id = $row->item_id;
			$this->db->query("UPDATE item SET status = 3 WHERE id = $item_id");// 3 - delete
		}

		//DO THE RECORD 
		$record_insert_arr = array(
			'trade_id' => $trade_id ,
		 	'op' => $data['status'],
			'create_time' => date('Y-m-d H:i:s'),
		 	);
		$this->db->insert('trade_record' , $record_insert_arr );
		$this->afterUpdateTrade($trade_id);

		return TRUE;
	}

	function afterUpdateTrade($trade_id){
		$trade_view = array();
                $trade_view['trade_id'] = $trade_id;
		$trade_query = $this->db->query("SELECT * FROM trade WHERE id = $trade_id ");
                if($trade_query->num_rows() != 1)
                        return 0;
                $trade_row = $trade_query->first_row();
		$trade_view['trade_status'] = $trade_row->status;
		$this->db->where('trade_id',$trade_id);
		return $this->db->update('trade_view',$trade_view);
	}
}

