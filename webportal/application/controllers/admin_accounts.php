<?php
class Admin_accounts extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
		$this->load->model('users_model');
        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }
 
    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function index()
    {
		//$data['accounts'] = $this->users_model->get_members();
        //load the view
        //$data['main_content'] = 'admin/accounts/list';
        //$this->load->view('includes/template', $data);  

        $this->load->library('pagination');
         $this->load->helper('url');
        $data['main_content'] = 'admin/accounts/list';
        $config['base_url']=base_url('index.php/admin/accounts/list');
        $config['total_rows'] = $this->users_model->count_all();
        $config['next_link']        =   'Next >>';
        $config['prev_link']        =   '<< Prev';
        $config['num_tag_open']     =   '<span style="padding:0 2px 0 2px">';
        $config['num_tag_close']    =   '</span>';
        $config['num_links']        =   5;
        $config['cur_tag_open']     =   '<a  class="currentpage">';
        $config['cur_tag_close']    =   '</a>';
        $config['per_page'] = 15; 
        $config['uri_segment'] = 4;      
        $this->pagination->initialize($config);
        $data['list_pagination'] = $this->pagination->create_links();
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['accounts'] = $this->users_model->get_members($config['per_page'], $page );   
        //load the view
        //$data['data'] = $this->leads_model->get_members($config['per_page'], $page );   
        $this->load->view('includes/template', $data);  




    }//index

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
			$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|xss_clean|is_unique[users.user_name]');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]|min_length[4]|max_length[32]');
			$this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim');
			$this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email|is_unique[users.email_address]');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {	
				/*
                $data_to_store = array(
					'first_name' => $this->input->post('first_name'), 
					'last_name' => $this->input->post('last_name'), 
                    'user_name' => $this->input->post('username'), 
					'email_address' => $this->input->post('email_address'), 
					'pass_word' => md5($this->input->post('password')), 
                    'is_admin' => $this->input->post('user_type')
                );
				*/
                $this->users_model->create_member();
                redirect('admin/accounts');
            }//validation run

        }		
	
        //load the view
        $data['main_content'] = 'admin/accounts/add';
        $this->load->view('includes/template', $data);  





    }  //add

    public function update($id = Null)
    {

		if ($id == Null)
			redirect('admin/accounts');
			
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
			//$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|matches[passconf]|min_length[4]|max_length[32]');
			$this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
			
				$newpass = $this->input->post('password');
				if (strlen ($newpass) > 3)
					$this->users_model->change_member_password($id, $newpass);
				
                $data_to_store = array(
                    'email_address' => $this->input->post('email'), 
                    'is_admin' => $this->input->post('user_type')
                );
                //if the insert has returned true then we show the flash message
                if($this->users_model->update_member($id, $data_to_store) == TRUE){
                    $this->session->set_flashdata('flash_message', 'updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/accounts/update/'.$id.'');

            }//validation run

        }			
			
        $data['user'] = $this->users_model->get_member_by_id($id);
        //load the view
        $data['main_content'] = 'admin/accounts/edit';
        $this->load->view('includes/template', $data);  
    }  	
	
	
    /**
    * Delete product by his id
    * @return void
    */
    public function delete()
    {
        //product id 
        $id = $this->uri->segment(3);       
        $this->users_model->delete_member($id);      
        redirect('admin/accounts');
    }//edit	

     public function search(){

        $this->load->library('pagination');
        $this->load->helper('url');

        $keyword    =   $this->input->post('search_string');
         $user_type = $this->input->post('user_type');
        $user_status = $this->input->post('user_status');
        
     
            if($user_type==0)
            {
             $user_type=3;
            }elseif ($user_type==1) 
            {
                $user_type=0;
            }else{

                $user_type=1;
            }

        $data['main_content'] = 'admin/accounts/list';
        $config['base_url']=base_url('index.php/admin/leads');
        $config['total_rows'] = $this->users_model->count_all_search($keyword,$user_type, $user_status);
        $config['next_link']        =   'Next >>';
        $config['prev_link']        =   '<< Prev';
        $config['num_tag_open']     =   '<span style="padding:0 2px 0 2px">';
        $config['num_tag_close']    =   '</span>';
        $config['num_links']        =   5;
        $config['cur_tag_open']     =   '<a  class="currentpage">';
        $config['cur_tag_close']    =   '</a>';
        $config['per_page'] = 20; 
        $config['uri_segment'] = 4;      
        $this->pagination->initialize($config);
        $data['list_pagination'] = $this->pagination->create_links();
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['accounts'] = $this->users_model->search($config['per_page'], $page,$keyword,$user_type,$user_status);   
        $this->load->view('includes/template', $data); 
    }
}