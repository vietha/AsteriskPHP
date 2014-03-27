<?php
class Admin_report extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
		$this->load->model('report_model');
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
		//$data['report'] = $this->report_model->get_members();
        //load the view
       // $data['main_content'] = 'admin/report/list';
        //$this->load->view('includes/template', $data);  


           $this->load->library('pagination');
         $this->load->helper('url');
        $data['main_content'] = 'admin/report/list';
        $config['base_url']=base_url('index.php/admin/report/index');
        $config['total_rows'] = $this->report_model->count_all();
        $config['next_link']        =   'Next »';
        $config['prev_link']        =   '« Prev';
        $config['num_tag_open']     =   '<span style="padding:0 2px 0 2px">';
        $config['num_tag_close']    =   '</span>';
        $config['num_links']        =   1;
        $config['cur_tag_open']     =   '<a  class="currentpage">';
        $config['cur_tag_close']    =   '</a>';
        $config['per_page'] = 10; 
        $config['uri_segment'] = 4;      
        $this->pagination->initialize($config);
        $data['list_pagination'] = $this->pagination->create_links();
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data['data'] = $this->report_model->get_members($config['per_page'], $page );   
        $this->load->view('includes/template', $data);

    }//index
     public function exportdata()
     {
      $this->load->library('Csv_convert');
      //$this->load->view('includes/template', $data);  
        $this->load->helper('download');


        $start_date = $this->input->post('datestart');
        $end_date = $this->input->post('datesend');
     
        $title = array();
           $report = $this->report_model->printtablecdr($start_date,$end_date);
          $file_name="TableCdr.csv";
           
        if (count($report) > 0) {
            foreach ($report[0] as $key => $val) {
                array_push($title, $key);
            }
            $report_csv = $this->csv_convert->array_to_csv($report, $title);
            force_download($file_name, $report_csv);
        } else {
            $report_csv = $this->csv_convert->array_to_csv(array(array("Your report" => "No data in your report")));
            force_download($file_name, $report_csv);
        }

    }
}