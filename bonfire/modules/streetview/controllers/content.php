<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Content extends Admin_Controller {

	public function __construct() {
		parent::__construct();

		Template::set('toolbar_title', 'Streetview loader');
		
		$this->load->model('Streetview_Model', 'streetview_model', true);
	}

	//---------------------------------------------------------------------------------------------------------------------------------------

	public function index() {
		if ($this->input->post('submit')) {
			// should probably validate BEFORE sending string off to lookup
			$this->form_validation->set_rules('image_id', 'Image ID', 'trim|strip_tags|xss_clean|required|is_natural');
			
			if ($this->form_validation->run() == FALSE) {
				Template::set_message('Invalid input', 'error');
			} else {
				$result = $this->streetview_model->lookup_image_id($_POST);
				if ($result == FALSE) {
					Template::set_message('Image not found', 'error');
				} else {
					Template::set_message('Image successfully found', 'success');
					Template::set('image_info', $result);
				}
			}
		}
		Template::render();
	}

	//---------------------------------------------------------------------------------------------------------------------------------------

}
