<?php

// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;
//add_filter( 'bp_core_fetch_avatar_no_grav', '__return_true' );
if( class_exists('bbPress')):

/**
 * Remove stylesheet for BBP_Private_Replies plugin
 */
global $bbp_private_replies;

if( is_a( $bbp_private_replies, 'BBP_Private_Replies' ))
	remove_action( 'wp_enqueue_scripts', array( $bbp_private_replies, 'register_plugin_styles' ) );
//add_filter ('bbp_no_breadcrumb', '__return_true');

add_action( 'after_setup_theme', 'yt_bbpress_after_setup_theme' );
/**
 * bbPress after theme setup
 */
function yt_bbpress_after_setup_theme(){
	//forum
	add_post_type_support( 'forum', 'thumbnail' );
	//add_post_type_support( 'topic', 'thumbnail' );

}

add_filter( 'yt_site_primary_classes', 'yt_bbpress_site_primary_class' );
/**
 * yt_site_primary_classes
 */
function yt_bbpress_site_primary_class( $classes ){

	if( yt_is_bbpress() ){	
		$classes = array();	
		
		$layout = yt_get_options( 'bbp_layout' );

		if( 'default' == $layout ){
			$classes[] = 'col-sm-12';
			$classes[] = 'col-md-8';
		}elseif( 'right-sidebar' == $layout ){
			$classes[] = 'col-sm-12';
			$classes[] = 'col-md-8';
		}elseif( 'left-sidebar' == $layout ){
			$classes[] = 'col-sm-12';
			$classes[] = 'col-md-8';
			$classes[] = 'col-md-push-4';
		}elseif( 'fullwidth' == $layout ){
			$classes[] = 'col-sm-12';
		}
	}		

	return $classes;
}

add_filter( 'yt_site_secondary_classes', 'yt_bbpress_site_secondary_class' );
/**
 * yt_site_secondary_classes
 */
function yt_bbpress_site_secondary_class( $classes ){

	if( yt_is_bbpress() ){	
		$classes = array();	
		
		$layout = yt_get_options( 'bbp_layout' );

		if( 'default' == $layout ){
			$classes[] = 'col-sm-12';
			$classes[] = 'col-md-4';
		}elseif( 'right-sidebar' == $layout ){
			$classes[] = 'col-sm-12';
			$classes[] = 'col-md-4';
		}elseif( 'left-sidebar' == $layout ){
			$classes[] = 'col-sm-12';
			$classes[] = 'col-md-4';
			$classes[] = 'col-md-pull-8';
		}
	}		

	return $classes;
}

add_filter( 'yt_site_secondary_content_condition', 'yt_bbpress_get_sidebar' );
/**
 * Sidebar
 */
function yt_bbpress_get_sidebar( $condition ){
	if( yt_is_bbpress() && 'fullwidth' == yt_get_options( 'bbp_layout' ) ){
		return false;
	}
	return $condition;

}

add_filter( 'yt_theme_dynamic_sidebars_default', 'yt_bbpress_sidebar', 10, 1 );
/**
 * Sidebar
 */
function yt_bbpress_sidebar( $default ){
	if( yt_is_bbpress() ){
		if( !is_singular() ){

			$sidebar = yt_get_options( 'bbp_archive_sidebar' );

			if( 'none' !== $sidebar )
				return $sidebar;	
		}else{

			$sidebar = yt_get_options( 'bbp_single_topic_sidebar' );
			if( 'none' !== $sidebar )
				return $sidebar;
		}
		
	}
	return $default;
}

add_filter( 'yt_theme_options', 'yt_bbpress_theme_options', 100 );
/**
 * Theme options
 */
