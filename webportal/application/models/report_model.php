<?php

class Report_model extends CI_Model {

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
		$query = $this->db->get('cdr',$number,$offset);
		return $query->result_array(); 	
    }// get_members

    
    public  function count_all(){
            return $this->db->count_all('cdr');
        }

	public function get_member_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('cdr');		
		$query = $this->db->get();
		return $query->result_array(); 
    }// get_member_by_id
		
	 function printtablecdr($start_date,$end_date)
    {	
		$this->db->select('*');			
        $this->db->where('calldate >=', $start_date);
        $this->db->where('calldate <=', $end_date );       
        $query  =   $this->db->get('cdr');
        return $query->result_array();
    }

   
}

