<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

class Todo_model extends MY_Model {
	
	// use the table 'todos'
	protected $table		= 'todos';
	// the primary key is 'todo_id'
	protected $key			= 'todo_id';
	protected $soft_deletes	= true;
	protected $date_format	= 'datetime';
	// do not automatically set created_on or modified_on fields
	protected $set_created	= false;
	protected $set_modified	= false;

	// can also create new methods, or override the ones present in MY_Model later
}
