
<form id="forum_submit_item" action="<?php echo url::site("$this->page_name/submit")?>" method="POST" class="fieldsets">
	
	<h3>Submit a New Post</h3>

	<?php if(isset($errors)) echo val_form::show_error_box($errors);?>
	
	<fieldset>
		<label>Category</label>
		<select name="forum_cat_id">
			<?php
			foreach($categories as $cat)
				echo "<option value=\"$cat->id\" rel=\"$cat->url\">$cat->name</option>";
			?>
		</select>
	</fieldset>	

	<?php
	$fields = array(
		#'category'	=> array('Category','select','text_req'),
		'title'	=> array('Title','input','text_req','',''),
		'body'	=> array('Post','textarea','text_req','','')
	);
	if(!isset($values)) $values = array();
	if(!isset($errors)) $errors = array();
	?>
	<?php echo val_form::generate_fields($fields, $values, $errors);?>
	
	<button type="submit">Submit</button>
</form>


<script type="text/javascript">
$('#forum_submit_item').ajaxForm({
	//target: "#contact_wrapper_%VAR% #newsletter_form",
	beforeSubmit: function(fields, form) {
		if(!$("input, textarea", form[0]).jade_validate() ) return false;
	},
	success: function(data) {
		// todo: output a success message man.
		var category = $('select[name="forum_cat_id"] > option:selected').attr('rel');
		$('#forum_content_wrapper')
		.html('<div class="ajax_loading">loading...</div>')
		.load('<?php echo url::site("$this->page_name/category")?>/'+ category);
	}
});

</script>




