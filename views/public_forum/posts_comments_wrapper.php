

<div id="breadcrumb_wrapper">
	&#8592; <a href="<?php echo url::site("$this->page_name/category/$post->url")?>" class="forum_load_main"><?php echo $post->name?></a>
</div>

<div id="main_post">
	
	<div class="title">
		<a href="<?php echo url::site("$this->page_name/view/$post->id").'/'.valid::filter_php_url($post->title)?>" class="forum_load_main post_link">
			<?php echo $post->title?>
		</a>
	</div>

	<div class="votes">
		<?php if($is_logged_in AND empty($post->has_voted) AND $owner != $post->forum_cat_post_comment->owner->id):?>
			<a href="<?php echo url::site("$this->page_name/vote/{$post->forum_cat_post_comment->id}/up")?>" class="cast_vote" rel="1">Up</a>
			<br><span><?php echo $post->forum_cat_post_comment->vote_count?></span>
			<br><a href="<?php echo url::site("$this->page_name/vote/{$post->forum_cat_post_comment->id}/down")?>" class="cast_vote">down</a> 
		<?php else:?>
			<span><?php echo $post->forum_cat_post_comment->vote_count?></span>
		<?php endif;?>
	</div>
	
	<div class="comment_body"><?php echo $post->forum_cat_post_comment->body?></div>

	<div class="comment_meta">
		<div class="meta">
			by:<a href="/users/profile/<?php echo $post->forum_cat_post_comment->owner->username?>"><?php echo $post->forum_cat_post_comment->owner->username?></a>
			 - <?php echo build::timeago($post->forum_cat_post_comment->created)?>
		</div>
		<?if($owner == $post->forum_cat_post_comment->owner->id):?>
			<div class="owner_actions"><a href="<?php echo url::site("$this->page_name/edit/post/$post->id")?>">edit</a> - <a href="">delete</a></div>
		<?endif;?>
	</div>	
</div>

<div class="clearboth"></div>
	
<ul class="sort_list">
	<li class="floatleft"><b><?php echo $post->comment_count-1?> Replies</b></li>
	<li><a href="<?php echo url::site("$this->page_name/view/$post->id")?>/?sort=votes" <?php echo $selected['votes']?>>Votes</a></li>
	<li><a href="<?php echo url::site("$this->page_name/view/$post->id")?>/?sort=newest" <?php echo $selected['newest']?>>Newest</a></li>
	<li><a href="<?php echo url::site("$this->page_name/view/$post->id")?>/?sort=oldest" <?php echo $selected['oldest']?>>Oldest</a></li>
</ul>

<div id="list_wrapper" class="forum_comments_list_wrapper">
	<?php echo $comments_list?>
</div>

<?php if($is_logged_in): ?>
	<div id="add_comment_wrapper">
		<form id="submit_comment" action="<?php echo url::site("$this->page_name/view/$post->id")?>" method="POST">
			<input type="hidden" name="post_id" value="<?php echo $post->id?>">
			
			<fieldset>
				<label>Add Reply</label>
				<br/><textarea name="body" rel="text_req"></textarea>
			</fieldset>
			<p><button type="submit">Add Reply</button></p>
		</form>
	</div>
<?php else:?>
	<a href="/admin?ref=<?php echo $_SERVER['REQUEST_URI']?>">Login</a> to post a reply.
<?php endif;?>

