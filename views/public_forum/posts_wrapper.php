

<div id="breadcrumb_wrapper">
	<a href="<?php echo url::site("$this->page_name/category/$category")?>" class="forum_load_main"><?php echo $category?></a> &#8594;
</div>

<ul class="sort_list">
	<li><a href="<?php echo url::site("$this->page_name/category/$category")?>/?sort=newest" <?php echo $selected['newest']?>>Newest</a></li>
	<li><a href="<?php echo url::site("$this->page_name/category/$category")?>/?sort=votes" <?php echo $selected['votes']?>>Votes</a></li>
	<li><a href="<?php echo url::site("$this->page_name/category/$category")?>/?sort=active" <?php echo $selected['active']?>>Recently Active</a></li>
</ul>

<div id="list_wrapper" class="forum_posts_list_wrapper">
	<?php echo $posts_list?>
</div>
