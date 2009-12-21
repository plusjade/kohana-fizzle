


<form action="/get/edit_showroom/edit/<?php echo $cat->id?>" method="POST" class="ajaxForm" id="add_links_form" style="min-height:300px;">	
	
	<div  id="common_tool_header" class="buttons">
		<button type="submit" id="link_save_sort" class="jade_positive">Add Category</button>
		<div id="common_title">Add a New Category</div>
	</div>	
		<div id="common_tool_info">
		<?php if(! empty($message) ) echo $message?>
		</div>
		
		
	<div class="fieldsets">
		<b>Category Name</b>
		<br><input type="text" name="category" value="<?php echo $cat->name?>" rel="text_req" style="width:300px">
	</div>
	
</form>
