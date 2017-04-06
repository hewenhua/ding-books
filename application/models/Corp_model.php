<?php 
class Corp_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	public function getCorpInfoById($corp_id){
		$corp_info = $this->db->query("SELECT * FROM corp_info WHERE corp_id = '$corp_id'");

		//if not exists , get data from douban
		if($corp_info->num_rows() == 0){
            return array();
		}

		$row = $corp_info->first_row("array");

		return $row;
	}

	public function saveCorpInfo($corp_info){
        $corp_id = isset($corp_info['corp_id']) && !empty($corp_info['corp_id']) ? $corp_info['corp_id'] : 0;
        if(empty($corp_id)){
            return false;
        }
        $corpinfo = $this->getCorpInfoById($corp_id);
        if(empty($corpinfo)){
		    $corp = array(
		    	'corp_id' => $corp_info['corp_id'] ,
		    	'permanent_code' => $corp_info['permanent_code'] ,
		    	'tmp_auth_code' => $corp_info['tmp_auth_code'] ,
                
		    	);
		    $insert_corp = $this->db->insert( "corp_info" , $corp);
		    $id = $this->db->insert_id();
        }else{
            $corp = array(
                'permanent_code' => $corp_info['permanent_code'] ,
                'tmp_auth_code' => $corp_info['tmp_auth_code'] ,

                );
            $this->db->where('corp_id',$corp_info['corp_id']);
            $this->db->update('corp_info', $corp);
        }
	}

}

