

<?php foreach($comments as $comment):?>
	<div class="each_comment_wrapper">
		<div class="title">
			<a href="<?php echo url::site("$this->page_name/view/{$comment->forum_cat_post->id}").'/'.valid::filter_php_url($comment->forum_cat_post->title)?>" class="forum_load_main"><?php echo $comment->forum_cat_post->title?></a>
		</div>
		
		<div class="votes">
				<span><?php echo $comment->vote_count?></span>
		</div>
		
		<div id="comment_<?php echo $comment->id?>" class="comment_body">
			<?php echo $comment->body?>
		</div>
		
		<div class="comment_meta">
			<div class="meta">
				 by:<a href="/users/profile/<?php echo $comment->owner->username?>"><?php echo $comment->owner->username?></a>
				 - <?php echo forum::timeago($comment->created)?>
			</div>
			<div class="owner_actions"><a href="<?php echo url::site("$this->page_name/edit/comment/$comment->id")?>" class="forum_load_main">edit</a> - <a href="#" class="forum_load_main">delete</a></div>
		</div>
		<div class="clearboth"></div>
	</div>			
<?php endforeach;?>
