<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Theme Styling
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeahthemes
 */
 /*
 * check if custom-styles.css is able to be written
 *
 * @since 1.0
 * @return string
 */

add_action( 'admin_init', 'yt_pre_check_custom_css_writable' );

function yt_pre_check_custom_css_writable() {


	//$access_type = get_filesystem_method();

	// custom css directory
	$the_dir = get_stylesheet_directory() . '/css';
	$external_dynamic_css = (bool) apply_filters( 'yt_site_dynamically_external_stylesheet', true );

	if( !file_exists( $the_dir) && $external_dynamic_css ){

		$msg = sprintf( '<div class="updated"><p>%s</p></div>', __( 'To use dynamically custom css from external file, make sure you have created "css" directory inside your child theme. ', 'yeahthemes') );
		add_action('admin_notices', create_function('', 'echo '. var_export( $msg, true ) . ';' ) );
	}
}
/*
 * Write to custom-styles.css
 *
 * @since 1.0
 * @return string
 */

if( !function_exists('yt_dynamic_options_css_writer')) {

	function yt_dynamic_options_css_writer( $new_data ) {

		
		/*Getting Credentials*/
		$url = wp_nonce_url('admin.php?page=yt-theme-options', 'yt-options-ajaxify-saving-data' );

		if (false === ($creds = request_filesystem_credentials($url, '', false, false, null) ) ) {
			return; // stop processing here
		}
		
		/*Initializing WP_Filesystem_Base*/
		if( ! WP_Filesystem( $creds ) ){
			
			request_filesystem_credentials($url, '', true, false, null);
			return true;
			
		}
	
		$uploads = wp_upload_dir();
		$css_dir = get_stylesheet_directory() . '/css/'; // Shorten code, save 1 call

		/* Save on different directory if on multisite */
		if( is_multisite() ) {
			$uploads_dir = trailingslashit( $uploads['basedir'] );
			
		} else {
			$uploads_dir = $css_dir;
		}
		
		$data = $new_data;
		$css = yt_site_get_dynamic_css( $data );
		
		/** Write to css file **/
		
		global $wp_filesystem, $sitepress;

		$lang = '';
		
		if( defined( 'ICL_LANGUAGE_CODE' ) && isset( $GLOBALS['sitepress'] ) && ICL_LANGUAGE_CODE !== $GLOBALS['sitepress']->get_default_language() ) {
	
			$lang = '_' . ICL_LANGUAGE_CODE;
			
		}

		$wrote = $wp_filesystem->put_contents( $uploads_dir . "custom-styles$lang.css", $css, FS_CHMOD_FILE );

		if( !$wrote ){
			//echo __('There\'s something wrong when writing css to file!, please make sure you have necessary permissions and css folder must be existed', 'yeahthemes');

			file_put_contents( $uploads_dir . "custom-styles$lang.css", $css );
		} 
	}
}

/*
 * Init dynamic css based on selected type
 *
 * @since 1.0
 * @return string
 */
add_action( 'init', 'yt_site_custom_styles', 9 );

if( !function_exists( 'yt_site_custom_styles') ) {
	function yt_site_custom_styles(){

		$external_dynamic_css = (bool) apply_filters( 'yt_site_dynamically_external_stylesheet', true );

		// if use external file, enqueue it.
		if( $external_dynamic_css ){

			if( yt_get_options( '_init_options_time' ) ){
		
				add_action( 'ytto_after_option_data_saved','yt_dynamic_options_css_writer', 10);
			}

			add_action( 'yt_theme_scripts_after_enqueue_styles', create_function( '', 'wp_enqueue_style( \'custom-styles\' );') );
		}
		// Print css
		else{
			add_action( 'wp_head', create_function( '', 'echo \'<!-- noptimize --><!--CUSTOM STYLE--><style type="text/css" id="custom-styles">\' . yt_site_get_dynamic_css() . \'</style><!--/CUSTOM STYLE--><!-- /noptimize -->\';'), 15);
		}
	}
}
/*
 * Dynamically genetate css using less css with pre-defined styles + theme options data
 *
 * @since 1.0
 * @return string
 */
