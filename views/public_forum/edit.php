


<form id="forum_edit_comment" action="<?php echo url::site("$this->page_name/edit/$type/$id")?>" method="POST" class="fieldsets">
	
	<h3>Edit Your Entry</h3>
	
	<? if(FALSE != $post):?>
		<b>Title</b>
		<br><input type="text" name="title" value="<?php echo $post->title?>" rel="text_req">
		<br><br>
	<? endif;?>
	
	
	<b>Body</b>
	<br><textarea name="body" style="width:100%;height:150px"><?php echo $comment->body?></textarea>
	
	<br><br>
	<button type="submit">Save Changes</button>
</form>


<script type="text/javascript">

</script>




