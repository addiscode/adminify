<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Show extends CI_Controller {
	private $table_name;
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	private function is_authenticated() {
		if(!$this->session->userdata("user"))
		    redirect("/welcome/login");
	}
	
	public function f() {
		$this->is_authenticated();
		$data['config'] = Spyc::YAMLLoad(BASEPATH . '../admin/application/config/config.yaml');
		$this->table_name = $this->uri->segment(3);
		$page = $this->uri->segment(4);
		$data['data']['list'] = $this->getList();
		$data['data']['map'] = $this->getFieldMap($data['config']);
		$breadcrumbs = array(
				array('title'=>plural($this->table_name),
						'href'=> base_url() . "index.php/show/f/" . plural($this->table_name))
				);
		$data['table_name'] = $this->table_name;
		$this->load->view('templates/__header', array('config'=>$data['config'],'breadcrumbs'=>$breadcrumbs));
		$this->load->view('show', $data);
		$this->load->view('templates/__footer');
	}
	
	private function getFieldMap($config) {
		$map = Array();
		$tableAttribs = $config['configuration']['tables'][$this->table_name];
		if(!isset($config['configuration']['tables'][$this->table_name]))
			return FALSE;
		foreach($tableAttribs['fields'] as $field=>$attribs) {
			if(isset($attribs['showOnList']) && $attribs['showOnList'] == true) $map[$field] = $attribs['friendly-name'];
		}
		return $map;
	}

	private function getList($page=0, $orderBy=0) {
		return $this->db->get("{$this->table_name}", 10, 0)->result_array();
	}
	
	
}