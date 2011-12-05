<?php 

if (! defined('BASEPATH')) exit('No direct script access');

class Pulse_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	public function vote($data)
	{
		$this->db->insert('votes', $data);
	}
	public function report($data)
	{
		$this->db->insert('report', $data);
	}
	public function voter_info($data)
	{
		$this->db->insert('voters', $data);
	}
	public function clear_ranking_by_question($id_question, $uuid = null)
	{
		$this->db->where('id_question', $id_question);
		$this->db->where('uuid', $uuid);
		$this->db->delete('ranking');
	}
	public function dbi($id)
	{
		echo "$id was deleted from the database, all votes associated with point were deleted and it was removed from reported images.";
		$this->db->where('id', $id);
		$this->db->delete('places');
		$this->db->where('id_left', $id);
		$this->db->delete('votes');
		$this->db->where('id_right', $id);
		$this->db->delete('votes');
		$this->db->where('id_places', $id);
		$this->db->delete('report');
	}
	public function kbi($id)
	{
		echo "$id was kept and removed from reported images.";
		$this->db->where('id_places', $id);
		$this->db->delete('report');
	}
	
	public function insert_ranking($data)
	{
		print_r($data);
		$this->db->insert_batch('ranking', $data);
	}
	public function getpairbystudyid($id_city)
	{
		$random = rand(1,4);
		$this->db->select('id, file_location_400_300');
		$this->db->from('places');
		if($random != 1)
		{
			$this->db->where("id > 3470");
		}	
		$this->db->order_by('id', 'RANDOM');
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array(0); 
			$data['id_left'] = $row['id'];
			$data['id_left_file_location_400_300'] = $row['file_location_400_300'];
		}
		else
		{
			show_error('Data Error', 'Please email the administrator if you get this message again.');
		}
		$this->db->select('id, file_location_400_300');
		$this->db->from('places');
		$this->db->order_by('id', 'RANDOM');
		$this->db->limit(1);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array(0); 
			$data['id_right'] = $row['id'];
			$data['id_right_file_location_400_300'] = $row['file_location_400_300'];
		}
		else
		{
			show_error('Data Error', 'Please email the administrator if you get this message again.');
		}
		return $data;
	}
	public function init_candidates($id=null)
	{
		$this->db->select('id, file_location_400_300');
		$this->db->from('places');
		$this->db->where("id_city = " . rand(1,5));
		$this->db->order_by('id', 'RANDOM');
		$this->db->limit(4);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array(0); 
			$data['id_left'] = $row['id'];
			$data['id_left_file_location_400_300'] = $row['file_location_400_300'];
			$row = $query->row_array(1);
			$data['id_right'] = $row['id'];
			$data['id_right_file_location_400_300'] = $row['file_location_400_300'];
			$row = $query->row_array(2); 
			$data['id_left_preload'] = $row['id'];
			$data['id_left_file_location_400_300_preload'] = $row['file_location_400_300'];
			$row = $query->row_array(3);
			$data['id_right_preload'] = $row['id'];
			$data['id_right_file_location_400_300_preload'] = $row['file_location_400_300'];
			return $data;
		}
		else
		{
			show_error('Data Error', 'Please email the administrator if you get this message again.');
		}
	}
	public function get_question($id_question=null)
	{
		if(is_null($id_question))
		{
			return $this->get_random_question();
		}
		else
		{
			$this->db->select('id, question');
			$this->db->from('questions');
			$this->db->where('id = '.$id_question);
			$query = $this->db->get();
			$data = array();
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array(0); 
				$data['id'] = $row['id'];
				$data['question'] = $row['question'];
			}
			else
			{
				show_error('Data Error', 'Please email the administrator if you get this message again.');
			}
			$this->db->select('id, question');
			$this->db->from('questions');
			$this->db->where('id != '.$id_question);
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array(0); 
				$data['id2'] = $row['id'];
				$data['question2'] = $row['question'];
				$row = $query->row_array(1); 
				$data['id3'] = $row['id'];
				$data['question3'] = $row['question'];
			}
			else
			{
				show_error('Data Error', 'Please email the administrator if you get this message again.');
			}
			return $data;
		}
	}
	public function get_single_question_by_combo($id_ranking_combo = null)
	{
		$this->db->select('questions.question');
		$this->db->from('questions');
		$this->db->join('ranking_combo', 'questions.id = ranking_combo.to_question');
		$this->db->where('ranking_combo.id = ' . $id_ranking_combo);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array(0); 
			return $row['question'];
		}
		else
		{
			show_error('Data Error', 'Please email the administrator if you get this message again.');
		}
	}
	public function get_to_place_by_combo($id_ranking_combo = null)
	{
		$this->db->select('to_place');
		$this->db->from('ranking_combo');
		$this->db->where('ranking_combo.id = ' . $id_ranking_combo);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array(0); 
			if(!is_null($row['to_place']))
			{
				return $row['to_place'];
			}
			else
			{
				return -1;
			}
		}
		else
		{
			return -1;
		}
	}
	public function get_place_by_combo($id_ranking_combo = null)
	{
		$this->db->select('city.name, city.country');
		$this->db->from('city');
		$this->db->join('ranking_combo', 'city.id = ranking_combo.to_place');
		$this->db->where('ranking_combo.id = ' . $id_ranking_combo);
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array(0); 
			return $row['name'] . ", " . $row['country'];
		}
		else
		{
			return "All Cities";
		}
	}
	public function get_question_by_combo($id_ranking_combo = null)
	{
		if(is_null($id_ranking_combo))
		{
			return $this->get_random_question();
		}
		else
		{
			$this->db->select('questions.id, questions.question');
			$this->db->from('questions');
			$this->db->join('ranking_combo', 'questions.id = ranking_combo.to_question');
			$this->db->where('ranking_combo.id = ' . $id_ranking_combo);
			$query = $this->db->get();
			$data = array();
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array(0); 
				$data['id'] = $row['id'];
				$data['question'] = $row['question'];
			}
			else
			{
				show_error('Data Error', 'Please email the administrator if you get this message again.');
			}
			$this->db->select('id, question');
			$this->db->from('questions');
			$this->db->where('id != '.$data['id']);
			$query = $this->db->get();
			if ($query->num_rows() > 0)
			{
				$row = $query->row_array(0); 
				$data['id2'] = $row['id'];
				$data['question2'] = $row['question'];
				$row = $query->row_array(1); 
				$data['id3'] = $row['id'];
				$data['question3'] = $row['question'];
			}
			else
			{
				show_error('Data Error', 'Please email the administrator if you get this message again.');
			}
			return $data;
		}
	}
	public function get_random_question()
	{
		$this->db->select('id, question');
		$this->db->from('questions');
		$this->db->order_by('id', 'RANDOM');
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array(0); 
			$data['id'] = $row['id'];
			$data['question'] = $row['question'];
			$row = $query->row_array(1); 
			$data['id2'] = $row['id'];
			$data['question2'] = $row['question'];
			$row = $query->row_array(2); 
			$data['id3'] = $row['id'];
			$data['question3'] = $row['question'];
			return $data;
		}
		else
		{
			show_error('Data Error', 'Please email the administrator if you get this message again.');
		}
	}
	public function get_total_votes()
	{
		$this->db->from('votes');
		return $this->db->count_all_results();
	}
	public function check_id($uuid_pulse)
	{
		$this->db->from('voters');
		$this->db->where("uuid_pulse = '$uuid_pulse'");
		return $this->db->count_all_results();
	}
}