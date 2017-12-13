<div class="site-info footer-info full-width-wrapper">
	<?php do_action( 'yt_footer_info_start' );?>
	<div class="container">
		<?php 
		if( yt_get_options('footer_text_left') ){
			echo sprintf( '<div class="left-footer-info">%s</div>', wp_kses_post( yt_get_options('footer_text_left') ) );
		}
		if( yt_get_options('footer_text_right') ){
			echo sprintf( '<div class="right-footer-info">%s</div>', wp_kses_post( yt_get_options('footer_text_right') ) );
		}
		
		?>
	</div>
	<?php do_action( 'yt_footer_info_end' );?>
</div><!-- .site-info -->