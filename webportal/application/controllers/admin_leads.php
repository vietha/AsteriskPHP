<?php
class Admin_leads extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
		$this->load->model('leads_model');
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

        $this->load->library('pagination');
         $this->load->helper('url');
        $data['main_content'] = 'admin/leads/list';
        $config['base_url']=base_url('index.php/admin/leads/index');
        $config['total_rows'] = $this->leads_model->count_all();
        $config['next_link']        =   'Next >>';
        $config['prev_link']        =   '<< Prev';
        $config['num_tag_open']     =   '<span style="padding:0 2px 0 2px">';
        $config['num_tag_close']    =   '</span>';
        $config['num_links']        =   5;
        $config['cur_tag_open']     =   '<a  class="currentpage">';
        $config['cur_tag_close']    =   '</a>';
        $config['per_page'] = 10; 
        $config['uri_segment'] = 4;      
        $this->pagination->initialize($config);
        $data['list_pagination'] = $this->pagination->create_links();
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['data'] = $this->leads_model->get_members($config['per_page'], $page );   
        $this->load->view('includes/template', $data);  


    }//index

    public function search(){

        $this->load->library('pagination');
        $this->load->helper('url');

        $keyword    =   $this->input->post('keyword');
        $start_date = $this->input->post('start_date');

        $data['main_content'] = 'admin/leads/list';
        $config['base_url']=base_url('index.php/admin/leads');
        $config['total_rows'] = $this->leads_model->count_all_search($keyword,$start_date);
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
        $data['data'] = $this->leads_model->search($config['per_page'], $page,$keyword,$start_date);   
        $this->load->view('includes/template', $data); 
    }

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
			$this->form_validation->set_rules('leads_email', 'Email', 'trim|required|valid_email|is_unique[users.email_address]');
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
                $this->leads_model->create_member();
                redirect('admin/leads');
            }//validation run

        }		
	
        //load the view
        $data['main_content'] = 'admin/leads/add';
        $this->load->view('includes/template', $data);  
    }  //add

    public function update($id = Null)
    {   

       
		if ($id == Null)
			redirect('admin/leads');
			
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {  


            //form validation
			//$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[5]|max_length[12]|xss_clean');
			//$this->form_validation->set_rules('password', 'Password', 'trim|matches[passconf]|min_length[4]|max_length[32]');
			//$this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim');
			$this->form_validation->set_rules('leads_email', 'Email', 'trim|required|valid_email');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
		         
                $data_to_store = array(
                    'name' => $this->input->post('leads_name'),
                     'email' => $this->input->post('leads_email'),
                    'phonenumber' => $this->input->post('leads_phone'),                 
                    'datecreate' => $this->input->post('leads_datecreate'),
                    'lastupdate' => $this->input->post('leads_lastupdate'),
                    'is_active' => $this->input->post('leads_status')   
                
                );
             
                if($this->leads_model->update_member($id, $data_to_store) == TRUE){
                    $this->session->set_flashdata('flash_message', 'updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }

                //redirect('admin_leads/update/'.$id.'');

            }//validation run

        }	

      $data['name'] = $this->leads_model->get_member_by_id($id);

        //load the view
        $data['main_content'] = 'admin/leads/edit';
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
        $this->leads_model->delete_member($id);
        redirect('admin/leads');
    }//edit	

    function importcsv() {
        $data['addressbook'] = $this->csv_model->get_addressbook();
        $data['error'] = '';    //initialize image upload error array to empty
 
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '1000';
 
        $this->load->library('upload', $config);
 
 
        // If upload failed, display error
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
 
            $this->load->view('admin/leads/csvindex', $data);
        } else {
            $file_data = $this->upload->data();
            $file_path =  './uploads/'.$file_data['file_name'];
 
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                foreach ($csv_array as $row) {
                    $insert_data = array(
                        'name'=>$row['name'],
                        'email'=>$row['email'],
                        'phone'=>$row['phone'],
                        'email'=>$row['email'],
                    );
                    $this->csv_model->insert_csv($insert_data);
                }
                $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect(base_url().'csv');
                //echo "<pre>"; print_r($insert_data);
            } else 
                $data['error'] = "Error occured";
                $this->load->view('admin/leads/csvindex', $data);
            }
 
        } //importcsv
}