<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Streetview_Model extends MY_MODEL {

	public function lookup_image_id ($form_data) {
		// look up ID in database, and return lat,long
		// return false if not found in db
		
		// get an int value from the image_id string
		$img_id = intval($form_data['image_id']);
		
		// load the pulse database
		$pulse_db = $this->load->database('pulse', True);

		// find the id in the database
		$find = "SELECT lat,lng,heading,pitch FROM places WHERE id = ?";
		$lat_lng = $pulse_db->query($find, array($img_id))->result_array();
		
		// the query will return false if not found in db
		return $lat_lng;
	}


}
