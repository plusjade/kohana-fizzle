<?php defined('SYSPATH') OR die('No direct access allowed.');

class Forum_Cat_Post_Comment_Model extends ORM {

	// Relationships
	protected $belongs_to = array('forum_cat_post');
	protected $has_one = array('owner');
	protected $has_and_belongs_to_many = array('forum_comment_votes');
	protected $load_with = array('owner');


	/**
	 * Overload saving to increase comment count and set last_active time.
	 * does not include edits of existing comments.
	 */
	public function save()
	{
		if ($this->loaded === FALSE)
		{
			$this->created = time();
			
			# update the parent post of this comment.
			$post = ORM::factory('forum_cat_post', $this->forum_cat_post_id);
			$post->comment_count = ++$post->comment_count;
			$post->last_active = time();
			$post->save();
		}
		return parent::save();
	}
	

} // End forum_cat_post_comment Model