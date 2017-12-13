<div class="site-top-bar" id="site-top-menu">
	<div class="container">
		<div class="site-top-menu-left">
			<?php do_action( 'yt_site_left_top_bar' );?>
			<?php echo sprintf('<div class="tel-numbers">%s</div>', yt_get_options( 'left_top_bar' ) );?>
		</div>

		<div class="site-top-menu-right">
			<?php do_action( 'yt_site_right_top_bar' );?>
			<?php
			if ( has_nav_menu( 'secondary' ) ) {
				echo wp_nav_menu(
					apply_filters( 'yt_site_top_menu_navigation_args', array( 
						'theme_location' => 'secondary' ,
						'echo' => false, 
						'container_class' => 'top-navigation hidden-xs hidden-sm',
						'menu_class'      => 'menu',
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						//'depth' => 1,
					))
				);
			}

			if( 'top_menu' === yt_get_options( 'header_social_links_position' ) ):
			
				
				echo yt_site_social_networks( array(
					'template' => '<div class="site-social-networks gray-2-secondary hidden-xs">%s</div>', 
					'show_title' => false,
					'echo' => true
				));
			endif?>

		</div>
	</div>
</div>