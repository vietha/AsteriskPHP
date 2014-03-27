<?php

class Leads_model extends CI_Model {

    /**
    * Validate the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */
	function validate($user_name, $password)
	{
		$this->db->where('leads_email', $user_name);
		$this->db->where('leads_datecreate', $password);
		$query = $this->db->get('leads');
		
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
		$query = $this->db->get('leads',$number,$offset);
		return $query->result_array(); 	
    }// get_members

    
    public  function count_all(){
            return $this->db->count_all('leads');
        }
	
	
	public function get_member_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('leads');
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

		$this->db->where('id', $this->input->post('id'));
		$query = $this->db->get('leads');

        if($query->num_rows > 0){
        	echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>';
			  echo "Username already taken";	
			echo '</strong></div>';
		}else{

			$new_member_insert_data = array(
				'name' => $this->input->post('leads_name'),
				'email' => $this->input->post('leads_email'),
				'phonenumber' => $this->input->post('leads_phone'),					
				'datecreate' => $this->input->post('leads_datecreate'),
				'lastupdate' => $this->input->post('leads_lastupdate'),
				'is_active' => $this->input->post('leads_status')					
			);
			$insert = $this->db->insert('leads', $new_member_insert_data);
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

		$this->db->update('leads', $data);
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
		$this->db->delete('leads'); 
    }// delete_member	

    	//,$end_date

		public  function count_all_search($keyword,$start_date){
			 $this->db->like('name',$keyword);
       		 $this->db->where('datecreate >=', $start_date);
			return $this->db->count_all_results('leads');
		}
  
	

    function search($number, $offset,$keyword,$start_date)
    {
        $this->db->like('name',$keyword);
        $this->db->where('datecreate >=', $start_date);
        //$this->db->where('lastupdate <=', $end_date);       
        $query  =   $this->db->get('leads',$number,$offset);
       return $query->result_array(); 	
    }

	
}

