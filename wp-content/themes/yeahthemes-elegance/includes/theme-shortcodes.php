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
 * Enable shortcode in text widget
 */
add_filter('widget_text', 'do_shortcode');

/**
 * YT_Shortcode_Generator class
 */
if( class_exists( 'YT_Shortcode_Generator' ) ){

	//add_filter( 'yt_shortcode_default_settings_fields', '__return_false' );

	// add_filter( 'yt_shortcode_front_css_bootstrap', '__return_false' );
	// add_filter( 'yt_shortcode_front_js_bootstrap', '__return_false' );
	//add_filter( 'yt_shortcode_editor_style_css_bootstrap', '__return_false' );

	add_filter( 'yt_shortcode_setting_fields', 'yt_theme_shortcode_fields' );
}

if( !function_exists( 'yt_theme_shortcode_fields' ) ){

	function yt_theme_shortcode_fields( $fields ){
 		$optionsTrueFalse = array(
 			'true' => __('True', 'yeahthemes'),
 			'false' => __('False', 'yeahthemes')
 		);
		
 		$optionOrderBy = array(
 			'date'	=> __('Date', 'yeahthemes'),
 			'title'	=> __('Title', 'yeahthemes'),
 			'name'	=> __('Post slug', 'yeahthemes'),
 			'author'	=> __('Author', 'yeahthemes'),
 			'comment_count'	=> __('Number of comments', 'yeahthemes'),
 			'modified'	=> __('Last modified date', 'yeahthemes'),
 			'rand'	=> __('Random order', 'yeahthemes'),
 		);

 		if( function_exists('yt_simple_post_views_tracker_display') ){
 			$optionOrderBy['meta_value_num'] = __('Post views', 'yeahthemes');
 		}

		/**
		 * Post List
		 */
		$fields['post_list'] = apply_filters( 'yt_theme_shortcode_args_post_list', array(
					
			'name' => __('Post List', 'yeahthemes'),
			'desc' => '',
			'settings' => array(
				'iconfont' => '',
				'iconimage' => '',
				'syntax' => '[post_list title="{{title}}" style="{{style}}" cat="{{cat}}" tags="{{tags}}" count="{{count}}" exclude_format="{{exclude_format}}" order="{{order}}" orderby="{{orderby}}" time_period="{{time_period}}" show_icon="{{show_icon}}" show_cat="{{show_cat}}" show_date="{{show_date}}"]'
			),
			'options' => array(
				'title' => array(
					'std' => '',
					'type' => 'text',
					'name' => __('Title', 'yeahthemes'),
					'desc' => '',
				),
				'style' => array(
					'std' => '',
					'type' => 'select',
					'options' => array(
						'small' => __('Default (Small)', 'yeahthemes'),
						'large' => __('Large', 'yeahthemes'),
						'mixed' => __('First Large', 'yeahthemes'),
						'number' => __('Number (no thumb)', 'yeahthemes'),
						'nothumb' => __('Title Only', 'yeahthemes'),
						'thumb_first' => __('First item have thumbnail', 'yeahthemes'),
					),
					'name' => __('Thumbnail style:', 'yeahthemes'),
					'desc' => '',
					'settings' => array()
				),
				// 'cat' => array(
				// 	'std' => '',
				// 	'type' => 'select',
				// 	'options' => yt_get_category_list(),
				// 	'name' => __('Categories', 'yeahthemes'),
				// 	'desc' => __('Specify categories to retrieve posts from. Hold cmd/ctrl + click to select/deselect multiple tagsb<br>If no category was selected, retrieve all', 'yeahthemes'),
				// 	'settings' => array(
				// 		'multiple' => 1
				// 	)
				// ),
				// 'tags' => array(
				// 	'std' => '',
				// 	'type' => 'select',
				// 	'options' => yt_get_tag_list(),
				// 	'name' => __('Tagged in (optional):', 'yeahthemes'),
				// 	'desc' => __('Specify tags to retrieve posts from. Hold cmd/ctrl + click to select/deselect multiple tags', 'yeahthemes'),
				// 	'settings' => array(
				// 		'multiple' => 1
				// 	)
				// ),
				'cat' => array(
					'name' => __('Categories','yeahthemes'),
					'type' => 'text',
					'std' => '',
					'desc' => __('Specify category ids to retrieve posts from. seperate multiple cat ids by comma.If no category was specified, retrieve all','yeahthemes'),
					'settings' => array()
				),
				'tags' => array(
					'name' => __('Tagged in (optional):','yeahthemes'),
					'type' => 'text',
					'std' => '',
					'desc' => __('Specify tags to retrieve posts from. seperate multiple tag ids by comma.','yeahthemes'),
					'settings' => array()
				),
				'exclude_format' => array(
					'name' 		=> __( 'Exclude post format', 'yeahthemes' ),
					'desc'		=> __( 'Specify post formats you don\'t want to retrieve.', 'yeahthemes' ),
					'std' 		=> '',
					'type' 		=> 'select_alt',
					'options' 	=> yt_get_supported_post_formats(),
					'settings' => array(
						'multiple' => 1
					)
				),
				'count' => array(
					'std' => '5',
					'type' => 'number',
					'name' => __('Number of Post', 'yeahthemes'),
					'desc' => '',
				),
				'order' => array(
					'std' => '',
					'type' => 'select',
					'options' => array(
						'DESC' => __('Descending', 'yeahthemes'),
						'ASC' => __('Ascending', 'yeahthemes'),
					),
					'name' => __('Order', 'yeahthemes'),
					'desc' => '',
					'settings' => array()
				),
				'orderby' => array(
					'std' => '',
					'type' => 'select',
					'options' => $optionOrderBy,
					'name' => __('Order by', 'yeahthemes'),
					'desc' => '',
					'settings' => array()
				),
				'time_period' => array(
					'std' => '',
					'type' => 'select',
					'options' => array(
						'default' => __('Default', 'yeahthemes'),
						'this_week' => __('This week', 'yeahthemes'),
						'last_week' => __('Last week', 'yeahthemes'),
						'this_month' => __('This Month', 'yeahthemes'),
						'last_month' => __('Last Month', 'yeahthemes'),
						'last_30days' => __('Last 30 days', 'yeahthemes'),
					),
					'name' => __('Time period:', 'yeahthemes'),
					'desc' => '',
					'settings' => array()
				),
				'show_icon' => array(
					'std' => 'false',
					'type' => 'select',
					'options' => $optionsTrueFalse,
					'name' => __('Show views/comment counter', 'yeahthemes'),
					'desc' => __('Views counter only shows when YT Simple Post view tracker is installed and activated', 'yeahthemes'),
					'settings' => array()
				),
				'show_cat' => array(
					'std' => 'false',
					'type' => 'select',
					'options' => $optionsTrueFalse,
					'name' => __(' Show category tag', 'yeahthemes'),
					'desc' => '',
					'settings' => array()
				),
				'show_date' => array(
					'std' => 'true',
					'type' => 'select',
					'options' => $optionsTrueFalse,
					'name' => __('Show post date', 'yeahthemes'),
					'desc' => '',
					'settings' => array()
				),
				'show_rating' => array(
					'std' => 'true',
					'type' => 'select',
					'options' => $optionsTrueFalse,
					'name' => __('Show Review result', 'yeahthemes'),
					'desc' => __('Only shows when WP Review is installed and activated', 'yeahthemes'),
					'settings' => array()
				),
			)
		) );


		

		return $fields;
	}
}




