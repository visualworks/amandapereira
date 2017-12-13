<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Theme Options
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeah Includes
 */

if( !function_exists( 'yt_theme_options' ) ) {
	
	function yt_theme_options() {
		
		$yt_data = yt_get_options();
		
		$on_off = array(
			'on' => __('ON', 'yeahthemes'), 
			'off' => __('OFF', 'yeahthemes')
		);
		$show_hide = array(
			'show' => __('Show', 'yeahthemes'), 
			'hide' => __('Hide', 'yeahthemes')
		);
		
		/* Font faces */
		$fontfaces = yt_get_option_vars( 'fontfaces' );
		// Yes/No
			
		$yes_no = array('1' => __('Yes', 'yeahthemes'), '0' => __('No', 'yeahthemes'));
		
		//
		$enable_disable = array(
			'enable' => __('Enable', 'yeahthemes'), 
			'disable' => __('Disable', 'yeahthemes')
		);
		
		// True/False
	$true_false = array(
			'true' => __('True', 'yeahthemes'), 
			'false' => __('False', 'yeahthemes')
		);		
		
		//$yt_subscription_form = array('file' => __('Save to .CSV file','yeahthemes'), 'mailchimp' => 'MailChimp', 'feedburner' => 'FeedBurner');
			
		//Background Images Reader
		$bg_images_path = yt_get_overwritable_directory( '/images/bg/' ); // change this to where you store your bg images
		$bg_images_url = yt_get_overwritable_directory_uri( '/images/bg/' ) ; // change this to where you store your bg images
		
		
		$bg_images = array();
		
		if ( is_dir($bg_images_path) ) {
			if ($bg_images_dir = opendir($bg_images_path) ) { 
				while ( ($bg_images_file = readdir($bg_images_dir)) !== false ) {
					if(stristr($bg_images_file, '.png') !== false || stristr($bg_images_file, '.jpg') !== false) {
						$bg_images[] = $bg_images_url . $bg_images_file;
					}
				}    
			}
		}
		
		//print_r($bg_images);
		/*-----------------------------------------------------------------------------------*/
		/* TO DO: Add options/functions that use these */
		/*-----------------------------------------------------------------------------------*/
		
		//More Options
		$uploads_arr = wp_upload_dir();
		$all_uploads_path = $uploads_arr['path'];
		$all_uploads = get_option('yt_uploads');
		$other_entries = array('Select a number:','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19');
		$body_repeat = array('no-repeat','repeat-x','repeat-y','repeat');
		$body_pos = array('top left','top center','top right','center left','center center','center right','bottom left','bottom center','bottom right');
		
		// Image Alignment radio box
		$yt_options_thumb_align = array('alignleft' => 'Left','alignright' => 'Right','aligncenter' => 'Center'); 
				
		$url =  YEAHTHEMES_FRAMEWORK_URI . 'admin/assets/images/';	
		
		$yt_options = array();
		
		/**
		 * General Settings
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_generalsettings', array(	
			





			array( 
				'name' => __('General Settings','yeahthemes'),
				'desc' => __('Logo, Email, Copyright ,Contact/Branding informations ...','yeahthemes'),
				'type' => 'heading',
				'customize' => 1,
				'settings' => array(
					'icon' => 'generalsettings'
				)
				
			),
			array( 
				'type' => 'subheading',
				'name' => __('Overall','yeahthemes'),
				'customize' => 1,
				'customize_name' => __('General Settings - Overall','yeahthemes'),
			),
			array(
				'name' => __('Logo types','yeahthemes'),
				'desc' => sprintf(__('You can change site title and site tagline in <a href="%s">%s</a>','yeahthemes'), admin_url( 'options-general.php' ), __('Settings -> General', 'yeahthemes' ) ),
				'id' => 'site_logo_types',
				'std' => 'modal',
				'type' => 'select',
				'customize' => 1,
				'options' => array(
					'default' => __('Site title + tagline', 'yeahthemes'),
					'image' => __('Image Logo', 'yeahthemes'),
					'image_tagline' => __('Image Logo + Tagline', 'yeahthemes'),
				),
				'settings' => array(
					'folds' => '0',
				)
			),
			
			array( 
				'name' => __('Custom Logo','yeahthemes'),
				'desc' => __('Upload a logo for your theme, or specify the image address of your online logo. (http://example.com/logo.png) <br>Dimension: 250px for width and 100px for height (if you use short banner it will be 80px).','yeahthemes'),
				'id' => 'logo',
				'std' => YEAHTHEMES_URI . 'images/logo.png',
				'type' => 'media',
				'customize' => 1,
				'settings' => array(
					'sanitize' => 'esc_url',
					'fold' => 'site_logo_types',
					'fold_value' => 'image,image_tagline',
				)
			),
			array( 
				'name' => __('Boxed layout','yeahthemes'),
				'desc' => '',
				'id' => 'site_boxed_layout_mode',
				'std' => 0,
				'type' => 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'customize' => 1,
				'settings' => array(
					'label' => __('Toggle this to enable boxed layout (narrow width).','yeahthemes'),
				)
			),
			array( 
				'name' => __('Default Layout','yeahthemes'),
				'desc' => __('Select main content and sidebar alignment.','yeahthemes'),
				'id' => 'layout',
				'std' => 'default',
				'type' => 'columns',
		        'options' =>  array(
					'default' => 'default',
					'left-sidebar' => '4+8',
					'fullwidth' => '12',
					'right-sidebar' => '8+4',
				)
				
			),
			array( 
				'name' => __('Page comment box','yeahthemes'),
				'desc' => '',
				'id' => 'site_page_comment_mode',
				'std' => 0,
				'type' => 'checkbox',	
				'class' => 'yt-section-toggle-checkbox',
				'customize' => 1,
				'settings' => array(
					'label' => __('Toggle this to enable Page comment box.', 'yeahthemes'),
				)
			),
			array(
				'name' => __('Sidebar width','yeahthemes'),
				'id' => 'sidebar_width',	
				'std' => 300,
				'type' => 'uislider',
				'settings' => array(
					'min' => 100,
					'max' => 500,
					'step' => 10,
					'unit' => 'px',
				),		
				'desc' => __('Change your sidebar width! default is 300 (px)','yeahthemes'),
			),
		) ) );
		/**
		 * General Settings - Header
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_generalsettings_header', array(	
			array(
				'name' => __('Header','yeahthemes'),
				'type' => 'subheading',
				'customize' => 1,
				'customize_name' => __('General Settings - Header','yeahthemes')
			),

			array(
				'name' => __('Header Style','yeahthemes'),
				'desc' => '',
				'id' => 'header_style',
				'std' => 'default',
				'type' => 'select',
				'customize' => 1,
				'options' => array(
					'default' => __('Blog', 'yeahthemes'),
					'blog_alt' => __('Blog Alt', 'yeahthemes'),
					'magazine' => __('Magazine', 'yeahthemes'),
					'newspaper' => __('Newspaper', 'yeahthemes'),
				),
				'settings' => array(
					'after_control' => '<img src="' . esc_url( YEAHTHEMES_INCLUDES_URI . 'images/header-preview2.jpg' ) . '">',
				)
			),
			array(
				'name' => __('Top bar menu','yeahthemes'),
				'desc' => '',
				'id' => 'header_top_bar_menu',
				'std' => 'hide',
				'type' => 'toggles',
				'options' => $show_hide
			),
			array( 
				'name' => __('Left Top Bar','yeahthemes'),
				'id' => 'left_top_bar',
				'std' => '<div class="tel-numbers">Tel: <strong>+00 123 456 789</strong></div>',
				'type' => 'textarea',
				'customize' => 1,
			),
			array( 
				'name' => __('Scrollfix Main menu','yeahthemes'),
				'desc' => '',
				'id' => 'header_scrollfix_mainmenu',
				'std' => 0,
				'type' => 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'customize' => 1,
				'settings' => array(
					'label' => __('Toggle this to enable Scrollfix Main menu style.','yeahthemes'),
				)
			),
			array(
				'name' => __('Social links Position','yeahthemes'),
				'desc' => __('Social Media links with icons','yeahthemes'),
				'id' => 'header_social_links_position',
				'std' => 'hide',
				'type' => 'select',
				'customize' => 1,
				'options' => array(
					'hide' => __('Hide', 'yeahthemes'),
					'top_menu' => __('On Top Menu (Secondary menu)', 'yeahthemes'),
					'main_menu' => __('On Main Menu (Primary Menu)', 'yeahthemes'),
				)
			),

			
			array(
				'name' => __('Search','yeahthemes'),
				'desc' => __('The search button on Main Menu navigation (Primary menu)','yeahthemes'),
				'id' => 'header_search',
				'std' => 'hide',
				'type' => 'toggles',
				'options' => $show_hide
			),

		) ) ); 

		/**
		 * General Settings - footer
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_generalsettings_footer', array(	
			array( 
				'type' => 'subheading',
				'name' => __('Footer','yeahthemes'),
				'customize' => 1,
				'customize_name' => __('General Settings - Footer','yeahthemes'),
			),

			array(
				'name' => __('Footer Instagram Feed','yeahthemes'),
				'desc' => sprintf( __('This will show 16 images from your instagram, retrieved by id you put through <a href="%s" target="_blank">API</a>','yeahthemes'), admin_url( '/admin.php?page=yt-theme-options#yt-option-api-subscribeconnect' ) ),
				'id' => 'footer_instagram',
				'std' => 'hide',
				'type' => 'toggles',
				'options' => $show_hide
			),
			array( 
				'name' => __('Footer Layout','yeahthemes'),
				'desc' => __('Select a footer layout style. (full, half, small)','yeahthemes'),
				'id' => 'footer_layout',
				'std' => 'footer-extra',
				'type' => 'columns',
				'options' => array(
					'footer-full-layout' => '9+3',
					'footer-widget' => '9',
					'footer-extra' => '3'
				),
				'settings' =>  array(
					 'direction' => 'horizontal'
				),
				
			),
			array( 
				'name' => __('Footer Columns','yeahthemes'),
				'desc' => __('Select the number of columns you would like to display in the footer.','yeahthemes'),
				'id' => 'footer_columns',
				'std' => 'col-sm-3_col-sm-3_col-sm-3_col-sm-3',
				'type' => 'columns',
				'options' => yt_get_option_vars( 'footer_columns' ),
				'settings' =>  array(
				),
			),
			array( 
				'name' => __('Left Footer credit','yeahthemes'),
				'id' => 'footer_text_left',
				'std' => '&copy; ' . date("Y") . ' <a href="http://' . 'yeahthemes.com" title="Everything you need to create a trendy, uniquely beautiful website without any of coding knowledge">Yeahthemes</a>. All Rights Reserved. Powered by <a href="http://wordpress.org">WordPress</a>.',
				'type' => 'textarea',
				'customize' => 1,
			),
			array( 
				'name' => __('Right Footer credit','yeahthemes'),
				'id' => 'footer_text_right',
				'std' => '<a href="#masthead" class="back-to-top">Back to top</a>',
				'type' => 'textarea',
				'customize' => 1,
			)
		) ) );

		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_blogsettings_hero_banner', array(
			array(
				'type' => 'subheading',
				'name' => __('Hero Banner','yeahthemes'),
				'customize' => 1,
				'customize_name' => __('Hero Banner','yeahthemes'),
			),
			array( 
				'name' => __('Hero Banner','yeahthemes'),
				'desc' => '',
				'id' => 'site_hero_banner_mode',
				'std' => 0,
				'type' => 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'customize' => 1,
				'settings' => array(
					'label' => __('Toggle this to enable Hero Banner.','yeahthemes'),
				)
			),
			array(
				'name' => __('Layout type','yeahthemes'),
				'desc' => '',
				'id' => 'site_featured_articles_layout',
				'std' => 'default',
				'type' => 'select',
				'customize' => 1,
				'options' => array(
					'default' => __('Default (Static post)', 'yeahthemes'),
					'static-alt' => __('Static Posts', 'yeahthemes'),
					'carousel' => __('Carousel Slider', 'yeahthemes'),
					'blocks-alt' => __('Bricks (1x2)', 'yeahthemes'),
					'blocks' => __('Bricks (2x3)', 'yeahthemes'),
				),
				'settings' => array(
					'folds' => '0',
				)
			),
			array( 
				'name' => __('Autoplay','yeahthemes'),
				'desc' => '',
				'id' => 'site_hero_banner_autoplay_mode',
				'std' => 0,
				'type' => 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'settings' => array(
					'label' => __('Toggle this to enable autoplay mode. for Carousel Slider','yeahthemes'),
					'fold' => 'site_featured_articles_layout',
					'fold_value' => 'carousel',
				)
			),

			array(
				'name' => __('Query Settings','yeahthemes'),
				'desc' => '',
				'id' => 'site_featured_articles_args',
				'std' => '',
				'type' => 'group_options',
				'customize' => 0,
				'options' => array(
					array(
						'name' => __('Categories','yeahthemes'),
						'id' => 'cat',
						'type' => 'tag_search',
						'std' => '',
						'desc' => __('Keep typing and you will be suggested','yeahthemes'),
						'settings' => array(
							'tax' => 'category'
						)
					),
					array(
						'name' => __('Tags','yeahthemes'),
						'id' => 'tag',				
						'type' => 'tag_search',
						'std' => '',
						'desc' => __('Keep typing and you will be suggested','yeahthemes'),
					),
					array(
						'name' => __('Exclude from Categories','yeahthemes'),
						'id' => 'cat_not_in',
						'type' => 'tag_search',
						'std' => '',
						'desc' => __('Keep typing and you will be suggested','yeahthemes'),
						'settings' => array(
							'tax' => 'category'
						)
					),
					array(
						'name' => __('Exclude from Tags','yeahthemes'),
						'id' => 'tag_not_in',				
						'type' => 'tag_search',
						'std' => '',
						'desc' => __('Keep typing and you will be suggested','yeahthemes'),
					),
					array(
						'name' => __('Order','yeahthemes'),
						'id' => 'order',						
						'type' => 'select',
						'std' => 'DESC',
						'desc' => __('Keep typing and you will be suggested','yeahthemes'),
						'options' => array(
							'DESC' 		=> __( 'Descending', 'yeahthemes' ),
							'ASC' 		=> __( 'Ascending', 'yeahthemes' ),
						)
					),
					array(
						'name' => __('Order by','yeahthemes'),
						'id' => 'orderby',
						'std' => 'DESC',
						'type' => 'select',
						'options' => array(
							'date' 		=> __( 'Date', 'yeahthemes' ),
							'title' 	=> __( 'Title', 'yeahthemes' ),
							'name' 		=> __( 'Post slug', 'yeahthemes' ),
							'author' 	=> __( 'Author', 'yeahthemes' ),
							'modified' => __( 'Last modified date', 'yeahthemes' ),
							'comment_count' => __( 'Number of comments', 'yeahthemes' ),
							'rand' 		=> __( 'Random order', 'yeahthemes' ),
						)
					),
					array(
						'name' => __('Number of Posts','yeahthemes'),
						'id' => 'posts_per_page',						
						'type' => 'number',
						'std' => 10,						
						'desc' => __('Only apply to Carousel Layout','yeahthemes'),
					),
					array(
						'name' => __('Exclude post format','yeahthemes'),
						'id' => 'exclude_format',						
						'type' => 'multicheck',
						'std' => array(),
						'options' => yt_get_supported_post_formats(),
						'settings' => array(
							'is_indexed' => 1
						)
					),

				),
				'settings' => array(
					
				)
			),
			array( 
				'name' => __('Shuffle blocks','yeahthemes'),
				'desc' => '',
				'id' => 'site_hero_banner_shuffle_mode',
				'std' => 0,
				'type' => 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'settings' => array(
					'label' => __('Toggle this to enable shuffle mode.','yeahthemes'),
				)
			),

		) ) );
				 
		/**
		 * Blog Settings
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_blogsettings', array(
			array( 
				'name' => __('Blog Settings','yeahthemes'),
				'type' => 'heading',
				'desc' => __('Configure parameters for default blog page','yeahthemes'),
				'customize' => 1,
				'settings' => array(
					'icon' => 'blogsettings'
				)
			),
		) ) );
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_blogsettings_general', array(
			array(
				'type' => 'subheading',
				'name' => __('General','yeahthemes'),
				'customize' => 1,
				'customize_name' => __('Blog Settings - General','yeahthemes'),
			),

			array(
				'name' => __('Post Layout','yeahthemes'),
				'desc' => __('Default Post layout for main blog page','yeahthemes'),
				'id' => 'blog_post_layout',
				'std' => 'default',
				'type' => 'select',
				'options' => array(
					'default' => __('Default','yeahthemes'),
					'classic' => __('Classic','yeahthemes'),
					'grid' => __('Grid','yeahthemes'),
				),
				'customize' => 1,
			),
			array(
				'name' => __('Exclude Categories from Default Blog page (Page for posts)','yeahthemes'),
				'desc' => __('Specify categories that you don\'t want to display in main blog page (page for posts), Keep typing and you will be suggested','yeahthemes'),
				'id' => 'mainblog_exclude_cats',
				'std' => array(),
				// 'type' => 'category_checklist',
				'type' => 'tag_search',
				'settings' => array(
					'tax' => 'category'
				)
			),

			array( 
				'name' => __('Ignore Sticky Posts','yeahthemes'),
				'desc' => '',
				'id' => 'mainblog_ignore_sticky_posts',
				'std' => 0,
				'type' => 'checkbox',	
				'class' => 'yt-section-toggle-checkbox',
				'customize' => 1,
				'settings' => array(
					'label' => __('Toggle this to ignore sticky post on posts page.', 'yeahthemes'),
				)
			),

			array( 
				'name' => __('Hide Post thumbnail of Formats','yeahthemes'),
				'desc' => __('This option only applies to Default post layout','yeahthemes'),
				'id' => 'mainblog_hide_thumb',
				'std' => '',
				'type' => 'multicheck',
				'options' => yt_get_supported_post_formats(),
				'settings' => array(
					'is_indexed' => 1
				)
			),
			array(
				'name' => __('Excerpt output','yeahthemes'),
				'desc' => __('Handle output of excerpt content:<br>-Manual: Use <--more--> tag to split a first part of post content and use it as summary on main archive page.<br>-Automatic: Split your content automatically.','yeahthemes'),
				'id' => 'excerpt_output',
				'std' => 'manual',
				'type' => 'select',
				'options' => array(
					'manual' => __('Manual','yeahthemes'),
					'automatic' => __('Automatic','yeahthemes'),
				),
				'customize' => 1,
			),
			array(
				'name' => __('Excerpt length','yeahthemes'),
				'desc' => __('Change excerpt length to display in blog page. (default is 55 words), only apply when you handle excerpt manually.','yeahthemes'),
				'id' => 'custom_excerpt_length',
				'std' => 55,
				'type' => 'uislider',
				'settings' => array(
					'min' => 10,
					'max' => 300,
					'step' => 5,
					'unit' => 'w',
				)
			),
			array(
				'name' => __('Pagination','yeahthemes'),
				'desc' => __('Select the Pagination type for blog archive page','yeahthemes'),
				'id' => 'blog_pagination',
				'std' => 'number',
				'type' => 'select',
				'customize' => 1,
				'options' => array(
					'number' => __('Numeric Pagination','yeahthemes'), 
					'direction' => __('Next/Prev links','yeahthemes')
				)	
				
			),
			array(
				'name' => __('Read more button','yeahthemes'),
				'desc' => __('Toggle to show/hide readmore button in blog page ','yeahthemes'),
				'id' => 'blog_readmore_button',
				'std' => 'show',
				'type' => 'toggles',
				'options' => $show_hide,

				'customize' => 1,
			),

			array(
				'name' => __('Share Icons with counter','yeahthemes'),
				'desc' => __('Only default and class post layout will be applied','yeahthemes'),
				'id' => 'blog_share_icons',
				'std' => 'show',
				'type' => 'toggles',
				'options' => $show_hide,
				'customize' => 1,
			),
			array(
				'name' => __('Post format icon','yeahthemes'),
				'desc' => __('Video icon overlay on post format Video  thumbnail. This option only applies to Post Layout Grid','yeahthemes'),
				'id' => 'blog_post_format_icon',
				'std' => 'show',
				'type' => 'toggles',
				'options' => $show_hide,
				'customize' => 1,
			),
			array( 
				'name' => __('Post Meta Info','yeahthemes'),
				'desc' => '',
				'id' => 'blog_post_meta_info_mode',
				'std' => 'show',
				'type' => 'select',
				'options' => $show_hide,
				'class' => '',
				'customize' => 1,
				'settings' => array(
					'folds' => '0',
					'label' => __('Toggle this to enable post meta info.','yeahthemes'),
				)
			),
			array(
				'name' => __('Hide Post meta info','yeahthemes'),
				'desc' => __('Uncheck to hide the meta info in blog page','yeahthemes'),
				'id' => 'blog_post_meta_info',
				'std' => array(
					'author',
					'category',
					'date',
					'comments'
				),
				'type' => 'multicheck',
				'options' => array(
					'sticky' => __('Featured Badge (For Sticky Posts','yeahthemes'),
					'author' => __('Author of the Post','yeahthemes'),
					'category' => __('Post Category','yeahthemes'),
					'date' => __('Date of Post ','yeahthemes'),
					'comments' => __('Comments','yeahthemes'),
				),
				'settings' => array(
					'sortable' => true,
					'fold' => 'blog_post_meta_info_mode',
					'fold_value' => 'show',
				),
				
			)
			
			
		) ) );
		
		/**
		 * Blog Settings - Single
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_blogsettings_single', array(
			
			array(
				'type' => 'subheading',
				'name' => __('Single post','yeahthemes'),
				'customize' => 1,
				'customize_name' => __('Blog Settings - Single post','yeahthemes'),
			),
			array( 
				'name' => __('Layout','yeahthemes'),
				'desc' => __('By default, layout for single post will follow the Default layout under Interface Settings.','yeahthemes'),
				'id' => 'single_post_layout',
				'std' => 'default',
				'type' => 'columns',
		        'options' =>  array(
					'default' => 'default',
					'left-sidebar' => '4+8',
					'fullwidth' => '12',
					'right-sidebar' => '8+4',
				)
				
			),

			array(
				'name' => __('Hide Post thumbnail of Formats','yeahthemes'),
				'desc' => __('Specify which which post formats you don\'t want to show the post thumbnail (featured image)<br>This apply to single article only!','yeahthemes'),
				'id' => 'blog_single_post_featured_image',						
				'type' => 'multicheck',
				'std' => array(),
				'options' => yt_get_supported_post_formats(),
				'settings' => array(
					'is_indexed' => 1
				)
			),
			array(
				'name' => __('Related articles','yeahthemes'),
				'desc' => __('Display right before single post content','yeahthemes'),
				'id' => 'blog_single_post_related_articles',
				'std' => 'show',
				'type' => 'toggles',
				'options' => $show_hide,
				'customize' => 1,
			),
			array(
				'name' => __('Share Buttons','yeahthemes'),
				'desc' => __('Display right after single post content','yeahthemes'),
				'id' => 'blog_single_post_share_buttons',
				'std' => 'show',
				'type' => 'toggles',
				'options' => $show_hide,
				'customize' => 1,
			),
			array(
			    'id'       => 'blog_single_stuff',
			    'type'     => 'sorter',
			    'name'    => __('Manage blocks after single post','yeahthemes'),
			    'desc' => __('Drag & drop items you want to show on single post to "Enabled" column. You could also reorder the items','yeahthemes'),
			    'std' => array(
			    	'disabled' => array(),
			    	'enabled' => array( 'author_meta', 'post_nav', 'ymal', 'comments'),
			    ),
			    'options'  => array(
			        array(
			        	'id' => 'disabled',
			        	'name' => __('Disabled','yeahthemes')
			        ),
			        array(
			        	'id' => 'enabled',
			            'name' => __('Enabled','yeahthemes'),
			        )
			    ),
				'class' => 'yt-widefat-section',
			    'settings' => array(
			    	'items' => array(
				    	'author_meta' => __('Author Meta','yeahthemes'),
			            'post_nav'     => __('Posts Nav','yeahthemes'),
			            'ymal' => __('You might also like','yeahthemes'),
			            'comments' => __('Comment area','yeahthemes'),
				    )
			    )
			)
		) ) );

		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_blogsettings_archive', array(
			array(
				'type' => 'subheading',
				'name' => __('Archive','yeahthemes'),
				'desc' => __('Archive Pages such as Category, Author...','yeahthemes'),
				'customize' => 1,
				'customize_name' => __('Blog Settings - Archive','yeahthemes'),
			),
			array( 
				'name' => __('Layout','yeahthemes'),
				'desc' => __('By default, layout for archive pages will follow the Default layout under Interface Settings.','yeahthemes'),
				'id' => 'archive_layout',
				'std' => 'default',
				'type' => 'columns',
		        'options' =>  array(
					'default' => 'default',
					'left-sidebar' => '4+8',
					'fullwidth' => '12',
					'right-sidebar' => '8+4',
				)
				
			),


			array(
				'name' => __('Post Layout','yeahthemes'),
				'desc' => __('Default Post layout for archive pages','yeahthemes'),
				'id' => 'archive_post_layout',
				'std' => 'default',
				'type' => 'select',
				'options' => array(
					'default' => __('Default','yeahthemes'),
					'classic' => __('Classic','yeahthemes'),
					'grid' => __('Grid','yeahthemes'),
				),
				'customize' => 1,
			),
		) ) );
			
		/**
		 * Typography
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_typography', array(
		
			array( 
				'name' => __('Typography','yeahthemes'),			
				'type' => 'heading',
				'settings' => array(
					'icon' => 'typography'
				)
			)
		) ) );	

		/**
		 * Typography - Elements
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_typography_elements', array(
			
			array( 
				'name' => __('Elements','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Configure font parameters for element','yeahthemes'),
			),
			array(
				'name' => '',
				'type' => 'info',
				'std' => wpautop( sprintf('<h3>%s</h3>%s', __('Change your typography style in Content Area', 'yeahthemes'), 
__('(From left to right:) Font Family, Font size, Font weight, Font style, Line height, Letter Spacing, Margin Bottom

7 Best Font Combinations:

- Amatic SC + Inconsolata
- Bebas Neue + Open sans
- Raleways + Lora
- Noto Serif/Roboto slab + Lora
- Noto Serif + Noto Sans
- Josefin Sans + Cabin
- Cinzel + Lora', 'yeahthemes' ) 
					)
				)
			),
			array(
				'type' => 'tab',
				'name' =>  __('Default Typo','yeahthemes'),
			),
			array(
				'name' => __('H1 Headings','yeahthemes'),
				'desc' => __('default : Helvetica 30px normal normal 1.35 0px none 35px','yeahthemes'),
				'id' => 'typo_h1',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '30px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.35',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					//'marginbottom' 	=> '35px',
					//'color' 			=> ''
				),
				'type' => 'typography'
			),
			array(
				'name' => __('H2 Headings','yeahthemes'),
				'desc' => __('default : 26px normal normal 1.35 0px none 30px','yeahthemes'),
				'id' => 'typo_h2',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '26px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.35',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					//'marginbottom' 	=> '30px',
					//'color' 			=> ''
				),
				'type' => 'typography'
			),
			array(
				'name' => __('H3 Headings','yeahthemes'),
				'desc' => __('default : 22px normal normal 1.75 0px none 20px','yeahthemes'),
				'id' => 'typo_h3',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '22px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.75',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					//'marginbottom' 	=> '20px',
					//'color' 			=> ''
				),
				'type' => 'typography'
			),
			array(
				'name' => __('H4 Headings','yeahthemes'),
				'desc' => __('default : 20px normal normal 1.75 0px none 20px','yeahthemes'),
				'id' => 'typo_h4',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '20px',
					'weight' 			=> 'normal',
					'style'				=> 'normal',
					'height' 			=> '1.75',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					//'marginbottom' 	=> '20px',
					//'color' 			=> ''
				),
				'type' => 'typography'
			),
			array(
				'name' => __('H5 Headings','yeahthemes'),
				'desc' => __('default : 16px normal normal 1.75 0px none 15px','yeahthemes'),
				'id' => 'typo_h5',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '16px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.75',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					//'marginbottom' 	=> '15px',
					//'color' 			=> ''
				),
				'type' => 'typography'
			),
			array(
				'name' => __('H6 Headings','yeahthemes'),
				'desc' => __('default : 13px normal normal 1.75 0px none 10px','yeahthemes'),
				'id' => 'typo_h6',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '13px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.75',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					//'marginbottom' 	=> '10px',
					//'color' 			=> ''
				),
				'type' => 'typography'
			),
			array(
				'name' => __('Bold tag inside Headings','yeahthemes'),
				'desc' => __('The <strong>strong</strong> and <strong>b</strong> tag inside of Heading tags','yeahthemes'),
				'id' => 'typo_bold_heading',
				'std' => array(
					'face' 		=> '',
					'weight' 	=> 'bold',
					'style' 	=> 'normal',
				),
				'type' => 'typography'
			),
			array(
				'name' => __('Body Font','yeahthemes'),
				'desc' => __('Specify the body font properties Default: 13px normal normal 1.75 0px none','yeahthemes'),
				'id' => 'typo_body_font',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '13px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.75',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					//'color' 			=> ''
				),
				'type' => 'typography'
			)
		) ) );
		/**
		 * Typography - Elements - Header
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_typography_elements_header', array(
			array(
				'type' => 'tab',
				'name' => __('Header','yeahthemes'),
			),
			array(
				'name' => __('Top Bar','yeahthemes'),
				'desc' => __('default : 11px normal normal','yeahthemes'),
				'id' => 'typo_top_bar',
				'std' => array(
					'face' 			=> '',
					'size' 			=> '11px',
					'weight' 		=> 'normal',
					'style' 		=> 'normal',
					'transform' 	=> 'none',
					'letterspacing' 	=> '0px'
				),
				'type' => 'typography'
			),
			array(
				'name' => __('Main Menu','yeahthemes'),
				'desc' => __('default : 14px normal normal','yeahthemes'),
				'id' => 'typo_main_menu',
				'std' => array(
					'face' 			=> '',
					'size' 			=> '14px',
					'weight' 		=> 'normal',
					'style' 		=> 'normal',
					'transform' 	=> 'none',
					'letterspacing' 	=> '0px'
				),
				'type' => 'typography'
			),
			array(
				'name' => __('Main Menu Child Level','yeahthemes'),
				'desc' => __('default : 12px normal normal','yeahthemes'),
				'id' => 'typo_main_menu_child',
				'std' => array(
					'face' 			=> '',
					'size' 			=> '12px',
					'weight' 		=> 'normal',
					'style' 		=> 'normal',
					'transform' 	=> 'none',
					'letterspacing' 	=> '0px'
				),
				'type' => 'typography'
			),
		) ) );
		
		/**
		 * Typography - Elements - Content
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_typography_elements_content', array(
			array(
				'type' => 'tab',
				'name' => __('Content','yeahthemes'),
			),
			array(
				'name' => __('Alternative font','yeahthemes'),
				'desc' => __('This option will apply to category meta and selector .alt-font ','yeahthemes'),
				'id' => 'typo_alt_font',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '11px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.25',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					// 'marginbottom' 		=> '10px',
					// 'color' 			=> ''
				),
				'type' => 'typography'
			),
			array(
				'name' => __('Sidebar widget\'s Headings','yeahthemes'),
				'desc' => __('default : 18px normal normal 1.75 0px none','yeahthemes'),
				'id' => 'typo_sidebar_widget_headings',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '18px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.75',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					//'marginbottom' 	=> '15px',
					//'color' 			=> ''
				),
				'type' => 'typography'
			),
		) ) );
		/**
		 * Typography - Elements - Footer
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_typography_elements_footer', array(
			array(
				'type' => 'tab',
				'name' =>  __('Footer','yeahthemes'),
			),
			array(
				'name' => __('Footer Headings','yeahthemes'),
				'desc' => __('default : 18px normal normal 1.75 0px none','yeahthemes'),
				'id' => 'typo_footer_headings',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '18px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.75',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					//'marginbottom' 	=> '15px',
					//'color' 			=> ''
				),
				'type' => 'typography'
			),
			array(
				'name' => __('Footer Elements','yeahthemes'),
				'desc' => __('Specify the footer element font properties Default: 13px normal normal 1.75 0px none','yeahthemes'),
				'id' => 'typo_footer_element',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '13px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.75',
					'letterspacing' 	=> '0px',
					'transform' 		=> 'none',
					//'color' 			=> ''
				),
				'type' => 'typography'
			),

			array(
				'name' => __('Footer Credit','yeahthemes'),
				'desc' => __('Specify the footer element font properties Default: 13px normal normal 1.75 0px none','yeahthemes'),
				'id' => 'typo_footer_credit',
				'std' => array(
					'face' 				=> '',
					'size' 				=> '12px',
					'weight' 			=> 'normal',
					'style' 			=> 'normal',
					'height' 			=> '1.75',
					'letterspacing' 	=> '2px',
					'transform' 		=> 'uppercase',
					// 'color' 			=> ''
				),
				'type' => 'typography'
			)
		) ) );
		/**
		 * Typography - Google Font
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_typography_googlefont', array(
		
			array( 
				'name' => __('Google Font','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Add, Remove font from <a href="http://www.google.com/webfonts/" target="_blank">Google Web Fonts</a> Library','yeahthemes'),
			),
			array( 
				'name' => '',
				'desc' => '',
				'std' => __('<p>Checkout <a href="http://www.google.com/webfonts/" target="_blank">Google Web Fonts</a> Library. for a complete list of available font.</p>','yeahthemes'),
				'type' => 'info'
			),
			array( 
				'name' => __('Enable Google font','yeahthemes'),
				'desc' => __('Click <strong>Enable</strong> to use Google webfont from <strong>Font Family</strong> select box','yeahthemes'),
				'id' => 'googlefont_mode',
				'std' => 'disable',
				'type' => 'toggles',
				'options' => $enable_disable
			),
			array( 
				'name' => __('Character sets','yeahthemes'),
				'desc' => __('If you choose only the languages that you need, you\'ll help prevent slowness on your webpage.<br><br>Notice: The theme will load font subsets if available!','yeahthemes'),
				'id' => 'googlefont_subsets',
				'type' => 'multicheck',
				'std' => array(),
				'options' => array(
					'latin' => __('Latin','yeahthemes'),
					'latin-ext' => __('Latin Extended','yeahthemes'),
					'cyrillic' => __('Cyrillic','yeahthemes'),
					'cyrillic-ext' => __('Cyrillic Extended','yeahthemes'),
					'greek' => __('Greek','yeahthemes'),
					'greek-ext' => __('Greek Extended','yeahthemes'),
					'khmer' => __('Khmer','yeahthemes'),
					'vietnamese' => __('Vietnamese','yeahthemes'),
				)
				
			)
		) ) );
		/**
		 * Typography - Custom Font
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_typography_customfont', array(
				
			array( 
				'name' => __('Custom Font','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Upload Custom font from your Computer','yeahthemes')
			),
			array( 
				'name' => '',
				'desc' => '',
				'std' => '<h3>Upload your custom font</h3><p>Remember to Refesh this page after hitting Save Changes so the Font family list will update the new fonts</p>',
				'type' => 'info'
			),
			array( 
				'name' => __('Enable Custom font','yeahthemes'),
				'desc' => __('Click <strong>Enable</strong> to use Google webfont from <strong>Font Family</strong> select box','yeahthemes'),
				'id' => 'customfont_mode',
				'std' => 'disable',
				'type' => 'toggles',
				'options' => $enable_disable
			),
			array( 
				'name' => __('Upload Custom Fonts.','yeahthemes'),
				'desc' => __('Add your custom font for your site.<br>If you don\'t know how to create a fontface for web, you can generate at: <a href="http://www.fontsquirrel.com/fontface/generator">here</a><br><br>Font You have added:<br>','yeahthemes'),
				'id' => 'custom_fontface',
				'std' => '',
				'type' => 'repeatable_field',
				'options' => array(
					array(
						'name' => __('Font name','yeahthemes'),
						'desc' => '',
						'id' => 'font_name',
						'std' => '',
						'type' => 'text'
					),
					array(
						'name' => __('Font weight','yeahthemes'),
						'desc' => '',
						'id' => 'font_weight',
						'std' => '400',
						'type' => 'select',
						'options' => array(
							'100'=> __('100 (Lighter)','yeahthemes'),
							'200'=>'200',
							'300'=>'300',
							'400'=>__('400 (Regular)','yeahthemes'),
							'500'=>'500',
							'600'=>'600',
							'700'=>__('700 (Bold)','yeahthemes'),
							'800'=>'800',
							'900'=>'900'
						)
					),
					array(
						'name' => __('Font style','yeahthemes'),
						'desc' => '',
						'id' => 'font_style',
						'std' => 'normal',
						'type' => 'select',
						'options' => array(
							'normal'=> __('Normal','yeahthemes'),
							'italic'=> __('Italic','yeahthemes')
						)
					),
					array(
						'name' => __('<b>.EOT</b> file','yeahthemes'),
						'desc' => '',
						'id' => 'font_eot',
						'std' => '',
						'type' => 'media',
						'settings' => array(
							'attr' => 'data-format="mixed"',
							'sanitize' => 'esc_url',
						)
					),
					array(
						'name' => __('<b>.WOFF</b> file','yeahthemes'),
						'desc' => '',
						'id' => 'font_woff',
						'std' => '',
						'type' => 'media',
						'settings' => array(
							'attr' => 'data-format="mixed"',
							'sanitize' => 'esc_url',
						)
					),
					array(
						'name' => __('<b>.TTF</b> file','yeahthemes'),
						'desc' => '',
						'id' => 'font_ttf',
						'std' => '',
						'type' => 'media',
						'settings' => array(
							'attr' => 'data-format="mixed"',
							'sanitize' => 'esc_url',
						)
					),
					array(
						'name' => __('<b>.SVG</b> file','yeahthemes'),
						'desc' => '',
						'id' => 'font_svg',
						'std' => '',
						'type' => 'media',
						'settings' => array(
							'attr' => 'data-format="mixed"',
							'sanitize' => 'esc_url',
						)
					),
				)
			
			),
			
		) ) );
		
		/**
		 * Styling Options
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_stylingoptions', array(	
			array( 
				'name' => __('Styling Options','yeahthemes'),
				'type' => 'heading',
				'customize' => 0,
				'settings' => array(
					'icon' => 'stylingoptions'
				)
			),
			array(
				'name' => '',
				'type' => 'info',
				'std' => __('<h3>Choose the built-in skins!, if you dont like them, you can customize by colorpicking your favorite color schemes :)</h3>','yeahthemes')
			),
			array(
				'name' => __('Built-in Skins','yeahthemes'),
				'desc' => __('Select your themes alternative color scheme','yeahthemes'),
				'id' => 'builtin_skins',
				'std' => '',
				'type' => 'color_blocks',
				'settings' =>  array(
					'width' => '30px', 
					'height' => '30px'
				),
				'options' => yt_get_option_vars( 'skins' ),
				
				'customize' => 0,
				
			),
			array(
				'type' => 'separator',
				'name' => __('Element colors','yeahthemes')
			),
			array(
				'name' => __('Primary color','yeahthemes'),
				'desc' => __('Pick a Primary color for your site.(this will overwrite built-in skins option)','yeahthemes'),
				'id' => 'primary_color',
				'std' => '',
				'type' => 'colorpicker',
				'customize' => 0,
			),
			array( 
				'name' => __('Secondary color','yeahthemes'),
				'desc' => __('Pick a Secondary color for your site ( This option applies to all heading tags color as well)','yeahthemes'),
				'id' => 'secondary_color',
				'std' => '',
				'type' => 'colorpicker',
				'customize' => 0,
			),
			array(
				'name' => __('Text color','yeahthemes'),
				'desc' => __('Pick a Text color for your site','yeahthemes'),
				'id' => 'text_color',
				'std' => '',
				'type' => 'colorpicker',
				'customize' => 0,
			),
			array( 
				'name' => __('Custom CSS','yeahthemes'),
				'desc' => __('Quickly add some CSS to your theme by adding it to this block.','yeahthemes'),
				'id' => 'custom_css',
				'std' => '/* Custom CSS*/',
				'type' => 'textarea',
				'class' => 'yt-widefat-section yt-tabifiable-textarea',
				'settings' => array(
					'rows' => '20',
					'sanitize' => false
				),
			),
			array(
				'type' => 'separator',
				'name' => __('Advanced styles','yeahthemes')
			),			
			array(
				'type' => 'tab',
				'name' => __('Body (Boxed & Fullwidth)','yeahthemes'),
			),
			array(
				'name' => __('Body Background options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => 'body_bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto',
					'color' => '',

					'image' => '',
				),
				'type' => 'background_options'
			),
			array(
				'type' => 'tab',
				'name' => __('Header','yeahthemes'),
			),
			array(
				'name' => __('Background image options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => 'header_bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto',
					'color' => '',
					'image' => '',
				),
				'type' => 'background_options',
			),
			array(
				'name' => __('Top Menu colors','yeahthemes'),
				'desc' => '',
				'id' => 'top_menu_colors',
				'std' => '',
				'type' => 'group_options',
				'options' => array(
					array(
						'name' => __('Menu Link Color','yeahthemes'),
						'desc' => __('Link color in top menu','yeahthemes'),
						'id' => 'link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Menu Link Hover Color','yeahthemes'),
						'desc' => __('Link hover state color in top menu','yeahthemes'),
						'id' => 'link_hover',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Menu Link Active Color','yeahthemes'),
						'desc' => __('Link hover state color in top menu','yeahthemes'),
						'id' => 'link_active',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Sub Menu Link Color','yeahthemes'),
						'desc' => __('Link color in top menu','yeahthemes'),
						'id' => 'sub_link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Sub Menu Link Hover Color','yeahthemes'),
						'desc' => __('Link hover state color in top menu','yeahthemes'),
						'id' => 'sub_link_hover',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Sub Menu Link Active Color','yeahthemes'),
						'desc' => __('Link hover state color in top menu','yeahthemes'),
						'id' => 'sub_link_active',
						'std' => '',
						'type' => 'colorpicker',
					),

				)
			),
			array(
				'name' => __('Main Menu colors','yeahthemes'),
				'desc' => '',
				'id' => 'main_menu_colors',
				'std' => '',
				'type' => 'group_options',
				'options' => array(
					array(
						'name' => __('Menu Link Color','yeahthemes'),
						'desc' => __('Link color in main menu','yeahthemes'),
						'id' => 'link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Menu Link Hover Color','yeahthemes'),
						'desc' => __('Link hover state color in main menu','yeahthemes'),
						'id' => 'link_hover',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Menu Link Active Color','yeahthemes'),
						'desc' => __('Link hover state color in main menu','yeahthemes'),
						'id' => 'link_active',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Sub Menu Link Color','yeahthemes'),
						'desc' => __('Link color in main menu','yeahthemes'),
						'id' => 'sub_link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Sub Menu Link Hover Color','yeahthemes'),
						'desc' => __('Link hover state color in main menu','yeahthemes'),
						'id' => 'sub_link_hover',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Sub Menu Link Active Color','yeahthemes'),
						'desc' => __('Link hover state color in main menu','yeahthemes'),
						'id' => 'sub_link_active',
						'std' => '',
						'type' => 'colorpicker',
					),

				)
			),
			array(
				'type' => 'tab',
				'name' => __('Content','yeahthemes'),
			),
			array(
				'name' => __('Background Options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => 'content_bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto',
					'color' => '',

					'image' => '',
				),
				'type' => 'background_options'
			),

			array(
				'name' => __('Content colors','yeahthemes'),
				'desc' => '',
				'id' => 'content_colors',
				'std' => '',
				'type' => 'group_options',
				'options' => array(
					array(
						'name' => __('Text color','yeahthemes'),
						'desc' => __('Text color in main content','yeahthemes'),
						'id' => 'text',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Link Color','yeahthemes'),
						'desc' => __('Regular link color in main content','yeahthemes'),
						'id' => 'link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Link Hover Color','yeahthemes'),
						'desc' => __('Regular link hover color in main content','yeahthemes'),
						'id' => 'link_hover',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Menu Link Color','yeahthemes'),
						'desc' => __('Menu Link color in main content ( applies to links inside li tag)','yeahthemes'),
						'id' => 'menu_link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Menu Link Hover Color','yeahthemes'),
						'desc' => __('Menu Link hover color in main content ( applies to links inside li tag)','yeahthemes'),
						'id' => 'menu_link_hover',
						'std' => '',
						'type' => 'colorpicker',
					)
					

				)
			),

			array(
				'name' => __('Sidebar colors','yeahthemes'),
				'desc' => '',
				'id' => 'sidebar_colors',
				'std' => '',
				'type' => 'group_options',
				'options' => array(
					array(
						'name' => __('Text color','yeahthemes'),
						'desc' => __('Text color in Sidebar','yeahthemes'),
						'id' => 'text',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Link Color','yeahthemes'),
						'desc' => __('Regular link color in Sidebar','yeahthemes'),
						'id' => 'link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Link Hover Color','yeahthemes'),
						'desc' => __('Regular link hover color in Sidebar','yeahthemes'),
						'id' => 'link_hover',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Menu Link Color','yeahthemes'),
						'desc' => __('Menu Link color in Sidebar ( applies to links inside li tag)','yeahthemes'),
						'id' => 'menu_link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Menu Link Hover Color','yeahthemes'),
						'desc' => __('Menu Link hover color in Sidebar ( applies to links inside li tag)','yeahthemes'),
						'id' => 'menu_link_hover',
						'std' => '',
						'type' => 'colorpicker',
					)
					

				)
			),
			array(
				'type' => 'tab',
				'name' => __('Footer','yeahthemes'),
			),
			array(
				'name' => __('Footer Widgets Background Options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => 'footer_widgets_bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto',
					'color' => '',
					
					'image' => '',
				),
				'type' => 'background_options',
			),
			array(
				'name' => __('Footer Widgets colors','yeahthemes'),
				'desc' => '',
				'id' => 'footer_widgets_colors',
				'std' => '',
				'type' => 'group_options',
				'options' => array(
					array(
						'name' => __('Text color','yeahthemes'),
						'desc' => __('Text color in Sidebar','yeahthemes'),
						'id' => 'text',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Link Color','yeahthemes'),
						'desc' => __('Regular link color in Sidebar','yeahthemes'),
						'id' => 'link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Link Hover Color','yeahthemes'),
						'desc' => __('Regular link hover color in Sidebar','yeahthemes'),
						'id' => 'link_hover',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Menu Link Color','yeahthemes'),
						'desc' => __('Menu Link color in Sidebar ( applies to links inside li tag)','yeahthemes'),
						'id' => 'menu_link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Menu Link Hover Color','yeahthemes'),
						'desc' => __('Menu Link hover color in Sidebar ( applies to links inside li tag)','yeahthemes'),
						'id' => 'menu_link_hover',
						'std' => '',
						'type' => 'colorpicker',
					)					

				)
			),
			array(
				'name' => __('Footer Credit Background Options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => 'footer_credit_bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto',
					'color' => '',
					
					'image' => '',
				),
				'type' => 'background_options',
			),
			array(
				'name' => __('Footer Credit colors','yeahthemes'),
				'desc' => '',
				'id' => 'footer_credit_colors',
				'std' => '',
				'type' => 'group_options',
				'options' => array(
					array(
						'name' => __('Text color','yeahthemes'),
						'desc' => __('Text color in Sidebar','yeahthemes'),
						'id' => 'text',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Link Color','yeahthemes'),
						'desc' => __('Regular link color in Sidebar','yeahthemes'),
						'id' => 'link',
						'std' => '',
						'type' => 'colorpicker',
					),
					array(
						'name' => __('Link Hover Color','yeahthemes'),
						'desc' => __('Regular link hover color in Sidebar','yeahthemes'),
						'id' => 'link_hover',
						'std' => '',
						'type' => 'colorpicker',
					),				

				)
			),
			// array(
			// 	'type' => 'tab',
			// 	'name' => __('Hero Banner','yeahthemes'),
			// ),
			// array(
			// 	'name' => __('Background image options','yeahthemes'),
			// 	'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
			// 	'id' => 'hero_bg_options',
			// 	'std' => array(
			// 		'repeat' => 'no-repeat',
			// 		'position' => 'center top',
			// 		'attachment' => 'local',
			// 		'size' => 'auto',
			// 		'color' => '',
			// 		'image' => '',
			// 	),
			// 	'type' => 'background_options',
			// ),
			array(
				'type' => 'tab',
				'name' => __('Mobile Menu','yeahthemes'),
			),
			array(
				'name' => __('Mobile Menu Background options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => 'mobi_menu_bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto',
					'color' => '',

					'image' => '',
				),
				'type' => 'background_options'
			),

		) ) );

							
		return $yt_options;
		
	}
}