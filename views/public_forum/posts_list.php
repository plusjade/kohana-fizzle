

<?php foreach($posts as $post):?>
	<div class="each_post_wrapper">
		<div class="votes">
			<span><?php echo $post->forum_cat_post_comment->vote_count?></span>
			<br/>votes
		</div>
		
		<div class="comments">
			<span><?php echo --$post->comment_count?></span>
			replies
		</div>
		
		<div class="summary">
			<div class="title">
				<a href="<?php echo url::site("$this->page_name/view/$post->id").'/'.valid::filter_php_url($post->title)?>" class="forum_load_main"><?php echo $post->title?></a>
			</div>
			<div>
				<a href="#" class="preview" rel="<?php echo $post->id?>">preview</a>
				created by <a href="/profile/<?php echo $post->forum_cat_post_comment->owner->username?>"><?php echo $post->forum_cat_post_comment->owner->username?></a>
				in <a href="<?php echo url::site("$this->page_name/category/$post->url")?>" class="forum_load_main"><?php echo $post->name?></a>
				 <?php echo forum::timeago($post->forum_cat_post_comment->created)?>
				<span>
				<em>last active</em> <?php echo forum::timeago($post->last_active)?>
				</span>
			</div>
		</div>
		
	</div>
	
	<div class="post_comment" id="preview_<?php echo $post->id?>"><?php echo $post->forum_cat_post_comment->body?></div>
<?php endforeach;?>
