<?php
/**
 * The template for displaying search forms in yeahthemes
 *
 * @package yeahthemes
 */
?>

<?php yt_before_search_form();?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	
	<?php yt_search_form_start();?>
	
		<label class="screen-reader-text"><?php _ex( 'Search for:', 'label', 'yeahthemes' ); ?></label>
		<input type="search" class="search-field form-control" placeholder="<?php echo esc_attr_x( 'Text to search...', 'placeholder', 'yeahthemes' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label', 'yeahthemes' ); ?>">
	
	<?php 
	echo apply_filters( 'yt_site_search_submit_button', 
		sprintf ( '<button type="submit" class="search-submit btn btn-primary">%s <i class="fa fa-search"></i></button>', esc_attr_x( 'Search', 'submit button', 'yeahthemes' ) ) 
		);
	?>
	
	<?php yt_search_form_end();?>
</form>

<?php yt_after_search_form();?>