<?php

class Users_model extends CI_Model {

    /**
    * Validate the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */
	function validate($user_name, $password)
	{
		$this->db->where('user_name', $user_name);
		$this->db->where('pass_word', $password);
		$query = $this->db->get('users');
		
		if($query->num_rows == 1)
		{
			$row = $query->row(); 
			if ($row->is_admin)
				return true;
		}		
	}

    /**
    * Serialize the session data stored in the database, 
    * store it in a new array and return it to the controller 
    * @return array
    */
	function get_db_session_data()
	{
		$query = $this->db->select('user_data')->get('ci_sessions');
		$user = array(); /* array to store the user data we fetch */
		foreach ($query->result() as $row)
		{
		    $udata = unserialize($row->user_data);
		    /* put data in array using username as key */
		    $user['user_name'] = $udata['user_name']; 
		    $user['is_logged_in'] = $udata['is_logged_in']; 
		}
		return $user;
	}
	
    /**
    * get all the user's data from the database
    * @return array
    */		
    public function get_members($number, $offset)
    {
		$query = $this->db->get('users',$number,$offset);
		return $query->result_array(); 	
    }// get_members
	

	 public  function count_all(){
            return $this->db->count_all('users');
        }
	
	
	public function get_member_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }// get_member_by_id
		
	
    /**
    * Store the new user's data into the database
    * @return boolean - check the insert
    */	
	function create_member()
	{

		$this->db->where('user_name', $this->input->post('username'));
		$query = $this->db->get('users');

        if($query->num_rows > 0){
        	echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>';
			  echo "Username already taken";	
			echo '</strong></div>';
		}else{

			$new_member_insert_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email_address' => $this->input->post('email_address'),			
				'user_name' => $this->input->post('username'),
				'pass_word' => md5($this->input->post('password'))						
			);
			$insert = $this->db->insert('users', $new_member_insert_data);
		    return $insert;
		}
	      
	}//create_member
	
	
    /**
    * Update user
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_member($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}	
	
    /**
    * delete the user's data from the database
    * @return 
    */		
    public function delete_member($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('users'); 
    }// delete_member	


	public function change_member_password($id, $newpassword)
    {
		$data = array(
					'pass_word' => md5($newpassword)
                );
		$this->db->where('id', $id);
		$this->db->update('users', $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
    }// change_member_password
	 function search($number, $offset,$keyword,$user_type,$user_status)
    {
        $this->db->like('user_name',$keyword);
        if($user_type!=3){
		$this->db->where('is_admin', $user_type);
        }
        
        $this->db->where('is_actived', $user_status);       
        $query  =   $this->db->get('users',$number,$offset);
       return $query->result_array(); 	
    }
    public  function count_all_search($keyword,$user_type,$user_status){
			 $this->db->like('user_name',$keyword);
       		  $this->db->where('is_admin', $user_type);
        	$this->db->where('is_actived', $user_status);     
			return $this->db->count_all_results('users');
		}
  

}

