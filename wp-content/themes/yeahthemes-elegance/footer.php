<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package yeahthemes
 */
?>

		<?php yt_main_end(); ?>
	</div><!-- #main -->
	
	<?php yt_before_footer();?>
	
	<footer id="colophon" <?php yt_section_classes( 'site-footer', 'footer' );?> role="contentinfo">
		
		<?php 
			
			yt_footer_start(); 
			
			yt_inside_footer(); 
			
			yt_footer_end();
		?>
		
	</footer><!--/#colophon-->
	
	<?php yt_after_footer();?>

</div><!--/.inner-wrapper-->
	
	<?php yt_wrapper_end(); ?>

</div><!--/#page-->

<?php yt_after_wrapper(); ?>

<?php wp_footer(); ?>

</body>

</html>