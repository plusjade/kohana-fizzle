<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * forum helper
 * render some handy forum html.
 */
 
class forum_Core {
	
/*
 * centralize the timeago html
 */	
	public static function timeago($date)
	{
		ob_start();
		?>
		<abbr class="timeago" title="<?php echo date("c", $date)?>"><?php echo date("M d y @ g:i a", $date)?></abbr>
		<?php
		return ob_get_clean();	
	}
	
} // end forum helper