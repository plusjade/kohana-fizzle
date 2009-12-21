

<?php foreach($comments as $comment):?>
	<div class="each_comment_wrapper">
		<div class="votes">
			<?php if($is_logged_in AND empty($comment->has_voted) AND $owner != $comment->owner->id):?>
				<a href="<?php echo url::site("$this->page_name/vote/$comment->id/up")?>" class="cast_vote" rel="1">Up</a>
				<br/><span><?php echo $comment->vote_count?></span>
				<br/><a href="<?php echo url::site("$this->page_name/vote/$comment->id/down")?>" class="cast_vote">down</a> 
			<?php else:?>
				<span><?php echo $comment->vote_count?></span>
			<?php endif;?>
		
		</div>
		
		<div id="comment_<?php echo $comment->id?>" class="comment_body">
			<?php echo $comment->body?>
		</div>
		
		<div class="comment_meta">
			<div class="meta">
				 by:<a href="/profile/<?php echo $comment->owner->username?>"><?php echo $comment->owner->username?></a>
				 - <?php echo forum::timeago($comment->created)?>
			</div>
			<?if($owner == $comment->owner->id):?>
				<div class="owner_actions"><a href="<?php echo url::site("$this->page_name/edit/comment/$comment->id")?>" class="forum_load_main">edit</a> - <a href="#" class="forum_load_main">delete</a></div>
			<?endif;?>
		</div>
		<div class="clearboth"></div>
	</div>			
<?php endforeach;?>
