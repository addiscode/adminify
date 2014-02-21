<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Delete extends CI_Controller {
	private $table_name;
	private $file_params = array();
	
	public function __construct() {
		parent::__construct();
		$this->load->database();
		$this->load->library(array('form_validation','session','upload'));
	}
	
	private function is_authenticated() {
		if(!$this->session->userdata("user"))
		    redirect("/welcome/login");
	}
	
	public function f() {
		$this->is_authenticated();
		$data['config'] = Spyc::YAMLLoad(BASEPATH . '../admin/application/config/config.yaml');
		$this->table_name = plural($this->uri->segment(3));
		if($this->input->post('submit')) {
			$this->insert();
			return;
		}
		$data['breadcrumbs'] = array(
				array('title'=>plural($this->table_name),
						'href'=> base_url() . "index.php/show/f/" . plural($this->table_name)),
				array('title'=>'New ' . singular($this->table_name),
					 'href'=>base_url() . "index.php/create/f/" . singular($this->table_name))
		);
		$data['table_name'] = $this->table_name;
		$data['widgets'] = $this->getWidgets($data['config']);
		$this->delete();
		redirect('show/f/'. plural($this->table_name));
	}
	
	private function getWidgets($config) {
		$fields = $config['configuration']['tables'][$this->table_name]['fields'];
		$widgets = array();
		foreach($fields as $field=>$attribs) {
			$validation = explode("|", $attribs['validation']);
				
			$widgets[$field] = array(
					'name'=>$field,
					'label'=>$attribs['friendly-name'],
					'widget'=>$attribs['widget'],
					'validation'=>$attribs['validation'],
					'required'=>(in_array("required", $validation))?true:false,
					'config'=>($attribs['widget'] == 'file')?$attribs['config']:array()
			);
		}
		return $widgets;
	}
	
	private function delete() {
		$data['config'] = Spyc::YAMLLoad(BASEPATH . '../admin/application/config/config.yaml');
		$error = array();
		$id = $this->uri->segment(4);
		$dbParams = array_pop($this->db->get_where(plural($this->table_name), array('id'=>$id))->result_array());
		$data['config'] = $config;
		$data['widgets'] = $this->getWidgets($config);
		$data['errors'] = array();
		$fileFields = array();
		foreach($data['widgets'] as $widget) {
			if($widget['widget'] == 'file') {
				$fileFields[] = $widget['name'];
				$filePath = $data['widgets'][$widget['name']]['config']['upload_path'] . "/" . $dbParams[$widget['name']];
				unlink($filePath);
			}
		}
		$this->db->delete(plural($this->table_name), array('id'=>$id));
		$log_file = BASEPATH. '../admin/application/logs/admin.log';
		$this->session->set_userdata("message", array('class'=>'alert-success','msg'=> singular($this->table_name) . " has been updated!"));
		$fh = fopen($log_file, 'a+');
		fwrite($fh, date("d-m-Y @ H:i:s") . " | " . singular($this->table_name) . " has been removed!\n");
		unset($fh);
	}
}
?>