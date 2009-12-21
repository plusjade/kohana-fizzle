<?php defined('SYSPATH') OR die('No direct access allowed.');

class Forum_Comment_Vote_Model extends ORM {

	// Relationships
	protected $has_and_belongs_to_many = array('owners');
	protected $primary_key = 'owner_id';



	public function save()
	{
		# TODO: possibly log the vote in the comment table. 
		return parent::save();
	}

	

} // End form_comment_vote Model

