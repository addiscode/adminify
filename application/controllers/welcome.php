<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library(array("session"));
	}
	
	public function index()
	{
		$this->is_authenticated();
		$data['config'] = Spyc::YAMLLoad(BASEPATH . '../admin/application/config/config.yaml');
		$data['breadcrumbs'] = array();
		$data['BASEPATH'] = BASEPATH;
		$this->load->view('templates/__header', array('config'=>$data['config'],'breadcrumbs'=>$data['breadcrumbs']));
		$this->load->view('welcome_message', $data);
		$this->load->view('templates/__footer');
	}
	
	private function is_authenticated() {
		if(!$this->session->userdata("user"))
		    redirect("/welcome/login");
	}
    
	public function authenticate() {
		$params = $this->input->post(NULL, TRUE);
		$isValid = FALSE;
		if (!$params || $this->input->post('email', TRUE) == '' || $this->input->post("password", TRUE) == '') {
			echo "EMPTY";
			return;
		}
		else {
			$user = $this->db->get_where("users",array(
				'email'=>$params['email'],
				'password'=>  sha1($this->input->post("password"))
			    ),1)->row_array();
			    if(empty($user)){
				$isValid = FALSE;
			    } else {
				$isValid = TRUE;
				$this->session->set_userdata("user", $user);
			    }
		}
		echo $isValid? "true":"false";
		//if form is submited will echo if called from inside will redirect		
	}
    
	public function login() {
		$data['config'] = Spyc::YAMLLoad(BASEPATH . '../admin/application/config/config.yaml');
		$data['noBreadcrumb'] = TRUE;
		$this->load->view("templates/__header", $data);
		$this->load->view("login");
	}
	
	public function logout() {
		$this->session->unset_userdata("user");
		redirect("welcome/login");
	}
	
	public function setting() {
		$this->is_authenticated();
		$user = $this->session->userdata("user");
		if($this->input->post("submit")) {
			//check if old password is correct
			if($user['password'] === sha1($this->input->post("old_password"))) {
				//check if the two passwords match
				if($this->input->post("password") === $this->input->post("confirm_password")) {
					$this->db->update("users",array('email'=>$this->input->post("email"),
											  'password'=>sha1($this->input->post("password"))
											  ),
							  array("id"=>$user['id'])
							  );
					$log_file = BASEPATH. '../admin/application/logs/admin.log';
					$fh = fopen($log_file, 'a+');
					fwrite($fh, date("d-m-Y @ H:i:s") . " | Account settings updated!\n");
					unset($fh);
					redirect("welcome");
				}else {
					$data['error'] = "Password confirmation not correct";
				}
				
			}else {
				$data['error'] = "Old password not correct";
			}
			
		}
		$data['config'] = Spyc::YAMLLoad(BASEPATH . '../admin/application/config/config.yaml');
		$data['noBreadcrumb'] = TRUE;
		$this->load->view("templates/__header", $data);
		$this->load->view("setting", $data);
	}
	
	public function recovery() {
		if($this->input->post("submit")) {
			$user = $this->db->get("users")->result_array();
			$user = $user[0];
			//check if email is correct
			if($this->input->post("email") === $user['email']) {
				$this->load->library("email");
				$newPassword = $this->genRandomString(6);
				$this->email->clear(TRUE);
				$this->email->from("no-reply@moderneth.com", "Password recovery");
				$this->email->to($user['email']);
				$message = "";
				//$this->email->message();
				//$this->email->send();
				$this->db->update("users",array("password"=>sha1($newPassword)), array('id'=>$user['id']));
				$this->session->set_flashdata("message", "Your recovery password is sent to your email address");
				redirect("welcome/login");
				return;
			} else {
				$data['error'] = "Invalid email address";
			}
			
		}
		$data['config'] = Spyc::YAMLLoad(BASEPATH . '../admin/application/config/config.yaml');
		$data['noBreadcrumb'] = TRUE;
		$this->load->view("templates/__header", $data);
		$this->load->view("recovery", $data);
	}
	
	private function genRandomString($length=8) {
		$characters = "0123456789!@#$&abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$charLength = strlen($characters);
		$string = "";
		for ($p = 0; $p < $length; $p++) {
		    $string .= $characters[mt_rand(0, $charLength)];
		}
	    return $string;
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */