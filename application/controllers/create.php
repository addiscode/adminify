<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Create extends CI_Controller {
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
		$this->load->view('templates/__header', $data);
		$this->load->view('form', $data);	
		$this->load->view('templates/__footer');
	}
	
	private function insert() {
		$data['config'] = Spyc::YAMLLoad(BASEPATH . '../admin/application/config/config.yaml');
		$log_file = BASEPATH. '../admin/application/logs/admin.log';
		$error = array();
		$params = $this->input->post();
		unset($params['submit']);
		$data['widgets'] = $this->getWidgets($data['config']);
		$data['errors'] = array();
		$fileFields = array();
		foreach($data['widgets'] as $widget) {
			if($widget['widget'] == 'file') {
				$fileFields[] = $widget['name'];
			}
			$this->form_validation->set_rules($widget['name'], $widget['label'], $widget['validation']);
		}
		if($this->form_validation->run() == TRUE) {
			$error = array();
			foreach($fileFields as $thisField) {
				$error = $this->do_upload($thisField,
								 $data['widgets'][$thisField]['config']['upload_path'],
								 $data['widgets'][$thisField]['config']['max_width'],
								 $data['widgets'][$thisField]['config']['max_height']
								);
			}
			if(empty($error)) {
				$data['params'] = array_merge($params,$this->file_params);
				$this->db->insert(plural($this->table_name),$data['params']);
				$this->session->set_userdata("message", array('class'=>'alert-success','msg'=> singular($this->table_name) . " has been updated!"));
				$fh = fopen($log_file, 'a+');
				fwrite($fh, date("d-m-Y @ H:i:s") . " | New " . singular($this->table_name) . " has been created!\n");
				unset($fh);
				redirect("show/f/" . plural($this->table_name));
			}else {
				$data['errors'] = $error;
				$this->displayErrors($data);
				return;
			}
		} else {
			$this->displayErrors($data);
			return;
		}
	}
	
	private function displayErrors($data) {
		$this->form_validation->set_error_delimiters("<p>","</p>");
		$data['table_name'] = $this->table_name;
		$data['validationFailed'] = TRUE;
		$data['params'] = $this->input->post();
		$data['breadcrumbs'] = array(
				array('title'=>plural($this->table_name),
						'href'=> base_url() . "index.php/show/f/" . plural($this->table_name)),
				array('title'=>'New ' . singular($this->table_name),
						'href'=>base_url() . "index.php/create/f/" . singular($this->table_name))
		);
		$this->load->view("templates/__header",$data);
		$this->load->view("form",$data);
		$this->load->view("templates/__footer");
	}
	
	private function getWidgets($config) {
		$fields = $config['configuration']['tables'][$this->table_name]['fields'];
		$widgets = array();
		foreach($fields as $field=>$attribs) {
			$validation = explode("|", $attribs['validation']);
			$options = array();
			if(isset($attribs['relation'])) {
				$options = array('0'=>'Select');
				$this->db->select($attribs['relation']['value'] . ',' . $attribs['relation']['label']);
				$rows = $this->db->get($attribs['relation']['table'])->result_array();
				foreach($rows as $row) {
					$options[$row[$attribs['relation']['value']]] = $row[$attribs['relation']['label']];
				}
			}
			$widgets[$field] = array(
					'name'=>$field,
					'label'=>$attribs['friendly-name'],
					'widget'=>$attribs['widget'],
					'validation'=>$attribs['validation'],
					'required'=>(in_array("required", $validation))?true:false,
					'options'=>$options,
					'mode'=> empty($options)?"":$attribs['relation']['mode'],
					'config'=>($attribs['widget'] == 'file')?$attribs['config']:array()
					);
			
		}
		return $widgets;
	}
	
	private function do_upload($field,$dir,$max_width,$max_height,$isRequired=FALSE,$file_name="",$max_size=1500,$allowed_types="gif|jpg|jpeg|png") {
		//FILE UPLOADING WITH VALIDATION
		$this->load->library("image_lib");
		$error=array();
		$data = array();
		$config = array(
				'upload_path'  => $dir,
				'allowed_types' => $allowed_types,
				'overwrite' => TRUE,
				'max_size' => $max_size
		);
		
		$this->upload->initialize($config);
		if ($this->upload->do_upload($field))
		{
			$data = $this->upload->data();
			$file_name = $data['raw_name'] . "*" . time() .".".array_pop(explode(".",$data['file_name']));
			rename($data['full_path'], $data['file_path']."/". $file_name);
			if($field == "image" || $field == "img") {
				$this->image_lib->initialize(array(
						'imgae_library'=>'gd2',
						'source_image'=>$data['file_path']."/".$file_name,
						'maintain_ratio'=>TRUE,
						'create_thumb' => TRUE,
						'thumb_marker' => "_thumb",
						'width'=>77,
						'height'=>87
						));
				if($this->image_lib->resize())
					$error = $this->image_lib->display_errors();
			}
			$this->file_params[$field] = $file_name;
			return $error;
		}
		return  array($this->upload->display_errors("<p>","</p>"));
		//END  OF FILE UPLOAD
	}
}

?>