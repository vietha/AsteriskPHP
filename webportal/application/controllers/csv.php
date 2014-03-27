<?php
 
class Csv extends CI_Controller {
 
    function __construct() {
        parent::__construct();
        $this->load->model('csv_model');
        $this->load->library('csvimport');
    }
 
    function index() {
        $data['leads'] = $this->csv_model->get_addressbook();
        $this->load->view('admin/leads', $data);
         $this->load->view('includes/template', $data);
    }
 
    function importcsv() {
        $data['leads'] = $this->csv_model->get_addressbook();

        $data['error'] = '';    //initialize image upload error array to empty

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = '1000';
 
        $this->load->library('upload', $config);
 
 
        // If upload failed, display error
        if (!$this->upload->do_upload()) {
            $data['error'] = $this->upload->display_errors();
              redirect(base_url().'admin/leads');
           // $this->load->view('admin/leads/csvindex', $data);
           
        } else {
            $file_data = $this->upload->data();

            $file_path =  './uploads/'.$file_data['file_name'];
            
            if ($this->csvimport->get_array($file_path)) {
                $csv_array = $this->csvimport->get_array($file_path);
                
                foreach ($csv_array as $row) {

                   
                    $insert_data = array(
                        'name'=>$row['name'],
                        'email'=>$row['email'],
                        'phonenumber'=>$row['phonenumber'],
                        'datecreate'=>$row['datecreate'],
                        'lastupdate'=>$row['lastupdate'],
                        'is_active'=>$row['is_active']
                    );

                    $this->csv_model->insert_csv($insert_data);
                }
                $this->session->set_flashdata('success', 'Csv Data Imported Succesfully');
                redirect(base_url().'admin/leads');
                //echo "<pre>"; print_r($insert_data);
            } else 
                $data['error'] = "Error occured";
                $this->load->view('admin/leads', $data);
            }
 
        } 
 
}