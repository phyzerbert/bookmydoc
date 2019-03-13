<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home_Translation extends CI_Controller {
	public function __construct() {
	parent::__construct();
		date_default_timezone_set("Asia/Kolkata");
		$this->load->model('HomeTranslation_model');
		$this->load->helper('directory');
		if(!$this->session->userdata('logged_in')) { 
			redirect(base_url());
		}
    }
	public function add_home()
	{
		$template['page'] = "Translation/Home/add_home";
		$template['page_title'] = "Add Language Translation";
		$this->load->view('template',$template);
	
		
	}
	public function insert_home()
	{
		$data= json_decode(file_get_contents("php://input"));
		$data =(array) $data;
        $role=$this->HomeTranslation_model->inserthome($data);
        echo $role;
	}
	public function view_home()
	{		
		$textFile ='home_lang.php';
		$textFile = str_replace(" ", "_", $textFile); 
        $textFile = strtolower($textFile);
		$trimmed_lang = array();
		$location = '../application/language/'.'';
		$lang_directory = directory_map($location, TRUE);		
		foreach($lang_directory as $key => $value):
			$trimmed_lang[] = trim($value , " \."); 				
		endforeach; 
		$avoid_final_lang=array(); $template['final_lang']=array();
		foreach($trimmed_lang as $single_lang):		
			$locationfiles = '../application/language/'.$single_lang.'/'.'';		
			if(!file_exists($locationfiles.$textFile) && !is_dir($locationfiles.$textFile)): 
				$avoid_final_lang[] = $single_lang;				
			else:
				$template['final_lang'][] = trim($single_lang , "\/"); 
			endif;
		endforeach;
		$template['final_lang'];
		$template['page'] = "Translation/Home/view_home";
		$template['page_title'] = "View Language Translation";		
		$this->load->view('template',$template);	
	}
	public function edit_home()
	{
		$template['language_name'] = $this->uri->segment(3);
		$template['page'] = "Translation/Home/edit_home";
		$template['page_title'] = "edit Language Translation";
		$this->load->view('template',$template);
			
	}
	public function home_delete()
	{
		$textFile ='home_lang.php';
		$language_name = $this->uri->segment(3);	
		$locationfiles = '../application/language/'.$language_name.'/'.$textFile;
		$language_location = '../application/language/'.$language_name.'';
		if(unlink($locationfiles)):
			$lang_directory = directory_map($language_location, TRUE);
			if(is_bool($lang_directory ) === false):
				rmdir($language_location);
				$result = $this->HomeTranslation_model->delete_language($language_name);				
				if($result):
					$this->session->set_flashdata('message', array('message' => 'Deleted Successfully','class' => 'success'));
					redirect(base_url().'Home_Translation/view_home');
				endif;		
			else:
				$this->session->set_flashdata('message', array('message' => 'Deleted Successfully','class' => 'success'));
				redirect(base_url().'Home_Translation/view_home');	
			endif;
		else:			
			$this->session->set_flashdata('message', array('message' => 'Request went wrong.Contact Administrator.','class' => 'danger'));
			redirect(base_url().'Home_Translation/view_home');
		endif;
	}	
} 
?>