<?php
/**
 * The forum Sidebar containing the extra widget areas.
 *
 * @package yeahthemes
 */
?>
	
	<?php yt_before_secondary(); ?>
	
	<div id="secondary" <?php yt_section_classes( 'widget-area', 'secondary' );?> role="complementary">
	
		<?php yt_secondary_start(); ?>
		
		<?php

			dynamic_sidebar( 'forum' );

		?>
		
		<?php yt_secondary_end(); ?>
		
	</div><!-- #secondary -->
	
	<?php yt_after_secondary(); ?>
