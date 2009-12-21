
<span class="on_close two">close-2</span>

<div  id="common_tool_header" class="buttons">
	<div id="common_title">Items in Category: </div>
</div>	

<div id="load_box" style="height:400px; overflow:auto">
	<ul id="generic_sortable_list" class="ui-tabs-nav">
		<?php
		$counter = 1;	
		foreach($items as $item)
		{
			$class = '';
			# if($item['enable'] == 'no') $class = 'class="not_enabled"';
			?>
			<li id="showroom_<?php echo $item->id?>" <?php echo $class?>>
				<table><tr>
					<td width="60px" class="drag_box"><span class="icon move">&#160; &#160; </span> DRAG</td>
					<td width="30px" class="aligncenter"><?php echo $item->position?>. </td>
					<td class="page_edit"><a href="/get/edit_showroom/edit/<?php echo $item->id?>" rel="facebox" class="secondary" id="<?php echo $item->id?>"><?php echo $item->name?></a></td>
					<td width="60px" class="alignright"><a href="/get/edit_showroom/delete/<?php echo "$tool_id/$item->id"?>" class="delete_showroom_item" id="<?php echo $item->id?>">Delete</a></td>
				</tr></table>
			</li>		
			<?php
			++$counter;
		}
		?>		
	</ul>	
</div>

<script type="text/javascript">
	$('#generic_sortable_list').sortable({ 
		handle	: '.drag_box',
		axis	: 'y',
		containment: '.facebox'
	});	
	// delete an item
	$('a.delete_showroom_item').click(function(){
		if(confirm('This cannot be undone. Delete this showroom item?')) {
			$.get($(this).attr('href'), function(data){
				var id = $(this).attr('id');
				$('#generic_sortable_list li#showroom_'+ id).remove();
				$('#show_response_beta').html(data);
			});
		}
		return false;
	});
	// should have javascript::save_sort() here...
</script>