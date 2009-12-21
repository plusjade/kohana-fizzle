
<span class="on_close">update-forum-<?php echo $tool_id?></span>

<div id="common_tool_header" class="buttons">
	<button type="submit" id="save_sort" class="jade_positive">Save Order</button>
	<div id="common_title">Rearrange Contact Order</div>	
</div>

<div class="common_left_panel">
	Click and hold the "drag" handle to re-position
	categories items.
	
	<p><span class="icon plus">&#160; &#160;</span> <a href="#" id="new_cat">New Category</a></p>	
</div>

<div class="common_main_panel">	
	<ul id="generic_sortable_list" class="ui-tabs-nav">
		<?php foreach($categories as $category):?>
			<li id="item_<?php echo $category->id?>" class="root_entry"> 
				<ul class="row_wrapper">
					<li class="drag_box"><span class="icon move"> &#160; &#160; </span> DRAG </li>
					<li class="position"><?php echo $category->position?>. </li>
					<li class="data"><span><?php echo $category->name?></span></li>
					<li class="delete_item"><span class="icon cross">&#160; &#160;</span> <a href="/get/edit_forum/delete/<?php echo $category->id?>" rel="<?php echo $category->id?>">delete</a></li>
				</ul>
			</li>		
		<?php endforeach;?>
	</ul>
</div>

<div class="save_pane fieldsets" style="display:none">
	<span class="icon cross floatright">&#160; &#160;</span>
	<form class="add_cat_form" action="/get/edit_forum/add/<?php echo $tool_id?>" method="post">
		<h2>New Category</h2>
		<b>Name</b>
		<br><input type="text" name="name" rel="text_req" class="send_input">
		<br><br>
		<b>Url</b>
		<br><input type="text" name="url" rel="text_req" class="auto_filename receive_input">
		<br><br>
		<button type="submit" class="jade_positive">Add Category</button>
	</form>
</div>

<script type="text/javascript">
// show new_cat
	$('#new_cat').click(function(){
		$(this).attr('disabled','disabled');
		$('.save_pane').clone().addClass('helper').show().prependTo('.common_main_panel');
		
	// add new category
		$('.add_cat_form').ajaxForm({
			beforeSubmit: function(fields, form){
				if(! $("input", form[0]).jade_validate() ) return false;
				$('div.save_pane.helper').html('Saving...');
			},
			success: function(data) {
				var data = data.split('|'); 
				var html = '<li id="item_'+ data[0] +'" class="root_entry"><ul class="row_wrapper"><li class="drag_box"><span class="icon move"> &#160; &#160; </span> DRAG </li><li class="position">0.</li><li class="data"><span>'+ data[1] +'</span></li><li class="delete_item"><span class="icon cross">&#160; &#160;</span> <a href="/get/edit_forum/delete/'+ data[0] +'" rel="'+ data[0] +'">delete</a></li></ul></li>';
				$('#generic_sortable_list').prepend(html);	
				$("div.save_pane.helper").remove();
				$(document).trigger('server_response.plusjade', data[2]);
			}	
		});	
		return false;
	});

	
	
// make sortable
	$("#generic_sortable_list").sortable({
		handle : ".drag_box",
		axis : "y",
		containment : '.common_main_panel'
	});
	
	<?php echo javascript::save_sort('forum')?>
	
	
</script>