/*===========================================================*/
/**
 * Post List
 */
function yt_theme_shortcode_post_list( $atts, $content = null ) {

	extract( shortcode_atts( array(
		'title' => '',
		'style' => 'small',
		'cat' => '',
		'tags' => '',
		'count' => 5,
		'exclude_format' => '',
		'order' => 'DESC',
		'orderby' => 'date',
		'time_period' => '',
		'show_icon' => 0,
		'show_cat' => 0,
		'show_date' => 1,
		'show_rating' => 0,
		'item_wrapper' => false
	), $atts));

	$cat_ids = !empty( $cat ) && is_string( $cat ) ? explode( ',', $cat ) : array();
	$tags_ids = !empty( $tags ) && is_string( $tags ) ? explode( ',', $tags ) : array();
	$exclude_formats = !empty( $exclude_format ) && is_string( $exclude_format ) ? explode( ',', $exclude_format ) : array();

	$instance = array();

	$instance['style'] 		= !empty( $style ) ? $style : 'small';
	$instance['category'] 	= $cat_ids;
	$instance['tags'] 		= $tags_ids;
	$instance['order'] 		= $order;
	$instance['orderby'] 	= $orderby;
	$instance['number'] 	= !empty( $count ) && is_numeric( $count ) ? $count : 5;
	$instance['time_period']= $time_period;

	$instance['exclude_format'] = $exclude_formats;
	$instance['show_icon'] 	= $show_icon == 'true' ? 1 : 0;
	$instance['show_cat'] 	= $show_cat == 'true' ? 1 : 0;
	$instance['show_date'] 	= $show_date == 'true' ? 1 : 0;
	$instance['show_rating'] 	= $show_date == 'true' ? 1 : 0;
	$instance['item_wrapper'] 	= 'div';


	$output = $title ? '<h3 class="widget-title">' . $title . '</h3>' : '';
	
	if( function_exists( 'yt_site_post_list' ) && is_callable( 'yt_site_post_list' ) ){
		ob_start();
			yt_site_post_list( $instance );	
			
			$output .= ob_get_contents();
		ob_end_clean();
	}


	return apply_filters( 'yt_theme_shortcode_post_list_html', $output, $atts );
}
