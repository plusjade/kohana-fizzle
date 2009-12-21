<?php

class Edit_Forum_Controller extends Edit_Tool_Controller {

/*
 *	edit the forum tool.
 */
	function __construct()
	{
		parent::__construct();	
	}
	
/*
 * manage the forum categories
 */
	function manage($tool_id=NULL)
	{
		valid::id_key($tool_id);
		
		$categories = ORM::factory('forum_cat')
			->where(array('forum_id' => $tool_id, 'fk_site' => $this->site_id))
			->find_all();
		#if(0 == $categories->count())
		#	die('no categories');
			
		$primary = new View('edit_forum/manage');
		$primary->tool_id = $tool_id;
		$primary->categories = $categories;
		die($primary);
	}


	
/*
 * Add categories
 */ 
	public function add($tool_id=NULL)
	{
		valid::id_key($tool_id);
		if(!empty($_POST['name']))
		{
			$_POST['url'] = (empty($_POST['url']))
				? $_POST['name'] : $_POST['url'];
			
			$new_cat			= ORM::factory('forum_cat');
			$new_cat->forum_id	= $tool_id;
			$new_cat->fk_site	= $this->site_id;
			$new_cat->name		= trim($_POST['name']);
			$new_cat->url		= valid::filter_php_url($_POST['url']);
			$new_cat->save();
			die("$new_cat->id|$new_cat->name|New Category Added.");
		}
		die('nothing sent');
	}

/*
 * edit a category
 */ 
	public function edit_category($cat_id=NULL)
	{

	}
	
	
/*

 */
	public function edit($id=NULL)
	{

	}

/*
 * delete a forum category
 */
	public function delete($id=NULL)
	{
		valid::id_key($id);
		
		ORM::factory('forum_cat')
			->where('fk_site', $this->site_id)
			->delete($id);
		die('Category deleted');
	}

/*
 */
	public function save_sort()
	{
		$db = new Database;	
		
		foreach($_GET['item'] as $position => $id)
			$db->update('forum_cats', array('position' => $position), "id = '$id'"); 	
		
		die('Item sort order saved'); # success
		
	}
	
/*

 */
	public function settings($tool_id=NULL)
	{
		die('Showroom settings are currently disabled while we update our code. Thanks!');
		valid::id_key($tool_id);
	}
	

	
/*
 */	
	public static function _tool_deleter($tool_id, $site_id)
	{
		# delete items_meta (items)
		return TRUE;	
	}

} /* -- end -- */