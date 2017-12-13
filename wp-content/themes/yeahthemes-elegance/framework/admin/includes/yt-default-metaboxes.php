<?php
 // This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Default Metaboxes
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://wpthms.com
 * @since		Version 1.0
 * @package 	Yeahthemes
 */


if( is_admin() )
	add_filter( 'yt_meta_boxes', 'yt_main_metaboxes_controls' );

if( !function_exists( 'yt_main_metaboxes_controls' ) ){

	function yt_main_metaboxes_controls( $meta_boxes ) {
		
		$shortname = 'yt_';
		
		$_show_hide = array(
			'show' => esc_html__( 'Show', 'yeahthemes'),
			'hide' => esc_html__( 'Hide', 'yeahthemes'),
		);
		$_false_true = array(
			'' => esc_html__( 'False', 'yeahthemes'),
			1 => esc_html__( 'True', 'yeahthemes'),
		);
		
		$sidebar_list = yt_get_registered_sidebars();
		$sidebar_list['none'] = esc_html__( 'None', 'yeahthemes');
		
		
		$_global_settings = array();
		$_page_settings = array();
		$_page_query_posts = array();
		$_page_elite_builder = array();
		
		$_global_settings = apply_filters( 'yt_main_metabox_global_settings', array(
			// array( 
			// 	'name' 		=> __( 'Content Settings', 'yeahthemes'),
			// 	'desc' 		=> __( 'Select content source that will be displayed from.', 'yeahthemes'),
			// 	'id' 		=> $shortname . 'page_content_settings',
			// 	'std' 		=> '',
			// 	'type' 		=> 'select',
			// 	'options' 	=> array(
			// 		'default'				=> __( 'Default Editor', 'yeahthemes'),
			// 		'editor-queryposts' 	=> __( 'Editor + Query Posts', 'yeahthemes')
			// 	),
			// )
		), $shortname );
		
		
		$sidebar_image_uri = YEAHTHEMES_FRAMEWORK_URI . 'admin/assets/images/';
		
		
		/*Tab: Page Settings */
		
		$_page_settings 	= apply_filters( 'yt_main_metabox_page_settings', array(
			array( 
				'type' => 'tab',
				'name' => esc_html__( 'Page Settings', 'yeahthemes'),
			),
			/*array( 
				'name' 		=> __( 'Sidebar layout', 'yeahthemes' ),
				'desc' 		=> __( 'Default sidebar will be retrieved from Theme Options', 'yeahthemes' ),
				'id' 		=> $shortname . 'page_sidebar_layout',
				'std' 		=> 'default',
				'type' => 'columns',
		        'options' =>  array(
					'default' => 'default',
					'left-sidebar' => '4+8',
					'fullwidth' => '12',
					'right-sidebar' => '8+4',
				)
				
			),*/
			array( 
				'name' 		=> esc_html__( 'Default sidebar', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> $shortname . 'page_default_sidebar',
				'type' 		=> 'select',
				'options'	=> array_merge( yt_get_registered_sidebars(), array( 'none' => __('None', 'yeahthemes') ) ),
				'settings' => array(
				)
			)
		), $shortname );
		
		
		/*Merger $_global_settings & $_page_settings */
		$_metabox_controls = array_merge( $_global_settings, $_page_settings );
		
		$_metabox_controls = apply_filters( 'yt_main_metabox_page_controls', $_metabox_controls, $shortname );
		
		$meta_boxes[] = array(
			'id'         => 'yt_main_metabox_page_controls',
			'title'      => apply_filters( 'yt_main_metabox_page_controls_panel_title', esc_html__( 'Page Settings Panel', 'yeahthemes') ),
			'pages'      => array( 'page', ), // Post type
			'context'    => 'normal',
			'priority'   => 'high',
			'show_names' => true, // Show field names on the left
			'fields'     => $_metabox_controls
		);
		
		//print_r($meta_boxes);
		
		return $meta_boxes;
		
	}

}