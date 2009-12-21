
<span class="on_close"><?php echo $js_rel_command?></span>
<?php
$views = array( 'list', 'gallery');
echo form::open("edit_showroom/settings/$tool->id", array('class' => 'ajaxForm'));
?>
	<div  id="common_tool_header" class="buttons">
		<button type="submit" name="edit_showroom" class="jade_positive">Save Settings</button>
		<div id="common_title">Showroom Settings.</div>
	</div>	
			
	<div class="common_left_panel">

	</div>
	
	<div class="common_main_panel fieldsets">
		<b>showroom Name</b>
		<br><input type="text" name="name" value="<?php echo $tool->name?>">
	
		<br><br>
		<b>showroom View Style</b>
		<br><select name="view">
			<?php 
				foreach($views as $view)
				{
					if($view == $tool->view)
						echo '<option SELECTED>'.$view.'</option>';
					else
						echo '<option>'.$view.'</option>';
				}
			?>
		</select>
		
		<br><br>
		
		<b>View Params</b>
		<br><input type="text" name="params" value="<?php echo $tool->params?>">
	
	</div>
		
</form>
