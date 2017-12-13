<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Theme functions
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeah Includes
 */

/**
 * Top bar
 *
 * @since 1.0.0
 */
add_action( 'yt_header_start', 'yt_site_top_bar', 5 );

if( !function_exists( 'yt_site_top_bar') ){

	function yt_site_top_bar(){

		if( 'hide' == yt_get_options( 'header_top_bar_menu' ) )
			return;

		get_template_part( 'includes/templates/top-bar' );
		
	}
}

/******************************************************
 * Ad Spaces
 ******************************************************/

/**
 * Before Header
 */

add_action( 'yt_header_start', 'yt_site_ads_before_header', 4 );

function yt_site_ads_before_header(){
	
	if( yt_get_options( 'site_ads_before_header' ) ):
		$output = sprintf('<div class="site-ads-before-header clearfix text-center">
			<div class="container clearfix">
			%s
			</div>
		</div>', yt_get_options( 'site_ads_before_header' ));

		echo do_shortcode( apply_filters( 'yt_site_ads_before_header', $output ) );
	endif;

}

/**
 * Foot Ads
 *
 * @since 1.0
 */
add_action( 'yt_footer_start','yt_site_foot_ads', 20);

if ( ! function_exists( 'yt_site_foot_ads' ) ) {
	
	function yt_site_foot_ads(){

		if( !empty( $GLOBALS['post'] )){
			$post = $GLOBALS['post'];
			$post_id = $post->ID;
		}else{
			$post_id = 0;
		}

		if( yt_get_options( 'site_ads_before_footer' ) ):
			$output = sprintf('<div class="site-ads-before-footer clearfix text-center">
				<div class="container site-head-ads text-center clearfix">
				%s
				</div>
			</div>', yt_get_options( 'site_ads_before_footer' ) );
			echo do_shortcode( apply_filters( 'yt_site_foot_ads', $output, $post_id ) );
		endif;
	}
}

/**
 * Inside Header
 */
//add_action( 'yt_inside_header', 'yt_site_ads_inside_header', 6 );

function yt_site_ads_inside_header(){

	
	$output = sprintf('<div class="site-ads-inside-header">%s</div>', yt_get_options( 'site_ads_inside_header' ));

	echo do_shortcode( apply_filters( 'yt_site_ads_inside_header', $output ) );
	

}
/**
 * After Header
 */
add_action( 'yt_header_end', 'yt_site_ads_after_header', 10 );

function yt_site_ads_after_header(){

	if( yt_get_options( 'site_ads_after_header' ) ):
		$output = sprintf('<div class="site-ads-after-header clearfix text-center">
			<div class="container clearfix">
			%s
			</div>
		</div>', yt_get_options( 'site_ads_after_header' ));

		echo do_shortcode( apply_filters( 'yt_site_ads_after_header', $output ) );
	endif;

}


add_action( 'yt_header_start', 'yt_site_start_header_banner', 5 );
/**
 * Header wrapper
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_start_header_banner' ) ) {
	
	function yt_site_start_header_banner(){
		// global $allowedtags;
		// print_r( $allowedtags );

		echo apply_filters( 'yt_site_start_header_banner',
		'<div class="site-banner" id="site-banner">
			<div class="container">
				' );
	}
}

add_action( 'yt_inside_header', 'yt_site_inside_header_banner', 5 );

function yt_site_inside_header_banner(){
	$header_style = yt_get_options('header_style');
	
	$array = array( 'yt_site_branding', 'yt_site_header_main_menu_nav' );
	if( 'blog_alt' == $header_style ){
		$array = array( 'yt_site_header_main_menu_nav', 'yt_site_branding' );
	}elseif( 'magazine' == yt_get_options( 'header_style' ) && yt_get_options( 'site_ads_inside_header' ) ){
		$array = array( 'yt_site_branding', 'yt_site_ads_inside_header', 'yt_site_header_main_menu_nav' );
	}

	foreach ( $array as $key => $func) {
		if( function_exists( $func ) )
			call_user_func( $func );
	}
}

add_action( 'yt_header_end', 'yt_site_end_header_banner', 5 );
/**
 * Header wrapper
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_end_header_banner' ) ) {
	
	function yt_site_end_header_banner(){
		echo apply_filters( 'yt_site_end_header_banner',
				'
			</div>
		</div>' );
	}
}


//add_action( 'yt_inside_header', 'yt_site_branding', 5 );
/**
 * Branding
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_branding' ) ) {
	
	function yt_site_branding(){
		
		
		get_template_part( 'includes/templates/site-branding' );
		
	}
}

//add_action( 'yt_inside_header', 'yt_site_header_main_menu_nav', 7 );
/**
 * Main Menu
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_header_main_menu_nav' ) ) {
	
	function yt_site_header_main_menu_nav(){
		$primary_menu = '';
		//Navnav
		if ( has_nav_menu( 'primary' ) ) {
			$primary_menu = wp_nav_menu(
				apply_filters( 'yt_site_primary_navigation_args', array( 
					'theme_location' => 'primary' , 
					'echo' => false, 
					'container_class' => 'site-navigation-menu-container hidden-xs hidden-sm',
					'menu_class'      => 'menu',
					'depth' => 5,
					'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'menu_id'         => 'site-navigation-menu-container',
				))
			);
		}else{
			$primary_menu = '<ul class="menu"><li>' . 
				sprintf( 
					esc_html__('Create a Menu in %s and assign it as Main Menu in %s', 'yeahthemes'),
					sprintf( '<a href="%s">%s</a>',
						esc_url( admin_url('nav-menus.php') ),
						esc_html__( 'Menus', 'yeahthemes' )
					),
					sprintf( '<a href="%s">%s</a>',
						esc_url( admin_url('nav-menus.php?action=locations') ),
						esc_html__( 'Theme Location', 'yeathemes' )
					)
				) . '</li></ul>';
		}
		
		$site_navigaton = '<nav id="site-navigation" class="main-navigation" role="navigation">
			<div class="sr-only skip-link"><a href="#content" title="' . esc_attr( 'Skip to content', 'yeahthemes' ) . '">' . __( 'Skip to content', 'yeahthemes' ) . '</a></div>
			' . $primary_menu . '
			<a href="javascript:void(0)" class="main-menu-toggle hidden-md hidden-lg">
				<span class="bar1"></span>
				<span class="bar2"></span>
				<span class="bar3"></span>
			</a>
		</nav><!-- #site-navigation -->';
		
		//Allow editing site
		echo apply_filters( 'yt_site_primary_menu', $site_navigaton );

		?><?php
	}
}

add_filter( 'wp_nav_menu_items', 'yt_site_custom_menu_items', 20, 2 );
/**
 * Custom Menu items
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_custom_menu_items' ) ) {
	function yt_site_custom_menu_items ( $items, $args ) {
		
		/*$items = '<li class="menu-item menu-item-type-custom menu-item-social-networks mega-menu-parent">
			<a href="#"><i class="fa fa-reorder"></i> Menu</a>
			<div class="mega-menu-content-wrapper full-width-wrapper">
				<div class="container">
					<ul class="sub-menu">
						<li id="menu-item-517" class="menu-item"><a href="http://192.168.1.15/framework/category/uncategorized/">Uncategorized</a></li>
						<li id="menu-item-518" class="menu-item"><a href="http://192.168.1.15/framework/category/audio/">Audio</a></li>
						<li id="menu-item-519" class="menu-item"><a href="http://192.168.1.15/framework/category/link/">Link</a></li>
						<li id="menu-item-520" class="menu-item"><a href="http://192.168.1.15/framework/category/link/video/">Video</a></li>
						<li id="menu-item-521" class="menu-item"><a href="http://192.168.1.15/framework/category/link/video/hello-x/">Hello</a></li>
						<li id="menu-item-522" class="menu-item"><a href="">Image</a></li>
					</ul>
				</div>
			</div>
		</li>';*/
		if( $args->theme_location == 'primary' ){

			if( 'main_menu' === yt_get_options( 'header_social_links_position' ) ):

				$items .= yt_site_social_networks( array(
					'template' => '<li class="menu-item-type-custom menu-item-social-networks secondary-2-primary site-social-networks">%s</li>', 
					'title' => false
				) );

			endif;

			if( 'hide' !== yt_get_options( 'header_search' ) ):
				$items .= '<li class="menu-item menu-item-type-custom menu-item-gsearch" data-default="fa fa-search" data-close="fa fa-close">';
					$items .= '<a href="javascript:void(0);"><i class="fa fa-search"></i></a>';
					$items .= sprintf( '<!-- noptimize --><script type="text/html" data-role=".menu-item-gsearch">/* <![CDATA[ */%s/* ]]> */</script><!-- /noptimize -->', get_search_form( $echo = false ) );

				$items .= '</li>';
			endif;
		}
   
   		return $items;
   	}
}








	
add_action( 'yt_main_start', 'yt_site_start_single_row', 3);
/*
 * Start primary wrapper
 *
 * @since 1.0
 */

if ( ! function_exists( 'yt_site_start_single_row' ) ) {
	 
	function yt_site_start_single_row(){
		echo apply_filters( 'yt_site_start_single_row', '<div class="container">' );
	}
}

add_action( 'yt_main_end', 'yt_site_end_single_row', 150);
/*
 * End primary wrapper
 *
 * @since 1.0
 */

if ( ! function_exists( 'yt_site_end_single_row' ) ) {
	 
	function yt_site_end_single_row(){
		
		echo apply_filters( 'yt_site_end_single_row', '</div>' );
	}
}

//add_action( 'yt_after_primary', 'yt_site_secondary_content', 10 );
/**
 * Secondary content
 *
 * @since 1.0
 */

if ( ! function_exists( 'yt_site_secondary_content' ) ) {
	 
	function yt_site_secondary_content(){
		$layout = yt_site_get_current_page_layout();

		// $archive_layout = yt_get_options( 'archive_layout' );

		// if( 'default' !== $archive_layout && is_archive() && !yt_is_woocommerce() )
		// 	$layout = $archive_layout;

		$condition = !is_page_template( 'template-fullwidth.php' ) && 'fullwidth' !== $layout;

		if( apply_filters( 'yt_site_secondary_content_condition', $condition ) )
			get_sidebar();
		
	}
}


add_action( 'yt_primary_start', 'yt_site_archive_header', 5 );
/**
 * Archive header
 *
 * @since 1.0
 */

if ( ! function_exists( 'yt_site_archive_header' ) ) {
	
	function yt_site_archive_header(){
	
		$condition = !is_archive() || yt_is_woocommerce();

		if( apply_filters( 'yt_site_archive_header_condition', $condition ) )
			return;

		get_template_part( 'includes/templates/archive-title' );

	}
}

add_action( 'yt_theme_post_meta_description', 'yt_theme_post_meta_description' );

if ( ! function_exists( 'yt_theme_post_meta_description' ) ) {

	function yt_theme_post_meta_description(){

		get_template_part( 'includes/templates/post-meta-info' );
	}

}

add_filter( 'yt_post_atts', 'yt_site_article_atts' );

if ( ! function_exists( 'yt_site_article_atts' ) ) {

	function yt_site_article_atts( $atts ){
		
		$atts['data-url'] = esc_url( apply_filters( 'yt_site_sharing_buttons_link', get_permalink() ) );
		$atts['data-title'] = esc_attr( get_the_title() );
		$atts['data-source'] = esc_url( home_url('/') );
		$thumb = wp_get_attachment_url( get_post_thumbnail_id( ) );	

		if( $thumb )
			$atts['data-media'] = $thumb;
		$meta_info = yt_get_options('blog_post_meta_info');
		if( in_array( 'category', $meta_info ))
			$atts['data-cat'] = esc_attr( sprintf('<div class="byline alt-font gray-2-secondary">%s</div>', get_the_category_list( __( ', ', 'yeahthemes' ) ) ) );
		
		return $atts;

	}

}


add_action( 'init', 'yt_setup_site_featured_articles', 30 );

if ( ! function_exists( 'yt_setup_site_featured_articles' ) ) {

	function yt_setup_site_featured_articles(){

		if( !yt_get_options( 'site_hero_banner_mode') )
			return;

		$layout = yt_get_options('site_featured_articles_layout');
		$settings = yt_get_options( 'site_featured_articles_args' );
		$layout = $layout ? $layout : 'default';
		$settings['layout_type'] = $layout;

		YT_Site_Featured_Articles::setup( $settings );

	}

}



add_action( 'yt_after_header', 'yt_site_featured_posts' );

if ( ! function_exists( 'yt_site_featured_posts' ) ) {
	
	function yt_site_featured_posts( ){

		$condition = is_front_page() || is_home();

		if( apply_filters( 'yt_site_featured_posts_condition', $condition ) ){
			
		}else{
			return;
		}

		if( !yt_get_options( 'site_hero_banner_mode') )
			return;
		
		global $post;

		$temp_post = $post;
		$featured_posts = YT_Site_Featured_Articles::get_featured_posts();

		if( empty( $featured_posts ) )

			return;

		$settings = yt_get_options('site_featured_articles_args');
		$hero_layout = yt_get_options('site_featured_articles_layout');

		$wrapper_class = 'site-hero featured-content featured-posts';
		$wrapper_class .= ' layout-' . sanitize_title( $hero_layout );
		$template_part = 'default' !== $hero_layout ? "-{$hero_layout}" : '';
		$count = 0;

		$wrapper_start = '<div class="site-hero-inner-wrapper">';
		$wrapper_end = '</div>';
		$inner_wrapper_start = $inner_wrapper_end = $item_start = $item_end = '';

		// Layout Default
		if( 'default' == $hero_layout ){
			$wrapper_start = '<div class="container">';
			$wrapper_end = '</div>';
		// Layout Carousel
		}elseif( 'static-alt' == $hero_layout ){

			$wrapper_start = '<div class="site-hero-inner-wrapper">';
			$wrapper_end = '</div>';
		// Layout Carousel
		}elseif( 'carousel' == $hero_layout ){

			$autoplay = yt_get_options('site_hero_banner_autoplay_mode');

			$slider_settings = apply_filters( 'yt_site_hero_banner_carousel_settings', array(
				'directionNav' 	=> true,
				'controlNav' 	=> true,
				'pausePlay' 	=> false,
				'animation' 	=> 'slide',
				'slideshow' 	=> $autoplay ? true : false,
				'css3Effect' 	=> '',
				'smoothHeight'	=> true,
				'animationLoop' => false,
				'animationSpeed'=> 600,
				'animationLoop'	=> true
			), 'hero_banner');

			$data_settings = json_encode( $slider_settings );
			$wrapper_start = '<div class="site-hero-inner-wrapper">';
			$wrapper_end = '</div>';
			$inner_wrapper_start = '<div class="yeahslider yeahslider-gallery" data-settings="' . esc_attr( $data_settings ) . '" data-init="onload"><ul class="slides">';
			$inner_wrapper_end = '</ul></div>';
			$item_start = '<li>';
			$item_end = '</li>';

		// Layout blocks
		}elseif( 'blocks' == $hero_layout ){
			$wrapper_start = '<div class="row">';
			$wrapper_end = '</div>';
		
		// Blocks alt	
		}elseif( 'blocks-alt' == $hero_layout ){
			$wrapper_start = '<div class="container"><div class="site-hero-inner-wrapper">';
			$wrapper_end = '</div></div>';			
		}


		if( yt_get_options( 'site_hero_banner_shuffle_mode') )
			shuffle( $featured_posts );

		echo '<!-- .site-hero --><div id="site-hero" class="' . esc_attr( $wrapper_class ) . '">';

		do_action( 'yt_site_featured_posts_content_start', $hero_layout );

			echo !empty( $wrapper_start ) ? $wrapper_start : '';

			do_action( 'yt_site_featured_posts_before', $hero_layout );

				echo !empty( $inner_wrapper_start ) ? $inner_wrapper_start : '';

				//print_r( $featured_posts );
				foreach ( (array) $featured_posts as $order => $post ) :

					setup_postdata( $post );

					$count++;
					
					echo !empty( $item_start ) ? $item_start : '';
					// Include the featured content template.
					get_template_part( "content-featured-post{$template_part}", get_post_format() );

					echo !empty( $item_end ) ? $item_end : '';
					/**
					 * Break the loop
					 */
					if( 'default' == $hero_layout && 1 == $count )
						break;
					elseif( 'static-alt' == $hero_layout && 2 == $count )
						break;
					elseif( 'blocks' == $hero_layout && 5 == $count )
						break;
					elseif( 'blocks-alt' == $hero_layout && 3 == $count )
						break;
					
				endforeach;

				echo !empty( $inner_wrapper_end ) ? $inner_wrapper_end : '';

			do_action( 'yt_site_featured_posts_after', $hero_layout );

			echo !empty( $wrapper_end ) ? $wrapper_end : '';

		do_action( 'yt_site_featured_posts_content_end', $hero_layout );

		echo '</div><!--/ .site-hero -->';

		wp_reset_postdata();

		// Restore global post var from backup;
		$post = $temp_post;
	}
	
}



/*---------------------------------------------------------------------------------------------------------*
 * Single post
 *---------------------------------------------------------------------------------------------------------*/
/**
 *
 * -Author 
 * -Post nav
 * -You Might Also Like
 * -Comment
 */

add_action( 'yt_single_post_entry_footer_start', 'yt_site_single_post_sharing_button' );

if ( ! function_exists( 'yt_site_single_post_sharing_button' ) ) {

	function yt_site_single_post_sharing_button(){

		if( 'hide' == yt_get_options( 'blog_single_post_share_buttons' ) )
			return;

		$args = array();
			$args['settings'] = array(
			'style' => 'default',
			'size'	=> 'large',
			'counter' => false
		);

		$args['services'] = array(

			'facebook' => array(
				'icon' => 'fa fa-facebook',
				'title' => __('Share on Facebook', 'yeahthemes'),
				'show' => true,
				'label' => true,
				'via' => '',
			),
			'twitter' => array(
				'icon' => 'fa fa-twitter',
				'title' => __('Share on Twitter', 'yeahthemes'),
				'show' => true,
				'label' => true,
				'via' => ''
			),
			'pinterest' => array(
				'icon' => 'fa fa-pinterest',
				'title' => __('Pin this Post', 'yeahthemes'),
				'show' => true,
				'label' => true,
				'via' => '',
			),
			'email' => array(
				'icon' => 'fa fa-inbox',
				'title' => __('Share via Email', 'yeahthemes'),
				'show' => true,
				'label' => false,
				'via' => '',
			)/*,
			'linkedin' => array(
				'icon' => 'fa fa-linkedin',
				'title' => __('Share on Linkedin', 'yeahthemes'),
				'show' => false,
				'label' => false,
				'via' => '',
			),
			'tumblr' => array(
				'icon' => 'fa fa-tumblr',
				'title' => __('Share on Tumblr', 'yeahthemes'),
				'show' => true,
				'label' => false,
				'via' => '',
			),
			'more' => array(
				'icon' => 'fa fa-share-alt',
				'title' => __('More services', 'yeahthemes'),
				'show' => true,
				'label' => false,
				'via' => ''
			)*/
		);
		$args = apply_filters( 'yt_site_single_footer_social_sharing_buttons_args', $args  );
		echo '<div class="single-sharing-btns">';
		echo sprintf( '<h3 class="single-sharing-btns-title">%s</h3>', __( 'Share This Story', 'yeahthemes' ) );
		yt_site_social_sharing_buttons( $args['settings'], $args['services'], $class = '', $wrapper = 'ul', $echo = true);
		echo '</div>';
	}

}

/**
 * Post author
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_single_post_author' ) ) {

	function yt_site_single_post_author(){

		if( !is_singular()  )
			return;

		do_action( 'yt_site_single_post_author_before' );

		get_template_part( 'includes/templates/single-author-meta' );	

		do_action( 'yt_site_single_post_author_after' );
		
	}

}

/**
 * Post nav
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_single_post_dir_nav' ) ) {
	function yt_site_single_post_dir_nav(){

		do_action( 'yt_site_single_post_dir_nav_before' );
		
		yt_direction_nav( 'nav-below', 'secondary-2-primary' );

		do_action( 'yt_site_single_post_dir_nav_before' );
	}
}

add_action( 'yt_single_post_entry_content_start', 'yt_site_single_post_related_articles' );

if ( ! function_exists( 'yt_site_single_post_related_articles' ) ) {
	 
	function yt_site_single_post_related_articles(){

		if( !is_single() )
			return;

		if( 'hide' == yt_get_options( 'blog_single_post_related_articles') )
			return;

		get_template_part( 'includes/templates/single-related-articles' );
	}
}


/**
 * You Might Also Like
 *
 * @since 1.0
 */

if ( ! function_exists( 'yt_site_single_post_you_might_also_like' ) ) {
	 
	function yt_site_single_post_you_might_also_like(){

		if( !is_single() )

			return;
		
		get_template_part( 'includes/templates/single-ymal' );

		
	}
}

/**
 * Post comment
 *
 * @since 1.0
 */	

if ( ! function_exists( 'yt_site_single_post_comment' ) ) {
	 
	function yt_site_single_post_comment(){

		do_action( 'yt_site_single_post_comment_before' );
		// If comments are open or we have at least one comment, load up the comment template
		if ( comments_open() || '0' != get_comments_number() )
			comments_template();

		do_action( 'yt_site_single_post_comment_before' );
	}
}

add_action( 'yt_after_loop_singular_post','yt_site_blocks_after_single_post', 5);

if ( ! function_exists( 'yt_site_blocks_after_single_post' ) ) {

	function yt_site_blocks_after_single_post(){

		$option = yt_get_options('blog_single_stuff');

		if( !empty( $option['enabled'] ) ){
			foreach ( (array) $option['enabled'] as $key => $value) {
				switch ( $value ) {
					case 'author_meta':
						if( function_exists( 'yt_site_single_post_author' ))
							yt_site_single_post_author();
					break;

					case 'post_nav':
						if( function_exists( 'yt_site_single_post_dir_nav' ))
							yt_site_single_post_dir_nav();
					break;

					case 'ymal':
						if( function_exists( 'yt_site_single_post_you_might_also_like' ))
							yt_site_single_post_you_might_also_like();
					break;

					case 'comments':
						if( function_exists( 'yt_site_single_post_comment' ))
							yt_site_single_post_comment();
					break;
					
					default:
						do_action( 'yt_site_blocks_after_single_post', $value );
					break;
				}
			}
		}
		
	}

}
add_filter( 'the_content', 'yt_site_ads_before_single_post', 15, 1 );
add_filter( 'the_content', 'yt_site_ads_between_single_post', 10, 1 );
add_filter( 'the_content', 'yt_site_ads_after_single_post', 20 );
/**
 * Single post ads
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'yt_site_ads_before_single_post' ) ) {
	function yt_site_ads_before_single_post( $content){

		$condition = is_singular( 'post' );
		if( !apply_filters( 'yt_site_ads_before_single_post_condition', $condition ) )
			return $content;

		if( !empty( $GLOBALS['post'] )){
			$post = $GLOBALS['post'];
			$post_id = $post->ID;
		}else{
			$post_id = 0;
		}
		$ads = yt_get_options( 'site_ads_before_single_post' );
		$output = '';
		if( $ads ):
			$output = '<p class="entry-ad hidden-print single-post-ads-top text-center">';
			$output .= stripslashes( $ads );
			$output .= '</p>';
			$output = do_shortcode( apply_filters( 'yt_site_ads_before_single_post', $output, $post_id ) );
		endif;

		return $output . $content;
		
	}
}
/**
 * Single post ads
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'yt_site_ads_between_single_post' ) ) {
	function yt_site_ads_between_single_post( $content ){

		$condition = is_singular( 'post' );
		if( !apply_filters( 'yt_site_ads_between_single_post_condition', $condition ) )
			return $content;

		if( !empty( $GLOBALS['post'] )){
			$post = $GLOBALS['post'];
			$post_id = $post->ID;
		}else{
			$post_id = 0;
		}

		$ads = yt_get_options( 'site_ads_between_single_post' );
		if( empty( $ads ))
			return $content;

		$ads = '<p class="entry-ad clear hidden-print single-post-ads-between center-block text-center">' . stripslashes( $ads ) . '</p>';
		$ads = do_shortcode( apply_filters( 'yt_site_ads_between_single_post', $ads, $post_id ) );

		$delimeter = '</p>';
		$linebreaks = substr_count( $content, $delimeter );
		
		if( $linebreaks < apply_filters( 'yt_site_ads_between_single_post_paragraph_count', 3 ) )
			return $content;

		$insert_after = intval( $linebreaks/2 ) + 1;

		$paragraphs = explode( $delimeter, $content );

		foreach ($paragraphs as $key => $paragraph) {
			if ( trim( $paragraph ) ) {
				$paragraphs[$key] .= $delimeter;
			}
			if ( $insert_after == $key + 1 ) {
	            $paragraphs[$key] .= $ads;
	        }
		}		

		return implode( '', $paragraphs );
	}
}
/**
 * Single post ads
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'yt_site_ads_after_single_post' ) ) {
	function yt_site_ads_after_single_post( $content ){

		$condition = is_singular( 'post' );
		if( !apply_filters( 'yt_site_ads_before_single_post_condition', $condition ) )
			return $content;

		if( !empty( $GLOBALS['post'] )){
			$post = $GLOBALS['post'];
			$post_id = $post->ID;
		}else{
			$post_id = 0;
		}

		$ads = yt_get_options( 'site_ads_after_single_post' );
		$output = '';
		if( $ads ):
			$output = '<p class="entry-ad clear hidden-print single-post-ads-bottom text-center">';
			$output .= stripslashes( $ads );
			$output .= '</p>';
			$output = do_shortcode( apply_filters( 'yt_site_ads_after_single_post', $output, $post_id ) );
		endif;

		return $content . $output;
		

	}
}

add_filter( 'yt_icon_next_post', 'yt_site_icon_next_post');

if( !function_exists( 'yt_site_icon_next_post') ) {
	
	function yt_site_icon_next_post(){
		return '<span class="post-nav-icon"><i class="fa fa-chevron-right"></i></span>';
	}
}

add_filter( 'yt_icon_prev_post', 'yt_site_icon_prev_post');

if( !function_exists( 'yt_site_icon_prev_post') ) {
	
	function yt_site_icon_prev_post(){
		return '<span class="post-nav-icon"><i class="fa fa-chevron-left"></i></span>';
	}
}
/*---------------------------------------------------------------------------------------------------------*
 * Theme Footer
 *---------------------------------------------------------------------------------------------------------*/


add_action( 'yt_inside_footer', 'yt_site_footer', 10);

if ( ! function_exists( 'yt_site_footer' ) ) {
	 
	function yt_site_footer(){

		// echo '<div class="footer-instagram-feed">';
		// 	//Echo and get the user info
		// 	yt_instagram_feed( '12488328', 16, true);

		// echo '</div>';

		if( 'hide' !== yt_get_options('footer_instagram') )
			get_template_part( 'includes/templates/footer-instagram-feed' );

		$footer_layout = yt_get_options('footer_layout');

		do_action( 'yt_before_site_footer_widgets' );

		if( 'footer-extra' !== $footer_layout && apply_filters( 'yt_site_footer_widgets', true  ) ){
		
	 		get_template_part( 'includes/templates/footer-widgets' );

		}

		do_action( 'yt_after_site_footer_widgets' );

		do_action( 'yt_before_site_footer_info' );

		if( 'footer-widget' !== $footer_layout && ( yt_get_options('footer_text_left') 
			|| yt_get_options('footer_text_right'))  && apply_filters( 'yt_site_footer_info', true  ) ){
			
			get_template_part( 'includes/templates/footer-credits' );		
		
		}

		do_action( 'yt_after_site_footer_info' );
	}
}
/*---------------------------------------------------------------------------------------------------------*
 * Theme Layout class
 *---------------------------------------------------------------------------------------------------------*/
add_filter( 'yt_section_class', 'yt_site_section_class', 10, 2 );

/*
 * Primary Class
 *
 * @since 1.0.1.8
 */
function yt_site_section_class( $classes, $position ){

	if( 'primary' == $position )
		$classes[] = 'printable-section';
	elseif( 'secondary' == $position )
		$classes[] = 'hidden-print';

	return apply_filters( 'yt_site_section_class', $classes, $position );
}

add_filter( 'yt_site_get_current_page_layout', 'yt_filter_site_current_layout' );
/*
 * Custom layout for Archive
 *
 * @since 1.0
 */
function yt_filter_site_current_layout( $layout ){

		if( is_archive() && !yt_is_woocommerce() ){
			$archive_layout = yt_get_options( 'archive_layout' );
			
			if( 'default' !== $archive_layout )
				$layout = $archive_layout;
		}

		if( is_singular( 'post' )){
			$single_post_layout = yt_get_options( 'single_post_layout' );
			
			if( 'default' !== $single_post_layout )
				$layout = $single_post_layout;
		}

		return $layout;
}

add_filter( 'yt_header_class','yt_site_header_footer_class');
add_filter( 'yt_footer_class','yt_site_header_footer_class');
/*
 * Header Class
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_site_header_footer_class' ) ) {
	 
	function yt_site_header_footer_class($_classes){

		$classes = array();
		
		$classes[] = 'full-width-wrapper';
		$classes[] = 'hidden-print';
		
		$classes = apply_filters( 'yt_site_header_classes', $classes );
		
		$_classes = array_merge( $_classes, $classes );

		return $_classes;
	}
}

add_filter( 'yt_site_get_current_page_layout', create_function( '$layout' , 'if( is_404() ) return "fullwidth"; return $layout;') );