function yt_bbpress_theme_options( $options ) {

	$show_hide = array(
		'show' => __('Show', 'yeahthemes'), 
		'hide' => __('Hide', 'yeahthemes')
	);

	global $wp_registered_sidebars;

	//print_r( $wp_registered_sidebars );
	
	$url =  YEAHTHEMES_FRAMEWORK_URI . 'admin/assets/images/';	

	$bbp_options = apply_filters( 'yt_bbpress_theme_options' , array(
		array( 
			'name' => __('Forum','yeahthemes'),
			'desc' => '',
			'type' => 'heading',
			'customize' => 1,
			'settings' => array(
				'icon' => 'forum'
			)
			
		),

		array( 
			'name' => __('Default Layout','yeahthemes'),
			'desc' => __('Choose default layout for Forum pages (Forum Archive, Single Topic...).','yeahthemes'),
			'id' => 'bbp_layout',
			'std' => 'default',
			'type' => 'images',
			'options' => apply_filters( 'yt_theme_options_option_general_bbpress_layout' ,array(
				'default' 		=> $url . 'coldefault@2x.png',
				'right-sidebar' => $url . '2cr@2x.png',
				'fullwidth'		=> $url . '1col@2x.png',
				'left-sidebar' 	=> $url . '2cl@2x.png'
			) ),
			'settings' =>  array(
				'width' => '45px', 
				'height' => '36px'
			),
			'customize' => 1
			
		),

		array( 
			'name' 		=> __( 'Forum Archive sidebar', 'yeahthemes' ),
			'desc'		=> __( 'Apply to topic/forum archive', 'yeahthemes' ),
			'id' 		=> 'bbp_archive_sidebar',
			'type' 		=> 'select',
			'options'	=> array_merge( yt_get_registered_sidebars(), array( 'none' => __('None', 'yeahthemes') ) ),
			'settings' => array(
			)
		),
		array( 
			'name' 		=> __( 'Single Topic sidebar', 'yeahthemes' ),
			'desc'		=> '',
			'id' 		=> 'bbp_single_topic_sidebar',
			'type' 		=> 'select',
			'options'	=> array_merge( yt_get_registered_sidebars(), array( 'none' => __('None', 'yeahthemes') ) ),
			'settings' => array(
			)
		)

	) );
	return array_merge( $options, $bbp_options );
}

/**
 * Sticky label
 *
 * @access    public
 * @since     1.0
 */
if( !function_exists('yt_bbpress_sticky_topic_label')) {
	add_action( 'bbp_theme_before_topic_title', 'yt_bbpress_sticky_topic_label' );
	function yt_bbpress_sticky_topic_label() {
		
		global $post;
		
		$topic_id = bbp_get_topic_id();
		
		if(bbp_is_topic_sticky($topic_id)){
			echo '<span class="label '.(bbp_is_topic_super_sticky($topic_id) ? ' label-primary super-sticky bbp-super-sticky-label' : 'sticky label-primary bbp-sticky-label' ).' margin-right-10" title="">'.__('Sticky','yeahthemes').'</span>';
			
		}
		
	}
}

add_action( 'bbp_theme_before_forum_title', 'yt_bbpress_forum_thumbnail' );
/**
 * Forum archive topic thumbnail
 *
 * @access    public
 * @since     1.0
 */
if( !function_exists('yt_bbpress_forum_thumbnail')) {

	function yt_bbpress_forum_thumbnail() {
		
		global $post;
		
		$output = '';
		$post_id = bbp_get_forum_id();
		
		$thumbnail = has_post_thumbnail($post_id) ? get_the_post_thumbnail($post_id, 'thumbnail', array('class'=> 'yt-bbp-forum-thumnnail')) : '';
		
		echo !empty( $thumbnail ) ? '<a href="' . esc_url( get_permalink( $post_id ) ) . '" class="yt-bbp-post-thumbnail" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . $thumbnail .'</a>' : '';
		
		
	}
}

add_action( 'bbp_theme_before_topic_title', 'yt_bbpress_topic_thumbnail' );
/**
 * Forum archive topic thumbnail
 *
 * @access    public
 * @since     1.0
 */
if( !function_exists('yt_bbpress_topic_thumbnail')) {
	function yt_bbpress_topic_thumbnail() {
		
		global $post;
		$post_id = bbp_get_topic_id();
		
		$thumbnail = has_post_thumbnail($post_id) ? get_the_post_thumbnail($post_id, 'thumbnail', array('class'=> 'yt-bbp-topic-thumnnail')) : '';
		
		echo !empty( $thumbnail ) ? '<a href="' . esc_url( get_permalink( $post_id ) ) . '" class="yt-bbp-post-thumbnail" title="' . esc_attr( get_the_title( $post_id ) ) . '">' . $thumbnail .'</a>' : '';
		
		
	}
}

add_action( 'bbp_theme_before_reply_content', 'yt_bbpress_reply_thumbnail' );
/**
 * Topic thumbnail for single topic
 *
 * @access    public
 * @since     1.0
 */
if( !function_exists('yt_bbpress_reply_thumbnail')) {
	function yt_bbpress_reply_thumbnail() {
		
		global $post;

		static $yt_first_reply = true;
		
		$output = '';
		$post_id = bbp_get_topic_id();
		
		if(has_post_thumbnail($post_id)){
			
			$output = '<div class="bbp-topic-thumbnail" title="' . esc_attr( get_the_title( $post_id ) ).'">' . get_the_post_thumbnail( $post_id, 'large' ) . '</div>';
		}
		
		echo !empty( $yt_first_reply ) ? $output : '';
		
		$yt_first_reply = false;
		
	}
}


endif;// class_exists('bbPress')