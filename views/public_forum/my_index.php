

<h1><?php echo $this->owner->get_user()->username?>'s stuff.</h1>


<ul class="sort_list">
	<li class="floatleft"><b>Your <?php echo $type?></b></li>
	<li><a href="<?php echo url::site("$this->page_name/my/$type")?>/?sort=newest" <?php echo $selected['newest']?>>Newest</a></li>
	<li><a href="<?php echo url::site("$this->page_name/my/$type")?>/?sort=oldest" <?php echo $selected['oldest']?>>Oldest</a></li>
	<li><a href="<?php echo url::site("$this->page_name/my/$type")?>/?sort=votes" <?php echo $selected['votes']?>>Votes</a></li>
</ul>


<div id="list_wrapper" class="forum_<?php echo $type?>_list_wrapper">
	<?php echo $items_list?>
</div>


