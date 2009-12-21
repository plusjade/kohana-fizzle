<?php
/*
 * I don't use an ide so i am not going to 
 * pretend I know what to put here.
 * For licencing see the COPYING file.
 * Thank you for your help - Jade.
 */
class Forum_Controller extends Template_Controller {

	# Set the name of the template to use
	public $template = 'public_forum/template';
	
	/*
	 * specifies the page your forum is on.
	 * Technically you'll have to change the class name as well.
	 * but you can also just call it internally and specify page_name here.
	 */
	public $page_name ='forum';

	# the filtering params passed via the url.
	public $filter;
	public $filter2;
	
	# the sorting params passed via $_GET;
	public $sort;
	public $sort_by;
	public $order;

	
	public function __construct()
	{
		parent::__construct();
		
		$this->template->title = 'Kohana Forum';
		
		# configure filters.
		$url_array	= $this->uri->segment_array();
		$this->action	= (empty($url_array['2']))
			? 'category'
			: $url_array['2'];
		$this->filter		= (isset($url_array['3']))
			? $url_array['3']
			: '';
		$this->filter2	= (isset($url_array['4']))
			? $url_array['4']
			: '';
			
		# configure sorters.
		$this->sort = (isset($_GET['sort']))
			? $_GET['sort']
			: 'newest';
		$this->sort_by = (empty($_GET['sort']) OR 'votes' == $_GET['sort'])
			? 'vote_count'
			: 'created';
		$this->order = (empty($_GET['sort']) OR 'oldest' != $_GET['sort'])
			? 'desc'
			: 'asc';
			
		/*
		 * Notes:
		 * We make everything run through _index or _ajax
		 * because it's easier to delegate modular functionality
		 * based on whether a request is ajax or not.
		 * mapping publically (i.e. site.com/controller/method)
		 * would mean we'd have to have ajax/non-ajax logic within each method
		 * which i think is harder to maintain and read.
		 */
			
		# handle ajax requests of course =0
		if(request::is_ajax())
			die($this->_ajax());

		# handle non-ajax.
		die($this->_index());
	}

/*
 * Routes all non-ajax forum requests.
 * Essentially builds the entire page using a wrapper and template.
 */ 
	public function _index()
	{			
		# allowed actions and their methods.
		$action_mapper = array(
			'category'	=> 'posts_wrapper',
			'view'			=> 'comments_wrapper',
			'submit'		=> 'add_post',
			'vote'			=> 'vote',
			'edit'			=> 'edit',
			'my'				=> 'my',
		);

		if(!array_key_exists($this->action, $action_mapper))
			Event::run('system.404');
		
		$wrapper = new View('public_forum/index');
		$method = $action_mapper[$this->action];
		$wrapper->content = $this->$method(); 
		$wrapper->categories	= $this->categories();
		
		/*
		# define your logged in user.
		# Note: this just makes it easier to change here.
		$wrapper->user = ($this->owner->logged_in())
			? $this->owner->get_user()
			: FALSE;
		*/
		
		$wrapper->user = FALSE;
		
		$this->template->content = $wrapper;
		die($this->template);
	}

	
/*
 * Ajax request router.
 * returns only the data/views we need to update DOM.
 */ 	
	public function _ajax()
	{
		switch($this->action)
		{				
			case 'category':				
				if(empty($_GET['sort']))
					return $this->posts_wrapper();
				return $this->get_posts_list();
				break;
				
			case 'view':
				if(empty($_GET['sort']))
					return $this->comments_wrapper();
				return $this->get_comments_list($this->filter);
				break;
				
			case 'submit':
				return $this->add_post();
				break;

			case 'vote':
				return $this->vote();
				break;
				
			case 'edit':
				return $this->edit();
				break;
				
			case 'my':
				# no sorter default to posts.
				if(empty($_GET['sort']))
					return $this->my($this->filter);
				
				# has sorter, so we fetch raw content lists
				if('posts' == $this->filter)
					return $this->my_posts_list();
				if('comments' == $this->filter)
					return $this->my_comments_list();
				if('starred' == $this->filter)
					return $this->my_starred_list();
				break;
		}
		
		return 'invalid url parameters';
	}

	
##-----------------##
## Private methods ##
##-----------------##
	
	
/*
 * get the categories object.
 */
	private function categories()
	{
		$categories = ORM::factory('forum_cat')->find_all();
		if(0 == $categories->count())
			return FALSE;
			
		return $categories;
	}

/*
 * return a properly formatted selected array  
 * in order to highlight the correct sort tab in non-ajax mode.
 */
	private static function tab_selected($default='newest')
	{
		$selected = array(
			'votes' => '',
			'newest'=> '',
			'oldest'=> '',
			'active'=> ''
		);		
		if(empty($_GET['sort']) OR !array_key_exists($_GET['sort'], $selected))
			$selected[$default] = 'class="selected"';
		else
			$selected["$_GET[sort]"] = 'class="selected"';
			
		return $selected;
	}
	
	
/*
 * returns the posts_wrapper with sorting tabs
 * and a populated posts_list.
 */
	private function posts_wrapper()
	{
		$view							= new View('public_forum/posts_wrapper');
		$view->category	 = (empty($this->filter)) ? 'all' : $this->filter;	
		$view->posts_list = $this->get_posts_list($view->category);
		$view->selected	 = self::tab_selected();
		return $view;
	}

	
/*
 * returns a view of a list of posts
 * based on the category filter and the sorters
 */
	private function get_posts_list()
	{
		$category = $this->filter;

		# filter posts by?
		$where = array();
		if('all' != $category)
			$where['forum_cats.url'] = $category;
			
		# order posts by?
		$order_by = ('newest' == $this->sort)
			? 'forum_cat_post_comment:created'
			: (('votes' == $this->sort)
				? 'forum_cat_post_comment:vote_count'
				: 'forum_cat_posts.last_active');

		$posts = ORM::factory('forum_cat_post')
			->select('forum_cat_posts.*, forum_cats.name, forum_cats.url')
			->with('forum_cat_post_comment')
			->join('forum_cats', 'forum_cats.id', 'forum_cat_posts.forum_cat_id')
			->where($where)
			->orderby("$order_by", 'desc')
			->find_all();
		if(0 == $posts->count())
			return 'No posts in this category';
		
		$view = new View('public_forum/posts_list');		
		$view->posts = $posts;
		return $view;
	}
	
	
/*
 * returns a singlular view of a post along with its 
 * associated comments.
 * so named because a post is techincally a "special" kind of comment.
 */
	private function comments_wrapper()
	{		
		if($_POST)
			$this->add_comment();

		$post_id = valid::id_key($this->filter);
		$owner_id = ($this->owner->logged_in())
			? $this->owner->get_user()->id
			: FALSE;
			
		# get the post with child comment.
		$post = ORM::Factory('forum_cat_post', $post_id)
			->select("
				forum_cat_posts.*, forum_cats.name, forum_cats.url,
				(SELECT owner_id 
					FROM forum_comment_votes
					WHERE forum_cat_post_comment_id = forum_cat_posts.forum_cat_post_comment_id
					AND owner_id = '$owner_id'
				) AS has_voted
			")
			->join('forum_cats', 'forum_cats.id', 'forum_cat_posts.forum_cat_id')
			->where('forum_cat_posts.id', $post_id)
			->find();
		if(!$post->loaded)
			Event::run('system.404');

		$view = new View('public_forum/posts_comments_wrapper');
		$view->post			= $post;
		$view->is_logged_in	= $this->owner->logged_in();	
		$view->owner	= $owner_id;
		$view->comments_list = $this->get_comments_list($post_id);
		$view->selected		= self::tab_selected('votes');		
		return $view;
	}
	
	
/*
 * returns the actual comments list associated with a post.
 * which gets injected into the "wrapper" above depending on ajax/non-ajax
 */
	private function get_comments_list($post_id)
	{
		$owner_id = ($this->owner->logged_in())
			? $this->owner->get_user()->id
			: FALSE;

		$comments = ORM::Factory('forum_cat_post_comment')
			->select("
				forum_cat_post_comments.*,
				(SELECT owner_id 
					FROM forum_comment_votes
					WHERE forum_cat_post_comment_id = forum_cat_post_comments.id
					AND owner_id = '$owner_id'
				) AS has_voted
			")
			->where(array(
				'forum_cat_post_comments.forum_cat_post_id' => $post_id,
				'forum_cat_post_comments.is_post !=' => '1',
			))
			->orderby("forum_cat_post_comments.$this->sort_by", "$this->order")
			->find_all();
		if(0 == $comments->count())
			return 'No comments yet';

		$view = new View('public_forum/comments_list');
		$view->is_logged_in	= $this->owner->logged_in();	
		$view->owner	= $owner_id;			
		$view->comments		= $comments;
		return $view;
	}


	##--------------------------------##
	## Stuff for logged in users only ##
	##--------------------------------##

		
/*
 * add a comment to a valid post
 */ 
	private function add_comment()
	{
		if(!$this->owner->logged_in())
			return new View('public_forum/login');
		
		if(empty($_POST['body']))
			return 'Reply cannot be empty.';
		
		$post_id = $this->filter;
		$new_comment = ORM::Factory('forum_cat_post_comment');
		$new_comment->forum_cat_post_id = $post_id;
		$new_comment->owner_id	= $this->owner->get_user()->id;
		$new_comment->body			= $_POST['body'];
		$new_comment->save();
		return 'Thank you, your comment has been added!';		
	}

	
	
/*
 * view and handler to add a new post to a category.
 */
	private function add_post()
	{
		if(!$this->owner->logged_in())
			return new View('public_forum/login');
		
		if($_POST)
		{
			# validate submit form.
			$post = new Validation($_POST);
			$post->pre_filter('trim');
			$post->add_rules('title', 'required');
			$post->add_rules('body', 'required');

			# on error
			if(!$post->validate())
			{
				# get the categories
				$categories = $this->categories();
				if(!$categories)
					return 'There are no categories to add posts to =(';
					
				$view = new View('public_forum/submit');
				$view->categories = $categories;
				$view->errors = $post->errors();
				$view->values = $_POST;
				return $view;
			}

			# add the new post.
			$new_post = ORM::Factory('forum_cat_post');
			$new_post->forum_cat_id	= $_POST['forum_cat_id'];
			$new_post->title				= $_POST['title'];
			$new_post->save();
			
			# posts are technically specially marked comments.
			# add the child comment.
			$new_comment = ORM::Factory('forum_cat_post_comment');
			$new_comment->forum_cat_post_id	= $new_post->id;
			$new_comment->owner_id	= $this->owner->get_user()->id;
			$new_comment->body				= $_POST['body'];
			$new_comment->is_post			= '1';
			$new_comment->save();
			
			# update post with comment_id.
			$new_post->forum_cat_post_comment_id = $new_comment->id;
			$new_post->save();
			
			# output a success message.
			$status = new View('public_forum/status');
			$status->success = true;
			return $status;
		}
		
		# get the categories
		$categories = $this->categories();
		if(!$categories)
			return 'There are no categories to add posts to =(';
			
		$view = new View('public_forum/submit');
		$view->categories = $categories;
		return $view;
	}

/*
 * cast a vote
 * users can vote for comments/posts either up or down.
 * vote is added to comments, and logged so user can only vote once per comment.
 * TODO: degrade this for non-ajax requests.
 */	
	private function vote()
	{
		if(!$this->owner->logged_in())
			return new View('public_forum/login');
		
		$comment_id = valid::id_key($this->filter);
		$vote = $this->filter2;
		
		$has_voted = ORM::factory('forum_comment_vote')
			->where(array(
				'owner_id'		 	=> $this->owner->get_user()->id,
				'forum_cat_post_comment_id' => $comment_id
			))
			->find();	
		if(TRUE == $has_voted->loaded)
			return 'already voted.';
			
		$vote = ('down' == $vote) ? -1 : 1 ;
		
		$comment = ORM::factory('forum_cat_post_comment', $comment_id);		
		$comment->vote_count = ($comment->vote_count + $vote);
		$comment->save();		

		# log the vote.
		$log_vote = ORM::factory('forum_comment_vote');
		$log_vote->owner_id = $this->owner->get_user()->id;
		$log_vote->forum_cat_post_comment_id = $comment_id;
		$log_vote->save();

		return 'Vote has been accepted!';
	}	


	
/*
 * edit a comment or post.
 * TODO: check to see if i made sure the comment belogns to user.
 */
	private function edit()
	{
		if(!$this->owner->logged_in())
			return new View('public_forum/login');
		
		$type = $this->filter;
		$id = valid::id_key($this->filter2);
		
		if(!empty($_POST['body']))
		{			
			if('post' == $type)
			{
				$post = ORM::Factory('forum_cat_post', $id);
				$post->title = $_POST['title'];
				$id =  $post->forum_cat_post_comment_id;
				$post->save();
			}
			
			$comment = ORM::Factory('forum_cat_post_comment', $id);
			$comment->body = $_POST['body'];
			$comment->save();
			
			# output a success message.
			$status = new View('public_forum/status');
			$status->success = true;
			$status->message = 'Edits Saved!!';
			return $status;
		}

		$view = new View('public_forum/edit');
		
		switch($type)
		{
			case 'post':
				$post = ORM::Factory('forum_cat_post')
					->find($id);
				if(!$post->loaded)
					return 'invalid post id';
				$view->post = (TRUE == $post->loaded) ? $post : FALSE ;
				$comment_id = $post->forum_cat_post_comment_id;
				break;	
			case 'comment':
				$view->post = FALSE;
				$comment_id = $id;
				break;	
			default:
				Event::run('system.404');
		}
		
		$comment = ORM::Factory('forum_cat_post_comment')
			->where('owner_id', $this->owner->get_user()->id)
			->find($comment_id);
		if(!$comment->loaded)
			return 'invalid comment id';
	
		$view->comment = $comment;
		$view->type	  = $type;
		$view->id	  = $id;
		return $view;
	}

	
	
/*
 * returns a view wrapper for the "my" interface.
 * the interface currently lists a users posts and comments.
 */
	private function my()
	{
		if(!$this->owner->logged_in())
			return new View('public_forum/login');
		
		$type = empty($this->filter) ? 'posts' : $this->filter;
		
		$allowed = array('posts', 'comments', 'starred');
		if(!in_array($type, $allowed))
			Event::run('system.404');
			
		$wrapper = new View('public_forum/my_index');
		$wrapper->type = $type;

		$type = "my_{$type}_list";
		$wrapper->items_list = $this->$type();

		$wrapper->selected = self::tab_selected(); 
		return $wrapper;
	}
	
	
/*
 * return a list of posts belonging to the logged in user.
 */
	private function my_posts_list()
	{
		$posts = ORM::factory('forum_cat_post')
			->select('forum_cat_posts.*, forum_cats.name, forum_cats.url')
			->with('forum_cat_post_comment')
			->join('forum_cats', 'forum_cats.id', 'forum_cat_posts.forum_cat_id')
			->where('forum_cat_post_comment.owner_id', $this->owner->get_user()->id)
			->orderby("forum_cat_post_comment.$this->sort_by", $this->order)
			->find_all();
		if(0 == $posts->count())
			return 'No posts created yet.';

		$view			= new View('public_forum/posts_list');	
		$view->posts 	= $posts;
		return $view;
	}

/*
 * return a list of comments belonging to the logged in user.
 */
	private function my_comments_list()
	{
		$comments = ORM::factory('forum_cat_post_comment')
			->with('forum_cat_post')
			->where(array(
				'owner_id'	=> $this->owner->get_user()->id,
				'is_post'		=> '0'
			))
			->orderby("forum_cat_post_comments.$this->sort_by", $this->order)
			->find_all();
		if(0 == $comments->count())
			return 'No comments added yet.';
			
		$view = new View('public_forum/my_comments_list');
		$view->comments = $comments;
		return $view;
	}

/*
 * return a list of posts starred by the logged in user.
 * TODO: obviously this is not yet enabled =/
 */
	private function my_starred_list()
	{
		return 'this is the starred list';
	}
	

	
} /*end*/


	/* -- All done!
	 * I tried to make this as clear as possible.
	 * let me know if you have any ideas/feedback/bug reports.
	 * Thank you! -Jade @ github.com/plusjade & plusjade@gmail.com
	 */
	 
	 