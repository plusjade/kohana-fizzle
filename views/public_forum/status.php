
<?php
	$message = (isset($message)) ? $message : 'Your post has been submitted successfuly.';
?>

<?php if($success):?>
	<div class="jade_form_status_box box_positive">
		<?php echo $message?>
		<br/>Thank you! =)
	</div>
<?php else:?>
	<div class="jade_form_status_box box_negative">
		There was a problem submitting your post. =( 
		<br/>Please try again later. Sorry!
	</div>
<?php endif;?>