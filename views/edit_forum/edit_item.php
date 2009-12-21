
<span class="on_close two">close-2</span>

<?php echo form::open_multipart("edit_showroom/edit/$item->id", array('class' => 'custom_ajaxForm'))?>
	<input type="hidden" name="images" value="<?php echo $item->images?>">
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive" accesskey="enter"> Save Changes</button>
		<div id="common_title">Edit Showroom item</div>
	</div>	
	
	<div class="common_left_panel">	
		<ul id="showroom_toggle" class="ui-tabs-nav">
			<li><a href="#" rel="params" class="selected"><b>Attributes</b></span></a><li>
			<li><a href="#" rel="images"><b>Images</b></span></a><li>
			<li><a href="#" rel="intro"><b>Introduction</b></span></a><li>
			<li><a href="#" rel="desc"><b>Main Description</b></span></a><li>
		</ul>
	</div>

	<div class="common_main_panel">
	
		<div id="params" class="toggle fieldsets">
			<b>Item Name</b>
			<br><input type="text" name="name" value="<?php echo $item->name?>" rel="text_req" maxlength="50" style="width:275px">
			<br>
			<br><b>URL</b>
			<br><input type="text" name="url" value="<?php echo $item->url?>" class="auto_filename" rel="text_req" maxlength="50" style="width:275px">
			
			<br><br>
			<b>Category</b>
			 <select name="category">
				<?php 
				foreach($categories as $category)
					if ($item->cat_id == $category->id)
						echo '<option value="'.$category->id.'" selected="selected">'.$category->name.'</option>'."\n";
					else
						echo '<option value="'.$category->id.'">'.$category->name.'</option>'."\n";
				?>
			</select>
		</div>

		<div id="images" class="toggle" style="display:none">	
			<div style="padding:10px; background:#ffffcc; margin-bottom:10px">
				<b>Item Image Gallery</b>
				- - <span class="icon images">&#160; &#160; </span> <a href="#" class="get_file_browser" rel="albums">Add more images</a>
				- - <span class="icon cross">&#160; &#160; </span> <a href="#" id="remove_images">Remove Selected</a>
			</div>
				
			<div id="sortable_images_wrapper">
				<?php
				$img_path = Assets::assets_url();
				foreach($images as $data)
				{
					$data = explode('|', $data);
					?>
					<div>
						<span>drag</span>
						<img src="<?php echo "$img_path/$data[0]"?>" title="<?php echo $data[1]?>" alt="<?php echo $data[1]?>">
					</div>
					<?php 
				}
				?>
			</div>
		</div>
		
		<div id="intro" class="toggle" style="display:none">
			<b>Short Introduction</b>
			<br><textarea name="intro" class="render_html"><?php echo $item->intro?></textarea>
		</div>
		
		<input type="hidden" name="body" value="offline">
		<input type="hidden" name="old_category" value="<?php echo $item->cat_id?>">	
	
		<!-- 
		<div id="desc" class="toggle" style="display:none">
			<p><b>Extended Description</b></p>
			<textarea name="body" class="render_html"><?php echo $item->body?></textarea>
		</div>	
		-->
	</div>
</form>

<script type="text/javascript">

// make images sortable and selectable
	$("#sortable_images_wrapper").sortable({items:'div', handle:'span'});
	$("#sortable_images_wrapper").selectable({filter:'img'});


// toggle panes
	$('.common_left_panel li a').click(function(){
		$('.common_left_panel li a').removeClass('selected');
		var div = $(this).addClass('selected').attr('rel');
		$('.common_main_panel div.toggle').hide();
		$('.common_main_panel div#'+ div).toggle('fast');
		return false;
	});
	
// custom ajax form
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(formData){
			$('.facebox .show_submit').show();
			var output = '';
			$('#sortable_images_wrapper img').each(function(i){
				output += $(this).attr('alt') + '|';
			});
			formData[0].value = output; // first input has to be images.
		},
		success: function(data) {
			$.facebox.close();
			$('#show_response_beta').html(data);					
		}
	});
	
</script>
