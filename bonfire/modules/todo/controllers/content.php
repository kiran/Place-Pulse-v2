<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Content extends Admin_Controller {
	
	public function __construct()
	{
		parent::__construct();
	
		Template::set('toolbar_title', 'ToDo List');

		$this->load->model('Todo_model', 'todo_model', true);
	}

	//---------------------------------------------------------------------------------------------------------------------------------------------

	public function index()
	{
		// checks if a form has been submitted.  if so, run create_item, and check if an item is successfully created.
		if ($this->input->post('submit'))
		{
			if ($this->create_item($_POST)) {
				Template::set_message('New ToDo item successfully created', 'success');
			} else {
				Template::set_message('Error creating new ToDo item.', 'error');
			}
		}

		// Template::set makes a variable name $items available in the view
		Template::set('items', $this->todo_model->order_by('todo_id', 'desc')->find_all_by('deleted', 0));		

		Template::render();

	}

	//---------------------------------------------------------------------------------------------------------------------------------------------

	private function create_item($vars)
	{
		$this->form_validation->set_rules('description', 'Description', 'required|trim|strip_tags|max_length[255]|xss_clean');

		if ($this->form_validation->run() != false)
		{
			$data = array (
				'description' => $vars['description']
			);

			return $this->todo_model->insert($data);
		}

		return false;

	}

	//---------------------------------------------------------------------------------------------------------------------------------------------

	public function delete()
	{
		$id = $this->uri->segment(5);

		$this->todo_model->delete($id);

		echo 'true';
		die();
	}
}
