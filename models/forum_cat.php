<?php defined('SYSPATH') OR die('No direct access allowed.');

class Forum_Cat_Model extends ORM {

	// Relationships
	protected $belongs_to	= array('forum');
	protected $has_many		= array('forum_cat_post');
	protected $sorting		= array('position' => 'asc');

} // End forum_cat Model