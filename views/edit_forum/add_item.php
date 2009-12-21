
<span class="on_close two">close-2</span>

<?php echo form::open_multipart("edit_showroom/add_item/$tool_id", array( 'class' => 'ajaxForm' ) )?>
	<input type="hidden" name="images" value="<?php echo $item->images?>">
	
	<div id="common_tool_header" class="buttons">
		<button type="submit" class="jade_positive" accesskey="enter">Add Item</button>
		<div id="common_title">Add New Showroom Item</div>
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
			<br><input type="text" name="name" rel="text_req" maxlength="50" style="width:275px">
			<br>
			<br><b>URL</b>
			<br><input type="text" name="url" rel="text_req" class="auto_filename" maxlength="50" style="width:275px">

			<br><br>
			<b>Category</b> <?php echo $category?>
			<input type="hidden" name="category_id" value="<?php echo $category?>">
		</div>

		<div id="images" class="toggle" style="display:none">	
			<div style="padding:10px; background:#ffffcc; margin-bottom:10px">
				<b>Item Image Gallery</b>
				- - <span class="icon images">&#160; &#160; </span> <a href="#" class="get_file_browser" rel="albums">Add more images</a>
				- - <span class="icon cross">&#160; &#160; </span> <a href="#" id="remove_images">Remove Selected</a>
			</div>
				
			<div id="sortable_images_wrapper"></div>
		</div>
		
		<div id="intro" class="toggle" style="display:none">
			<p><b>Short Introduction</b></p>
			<textarea name="intro" class="render_html"></textarea>	
		</div>

		<input type="hidden" name="body" value="offline">
		<!--
		<div id="desc">
			<p><b>Extended Description</b></p>
			 <textarea name="body" class="render_html"></textarea> 
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
	
	// sanitize the url relative to name given.
	$("input[name='name']").keyup(function(){
		input = $(this).val().replace(<?php echo valid::filter_js_url()?>, '-').toLowerCase();
		$("input[name='url']").val(input);
	});
	
// custom ajax form
	$(".custom_ajaxForm").ajaxForm({
		beforeSubmit: function(formData){
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