<?php defined('SYSPATH') OR die('No direct access allowed.');

class Forum_Cat_Post_Model extends ORM {

	// Relationships
	protected $belongs_to = array('forum_cat');
	protected $has_one = array('forum_cat_post_comment');
	protected $has_many = array('forum_cat_post_comment');

} // End form_cat_post Model