if( !function_exists( 'yt_site_get_dynamic_css') ) {

	function yt_site_get_dynamic_css( $data = array() ){

		$lang = '';
		
		if( defined( 'ICL_LANGUAGE_CODE' ) && isset( $GLOBALS['sitepress'] ) && ICL_LANGUAGE_CODE !== $GLOBALS['sitepress']->get_default_language() ) {
	
			$lang = '_' . ICL_LANGUAGE_CODE;
			
		}
		
		$transient_name = 'yt_custom_styles' . $lang;

		if ( false !== ( $results = get_transient( $transient_name ) ) )
			return $results;
	
		$yt_data = empty( $data ) ? yt_get_options() : $data;
		// Append to custom css
		$dirUri 		= get_template_directory_uri();
		$primaryColor 	= $yt_data['primary_color'] ? $yt_data['primary_color'] : ( $yt_data['builtin_skins'] ? $yt_data['builtin_skins'] : '#8ec466');
		$primaryColor 	= apply_filters( 'yt_site_styling_primary_color', $primaryColor );
		$secondaryColor = $yt_data['secondary_color'] ? $yt_data['secondary_color'] : '#222';
		$textColor 		= $yt_data['text_color'] ? $yt_data['text_color'] : '#777777';

		$less_vars = apply_filters('yt_site_less_css_variables', array(

			'@textColor' 					=> $textColor,
			'@boldColor' 					=> '#333333',
			'@grayColor' 					=> '#aaaaaa',
			'@grayBgColor'					=> '#fafafa',
			'@whiteColor'					=> '#ffffff',
			'@blackColor'					=> '#000000',
			'@borderOpacity' 				=> '0.08',
			'@borderRadius' 				=> '1px',
			'@baseFontSize'					=> !empty( $yt_data['typo_body_font']['size'] ) ? $yt_data['typo_body_font']['size'] : '14px',
			'@iconFonts'					=> '"FontAwesome"',
			'@imageURI'						=> '"' .$dirUri .'"',

			'@padRight'						=> '20px',
			'@padLeft'						=> '20px',
			'@containerW'					=> '970px',
			'@largerContainerW'				=> '970px', //1370px
			'@sidebarW'						=> !empty( $yt_data['sidebar_width'] ) ? "{$yt_data['sidebar_width']}px" : '300px',

			// =Colors
			'@defaultColor'					=> '#fafafa',
			'@primaryColor' 				=> $primaryColor,
			'@secondaryColor' 				=> $secondaryColor,
			'@successColor'        			=> '#5cb85c',
			'@infoColor'            		=> '#5bc0de',
			'@warningColor'         		=> '#f0ad4e',
			'@dangerColor'          		=> '#d9534f',

			// =Body
			'@bodyTextColor' 				=> '@textColor',
			'@linkColor' 					=> '@secondaryColor',
			'@linkHoverColor' 				=> '@primaryColor',
			'@boldTextColor' 				=> '@secondaryColor',
			'@grayBorderColor' 				=> 'fade(@secondaryColor, 10%)',
			'@grayBorderColorTransparent'	=> 'fade(@secondaryColor, 8%)',

			'@mobileTextColor' 				=> '@grayColor', 
			'@mobileLinkColor' 				=> '@grayColor', 
			'@mobileLinkHoverColor' 		=> '@whiteColor', 
			'@mobileMenuLinkColor' 			=> '@grayColor', 
			'@mobileMenuLinkHoverColor' 	=> '@whiteColor',
			'@mobileMenuLinkActiveColor' 	=> '@primaryColor',	
			'@mobileSubMenuLinkColor' 	 	=> '@grayColor',
			'@mobileSubMenuLinkHoverColor' 	=> '@whiteColor',
			'@mobileSubMenuLinkActiveColor' => '@primaryColor',		

			// =Header
			'@topMenuLinkColor' 			=> !empty( $yt_data['top_menu_colors']['link'] ) ? $yt_data['top_menu_colors']['link'] : '@grayColor', 
			'@topMenuLinkHoverColor' 		=> !empty( $yt_data['top_menu_colors']['link_hover'] ) ? $yt_data['top_menu_colors']['link_hover'] : '@primaryColor',
			'@topMenuLinkActiveColor' 		=> !empty( $yt_data['top_menu_colors']['link_active'] ) ? $yt_data['top_menu_colors']['link_active'] : '@secondaryColor',
			// Sub Menu
			'@topSubMenuLinkColor' 	 		=> !empty( $yt_data['top_menu_colors']['sub_link'] ) ? $yt_data['top_menu_colors']['sub_link'] : '@grayColor',
			'@topSubMenuLinkHoverColor' 	=> !empty( $yt_data['top_menu_colors']['sub_link_hover'] ) ? $yt_data['top_menu_colors']['sub_link_hover'] : '@secondaryColor',
			'@topSubMenuLinkActiveColor' 	=> !empty( $yt_data['top_menu_colors']['sub_link_active'] ) ? $yt_data['top_menu_colors']['sub_link_active'] : '@primaryColor',
			
			// Menu
			'@mainMenuLinkColor' 			=> !empty( $yt_data['main_menu_colors']['link'] ) ? $yt_data['main_menu_colors']['link'] : '@secondaryColor', 
			'@mainMenuLinkHoverColor' 		=> !empty( $yt_data['main_menu_colors']['link_hover'] ) ? $yt_data['main_menu_colors']['link_hover'] : '@primaryColor',
			'@mainMenuLinkActiveColor' 		=> !empty( $yt_data['main_menu_colors']['link_active'] ) ? $yt_data['main_menu_colors']['link_active'] : '@primaryColor',

			// Sub Menu
			'@mainSubMenuLinkColor' 	 	=> !empty( $yt_data['main_menu_colors']['sub_link'] ) ? $yt_data['main_menu_colors']['sub_link'] : '@textColor',
			'@mainSubMenuLinkHoverColor' 	=> !empty( $yt_data['main_menu_colors']['sub_link_hover'] ) ? $yt_data['main_menu_colors']['sub_link_hover'] : '@primaryColor',
			'@mainSubMenuLinkActiveColor' 	=> !empty( $yt_data['main_menu_colors']['sub_link_active'] ) ? $yt_data['main_menu_colors']['sub_link_active'] : '@primaryColor',

			// =Content
			'@contentLinkColor' 			=> !empty( $yt_data['content_colors']['link'] ) ? $yt_data['content_colors']['link'] : '@primaryColor',
			'@contentLinkHoverColor'		=> !empty( $yt_data['content_colors']['link_hover'] ) ? $yt_data['content_colors']['link_hover'] : '@secondaryColor',
			'@contentMenuLinkColor' 		=> !empty( $yt_data['content_colors']['menu_link'] ) ? $yt_data['content_colors']['menu_link'] : '@secondaryColor',
			'@contentMenuLinkHoverColor'	=> !empty( $yt_data['content_colors']['menu_link_hover'] ) ? $yt_data['content_colors']['menu_link_hover'] : '@primaryColor',
			'@contentMenuLinkActiveColor'	=> '@primaryColor',
			'@contentTextColor'				=> !empty( $yt_data['content_colors']['text'] ) ? $yt_data['content_colors']['text'] : '@textColor',

			// =Sidebar
			'@sidebarTextColor' 			=> !empty( $yt_data['sidebar_colors']['text'] ) ? $yt_data['sidebar_colors']['text'] : '@textColor',
			'@sidebarLinkColor' 			=> !empty( $yt_data['sidebar_colors']['link'] ) ? $yt_data['sidebar_colors']['link'] : '@primaryColor',
			'@sidebarLinkHoverColor' 		=> !empty( $yt_data['sidebar_colors']['link_hover'] ) ? $yt_data['sidebar_colors']['link_hover'] : '@secondaryColor',
			'@sidebarMenuLinkColor' 		=> !empty( $yt_data['sidebar_colors']['menu_link'] ) ? $yt_data['sidebar_colors']['menu_link'] : '@secondaryColor',
			'@sidebarMenuLinkHoverColor' 	=> !empty( $yt_data['sidebar_colors']['menu_link_hover'] ) ? $yt_data['sidebar_colors']['menu_link_hover'] : '@primaryColor',
			'@sidebarMenuLinkActiveColor' 	=> '@primaryColor',

			// =Footer
			// Footer widgets
			'@footerWidgetTextColor' 			=> !empty( $yt_data['footer_widgets_colors']['text'] ) ? $yt_data['footer_widgets_colors']['text'] : '@textColor',
			'@footerWidgetLinkColor' 			=> !empty( $yt_data['footer_widgets_colors']['link'] ) ? $yt_data['footer_widgets_colors']['link'] : '@primaryColor',
			'@footerWidgetLinkHoverColor' 		=> !empty( $yt_data['footer_widgets_colors']['link_hover'] ) ? $yt_data['footer_widgets_colors']['link_hover'] : '@secondaryColor',
			'@footerWidgetMenuLinkColor' 		=> !empty( $yt_data['footer_widgets_colors']['menu_link'] ) ? $yt_data['footer_widgets_colors']['menu_link'] : '@secondaryColor',
			'@footerWidgetMenuLinkHoverColor' 	=> !empty( $yt_data['footer_widgets_colors']['menu_link_hover'] ) ? $yt_data['footer_widgets_colors']['menu_link_hover'] : '@primaryColor',

			// Footer Credit
			'@footerCreditTextColor' 			=> !empty( $yt_data['footer_credit_colors']['text'] ) ? $yt_data['footer_credit_colors']['text'] : '@grayColor',
			'@footerCreditLinkColor' 			=> !empty( $yt_data['footer_credit_colors']['link'] ) ? $yt_data['footer_credit_colors']['link'] : '@primaryColor',
			'@footerCreditLinkHoverColor' 		=> !empty( $yt_data['footer_credit_colors']['link_hover'] ) ? $yt_data['footer_credit_colors']['link_hover'] : '@whiteColor',
		) );

		//print_r( $less_css_vars );die();
		// Join the variable array
		$less = '';
		foreach ($less_vars as $key => $value) {
			$less .= "$key:$value;\n";
		}

		// Allow other plugin could use less var
		do_action( 'yt_site_get_dynamic_css_less_vars', $less );

		$less .= @yt_file_get_contents( get_template_directory() . '/framework/css/mixins.less', true );
		$less .= @yt_file_get_contents( get_template_directory() . '/css/theme-mixins.less', true );
		$less .= @yt_file_get_contents( get_template_directory() . '/css/bootstrap-theme.less', true );



		//return $less;
		$custom_css = yt_get_theme_googlefonts('import') . yt_get_theme_customfonts();

		// Custom Font
		
		$custom_css .= $less;

		// Headings
		$custom_css .= 'h1{'. yt_output_option( 'typography', $yt_data['typo_h1'] ) . '}' . "\n";
		$custom_css .= 'h2{' . yt_output_option( 'typography', $yt_data['typo_h2'] ) . '}' . "\n";
		$custom_css .= 'h3{' . yt_output_option( 'typography', $yt_data['typo_h3'] ) . '}' . "\n";
		$custom_css .= 'h4{' . yt_output_option( 'typography', $yt_data['typo_h4'] ) . '}' . "\n";
		$custom_css .= 'h5{' . yt_output_option( 'typography', $yt_data['typo_h5'] ) . '}' . "\n";
		$custom_css .= 'h6{' . yt_output_option( 'typography', $yt_data['typo_h6'] ) . '}' . "\n";
		
		$custom_css .= 'h1, h2, h3, h4, h5, h6{';
			$custom_css .= 'strong, b{';
				$custom_css .= yt_output_option( 'typography', $yt_data['typo_bold_heading'] );
			$custom_css .= '}';	
		$custom_css .= '}' . "\n";

		// Alt Font
		$custom_css .= '.alt-font{';
			$custom_css .= yt_output_option( 'typography', $yt_data['typo_alt_font'] );
		$custom_css .= '}' . "\n";

		// Body
		$custom_css .= 'body{';
			$custom_css .= 'color:@bodyTextColor;';
			$custom_css .= yt_output_option( 'typography', $yt_data['typo_body_font'] );			
			$custom_css .= yt_output_option( 'background_options', $yt_data['body_bg_options'] ) ;
		$custom_css .= '}' . "\n";

		// Layout
		$custom_css .= 'p{
			-webkit-font-smoothing: antialiased;
		}
		a{
			&:focus, &:visited{
				text-decoration: none;
			}
			transition: color linear .2s;
			color: @linkColor;
			&:hover{
				color: @linkHoverColor
			}
		}
		.site-top-bar,
		.main-navigation,
		.widget.border-box,
		.site-main,
		.comment-body:before{
			border-color: @grayBorderColor;
		}
		@media (min-width: 992px) {
			.layout-default, 
			.layout-left-sidebar, 
			.layout-right-sidebar{
				.content-area{
					// width: 970px - ( 300px + ( 20px + 20px + 15px  + 15px  ) );
					width: @containerW - ( @sidebarW + ( ( @padLeft + @padRight ) * 2 ) - 10px );
				}
				.widget-area{
					width: @sidebarW;		
				}
			}
		}
		@media (min-width: 1200px) {
			.container{
				padding-left: @padLeft;
				padding-right: @padRight;
				width: @largerContainerW
			}
			body.boxed-layout{
				.inner-wrapper, .sticky-main-nav-wrapper{
					width: @largerContainerW + @padLeft + @padRight;
				}
			}
			.site-hero{
				&.layout-blocks-alt{
					article{
						.entry-thumbnail{
							width: @sidebarW;
							height: 250px;
						}
						&.block-1{
							width: @largerContainerW - ( @sidebarW + ( ( @padLeft + @padRight ) * 2 ) );
							.entry-thumbnail{
								height: 540px;
							}
						}

					}
				}
			}
			.layout-default, .layout-left-sidebar, .layout-right-sidebar{
				.content-area{
					width: @largerContainerW - ( @sidebarW + ( ( @padLeft + @padRight ) * 2 ) );
					// width: @largerContainerW - ( 300px + ( ( 20px + 20px ) * 2 ) );
				}
				.widget-area{
					width: @sidebarW;	
				}
			}
		}

		.single .site-content > article.hentry > .entry-content,
		.site-content > article.hentry,
		.site-content > .direction-nav,
		.site-content > .pagination-nav,
		.site-content > .you-might-also-like-articles,
		.site-content > .comments-area,
		.site-content > .post-author-area,
		.site-content > .entry-stuff,
		.archive-title{
			border-bottom: 1px solid @grayBorderColor;
			padding-bottom: 40px;
			margin-bottom: 40px;
		}';
	
		// Primary color
		$custom_css .= '
		.active>a,
		.primary-color,
		.primary-2-secondary a,
		.secondary-2-primary a:hover,
		.secondary-2-primary .active > a,
		.secondary-2-primary .current,
		.gray-2-primary:hover,
		.gray-2-primary a:hover,
		.gray-2-primary .active > a,
		.gray-2-primary .current,
		.site-footer .footer-social-media-links a:hover,
		#main-mobile-menu .current-menu-item>a,
		.yeahslider-active-slide .slide-num{
		
			color: @primaryColor;
		}';

		// White Color
		$custom_css .= '.primary-bg-color{
			background-color: @primaryColor;
			color: @whiteColor;
			a{
				color: @whiteColor;
			}
		}
		.post-list-with-thumbnail li .post-thumb .cat-tag,
		#main-mobile-menu li:hover>a,
		#main-mobile-menu .site-title a{
			color: @whiteColor;

		}';

		// Secondary
		$custom_css .= '
		h1, h2, h3, h4, h5, h6, b, strong,
		.secondary-color,
		.secondary-2-primary a,
		.primary-2-secondary a:hover,
		.primary-2-secondary .active > a,
		.primary-2-secondary .current,

		.gray-2-secondary:hover,
		.gray-2-secondary a:hover,
		.gray-2-secondary .active > a,
		.gray-2-secondary .current,
		.site-title a,
		.social-share-buttons li:hover i,
		.pagination-meta{
			color: @secondaryColor; 
		}';

		// Gray color
		$custom_css .= '.gray-color,
		.gray-2-secondary a,
		.gray-2-primary,
		.gray-2-primary a,
		.gray-icon,
		.gray-icon i,
		.site-description,
		.social-share-buttons span.counter,
		.pager-heading,
		.site-footer .footer-social-media-links a{
			color: @grayColor;
		}';

		//Background Color
		$custom_css .= '.post-list-with-thumbnail li .post-thumb .cat-tag,
		html .mejs-controls .mejs-time-rail .mejs-time-current{
			background-color: @primaryColor;
		}
		nav.direction-nav .nav-previous .post-nav-icon,
		nav.direction-nav .nav-next .post-nav-icon{
			background-color: @secondaryColor; 
		}';

		// Border color
		$custom_css .= '.form-control:focus{
			.box-shadow(none);
		}';

		// Mobile menu nav
		$custom_css .= '.mobile-navigation{
			color: @mobileTextColor;
			a{
				color: @mobileLinkColor;
				&:hover{
					color: @mobileLinkHoverColor;
				}
			}
			';
			$custom_css .= yt_output_option( 'background_options', $yt_data['mobi_menu_bg_options'] );
			$custom_css .= '.menu{';
				
				$custom_css .= 'li{
					&:hover > a{
						color: @mobileMenuLinkHoverColor;
					}
					a{
						color: @mobileMenuLinkColor;
						
					}

					&.current-menu-item, &.current-menu-parent{
						> a{
							color: @mobileMenuLinkActiveColor;
						}
					}
					&.menu-item-has-children{
						> a .menu-item-indicator{
							color: @primaryColor;
						}
					}
					ul li{
						a{
							color: @mobileSubMenuLinkColor;
							&:hover{
								color: @mobileSubMenuLinkHoverColor;
							}
						}
						&.current-menu-item, &.current-menu-parent{
							> a{
								color: @mobileSubMenuLinkActiveColor;
							}
						}
					}
				}
			}';
		$custom_css .= '}';

		// Banner
		$custom_css .= '.site-banner{';
			$custom_css .= yt_output_option( 'background_options', $yt_data['header_bg_options'] )  . "\n" ;
		$custom_css .= '}';

		// Top bar
		$custom_css .= '.site-top-bar{';
			$custom_css .= yt_output_option( 'typography', $yt_data['typo_top_bar'] )  . "\n" ;
		$custom_css .= '}';

		// top navigation
		$custom_css .= '.top-navigation .menu{';
			
			$custom_css .= 'li{
				&:hover > a{
					color: @topMenuLinkHoverColor;
				}
				a{
					color: @topMenuLinkColor;
					
				}

				&.current-menu-item, &.current-menu-parent{
					> a{
						color: @topMenuLinkActiveColor;
					}
				}
				&.menu-item-has-children{
					> a .menu-item-indicator{
						color: @primaryColor;
					}
				}
				ul li{
					a{
						color: @topSubMenuLinkColor;
						&:hover{
							color: @topSubMenuLinkHoverColor;
						}
					}
					&.current-menu-item, &.current-menu-parent{
						> a{
							color: @topSubMenuLinkActiveColor;
						}
					}
				}
			}
		}';

		// Main nav
		$custom_css .= '.main-navigation .menu{';
			
			$custom_css .= 'li.menu-item{
				&:hover > a{
					color: @mainMenuLinkHoverColor;
				}';
				$custom_css .= yt_output_option( 'typography', $yt_data['typo_main_menu'] )  . "\n" ;
				$custom_css .= 'a{
					color: @mainMenuLinkColor;
					
				}

				&.current-menu-item, &.current-menu-parent{
					> a{
						color: @mainMenuLinkActiveColor;
					}
				}
				&.menu-item-has-children{
					> a .menu-item-indicator{
						color: @primaryColor;
					}
				}
				ul li{'; 
					$custom_css .= yt_output_option( 'typography', $yt_data['typo_main_menu_child'] )  . "\n" ;
					$custom_css .= 'a{
						color: @mainSubMenuLinkColor;
						&:hover{
							color: @mainSubMenuLinkHoverColor;
						}
					}
					&.current-menu-item, &.current-menu-parent{
						> a{
							color: @mainSubMenuLinkActiveColor;
						}
					}
				}
			}
		}';


		// Content
		$custom_css .= '.site-main{ ';
			$custom_css .= yt_output_option( 'background_options', $yt_data['content_bg_options'] );
		$custom_css .= '}';

		$custom_css .= '.entry-content{
			color: @contentTextColor;
			a{
				color:@contentLinkColor;
				&:hover{
					color: @contentLinkHoverColor;
				}
			}
			li a{
				color: @contentMenuLinkColor;
				&:hover{
					color: @contentMenuLinkHoverColor;
				}
			}
		}';
		
		// Widget
		$custom_css .= '.widget-area{

			color: @sidebarTextColor;
			a{
				color: @sidebarLinkColor;
				&:hover{
					color: @sidebarLinkHoverColor;
				}
			}
			li{

				a{
					color: @sidebarMenuLinkColor;
					&:hover{
						color: @sidebarMenuLinkHoverColor;
					}
				} 
				&.active a{
					
					color: @sidebarMenuLinkActiveColor;
				}

			}';
			// Widget title
			$custom_css .= '.widget-title{';
				$custom_css .= yt_output_option( 'typography', $yt_data['typo_sidebar_widget_headings'] );
			$custom_css .= '}';
		$custom_css .= '}' . "\n";

		// Footer Widget
		$custom_css .= '.footer-widgets{
			color: @footerWidgetTextColor;';
			$custom_css .= yt_output_option( 'typography', $yt_data['typo_footer_element'] ) ;
			$custom_css .= yt_output_option( 'background_options', $yt_data['footer_widgets_bg_options'] );
			// Widget title
			$custom_css .= '.widget-title{';
				$custom_css .= yt_output_option( 'typography', $yt_data['typo_footer_headings'] );
			$custom_css .= '}';

			$custom_css .= 'a{
				color: @footerWidgetLinkColor;
				&:hover{
					color: @footerWidgetLinkHoverColor;
				}
			}
			li a{
				color: @footerWidgetMenuLinkColor;
				&:hover{
					color: @footerWidgetMenuLinkHoverColor;
				}
			}';
		$custom_css .= '}';

		// Footer Credits
		$custom_css .= '.footer-info{
			color: @footerCreditTextColor;';
			$custom_css .= yt_output_option( 'typography', $yt_data['typo_footer_credit'] );
			$custom_css .= yt_output_option( 'background_options', $yt_data['footer_credit_bg_options'] );
			$custom_css .= 'a{
				color: @footerCreditLinkColor;
				&:hover{
					color: @footerCreditLinkHoverColor;
				}
			}';
		$custom_css .= '}';

		if( class_exists( 'woocommerce') ){
			$custom_css .= '
			html .woocommerce,html .woocommerce-page {
				.onsale, 
				.ui-slider-handle,
				.ui-slider-range{
					background-color:@primaryColor !important;
					color: #FFF !important;
				}
				.price, .amount{
					color: @primaryColor !important;
					
				}

				del{
					.price, .amount{
						color: @grayColor !important;
						
					}
				}
			}
			';
			$custom_css .= '.woocommerce h2{ 
				font-size: 24px;
			}';

		}

		// Custom CSS
		$custom_css .= $yt_data['custom_css'];

		$less_css = yt_lesscss_compiler( $custom_css, true );			
		
		$custom_css = apply_filters( 'yt_site_dynamic_css', $less_css );

		// $custom_css = str_replace(array("\r", "\n", "\t"), "", $custom_css);
		set_transient( $transient_name, $custom_css );
		//$custom_css = '';
		return $custom_css;

		// wp_add_inline_style( 'yt-custom-styles', $custom_css );

	}
}

add_filter( 'yt_option_vars_skins', 'yt_site_default_accent_color' );
/*
 * Add default skin for variable skins
 *
 * @since 1.0
 * @return array
 */
function yt_site_default_accent_color( $skins ){

	$skins = array_merge( array( '#000000' => 'default' ), $skins );
	return $skins;
}
/*
 * Remove transients after updating theme options
 *
 * @since 1.0
 * @return string
 */
add_filter( 'yt_refresh_transient_list_after_updating_theme_options', 'yt_site_flush_styling_transient' );

function yt_site_flush_styling_transient( $list ){
	$lang = '';	
	if( defined( 'ICL_LANGUAGE_CODE' ) && isset( $GLOBALS['sitepress'] ) && ICL_LANGUAGE_CODE !== $GLOBALS['sitepress']->get_default_language() ) {

		$lang = '_' . ICL_LANGUAGE_CODE;
		
	}
	$list[] = 'yt_custom_styles' . $lang;
	$list[] = 'yt_theme_less_css';
	$list[] = 'yt_theme_compiled_less_css';
	return $list;
}