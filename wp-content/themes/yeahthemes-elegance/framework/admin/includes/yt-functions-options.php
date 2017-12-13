<?php
/* This file is not called from WordPress. We don't like that. */
! defined( 'ABSPATH' ) and exit;

if( is_admin() || isset( $GLOBALS['wp_customize'] ) )
	add_action('init','yt_base_options', 2);

if ( !function_exists( 'yt_base_options' ) ) {
	
	function yt_base_options(){
		
		$yt_data = yt_get_options();
		
		/**
		 * Global vars
		 */
		
		$on_off = array(
			'on' => __('ON', 'yeahthemes'), 
			'off' => __('OFF', 'yeahthemes')
		);
		$show_hide = array(
			'show' => __('Show', 'yeahthemes'), 
			'hide' => __('Hide', 'yeahthemes')
		);
		/* Theme Skin */
		$skins = yt_get_option_vars( 'skins' );
		
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
		/*-----------------------------------------------------------------------------------*/
		/* The Options Array */
		/*-----------------------------------------------------------------------------------*/
		
		// Set the Options Array
		global $yt_options;
		
		$yt_options = array();		
		
		//===========================================================================
		//Locate the functions from file
		locate_template( '/includes/theme-options.php', true) ; 

		//If function exist, get the options
		if( function_exists( 'yt_theme_options' ) ){
			$yt_options_extended =& $yt_options;
			$yt_options_extended = yt_theme_options();
		}
		
		$yt_options = apply_filters( 'yt_theme_options', $yt_options );
		
		/**
		 * Option: Subscribe & Connect
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_subscribeconnect', array(	
			array( 
				'name' => __('Subscribe & Connect','yeahthemes'),
				'type' => 'heading',
				'settings' => array(
					'icon' => 'subscribeconnect'
				)
			
			)
		) ) );
		/**
		 * Subscribe & Connect - Subscribe
		 */
		 
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_subscribeconnect_api', array(	
		
			array( 
				'name' => __('API','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Configure Subscription API','yeahthemes')
			
			),
			array( 
				'name' => __('MailChimp API key','yeahthemes'),
				'desc' => __('Get your API at <a href="http://admin.mailchimp.com/account/api-key-popup" target="_blank">link</a>','yeahthemes'),
				'id' => 'mailchimp_api',
				'std' => '',
				'type' => 'text'
			),
			array( 
				'name' => __('Google API key','yeahthemes'),
				'desc' => __('Get your API at <a href="https://code.google.com/apis/console?hl=en#access" target="_blank">link</a>','yeahthemes'),
				'id' => 'google_api',
				'std' => '',
				'type' => 'text'
			
			),		
			array( 
				'name' => __('Twitter API key','yeahthemes'),
				'type' => 'separator',
				'desc' => __('Your application\'s OAuth settings','yeahthemes'),
			),
			array(
				'name' => '',
				'std' => __('<h3>Create your application and copy the Consumer key & Access token at <a href="https://dev.twitter.com/apps/">Twitter API</a></h3>','yeahthemes'),
				'type' => 'info'
			),
			array(
				'name' => __('Consumer key','yeahthemes'),
				'desc' => __('Enter your Consumer key from <strong>OAuth settings</strong>.','yeahthemes'),
				'id' => 'twitter_consumer_key',
				'std' => '',
				'type' => 'text'
			),
			array(
				'name' => __('Consumer secret','yeahthemes'),
				'desc' => __('Enter your Consumer secret from <strong>OAuth settings</strong>.','yeahthemes'),
				'id' => 'twitter_consumer_secret',
				'std' => '',
				'type' => 'text'
			),
			array(
				'name' => __('Access token','yeahthemes'),
				'desc' => __('Enter your Access token from <strong>Your access token</strong>.','yeahthemes'),
				'id' => 'twitter_access_token',
				'std' => '',
				'type' => 'text'
			),
			array( 
				'name' => __('Access token secret','yeahthemes'),
				'desc' => __('Enter your Access token secret from <strong>Your access token</strong>.','yeahthemes'),
				'id' => 'twitter_access_token_secret',
				'std' => '',
				'type' => 'text'
			)
		) ) );
		/**
		 * Subscribe & Connect - Connect
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_subscribeconnect_connect', array(				
			array( 
				'name' => __('Connect','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Add your Social Networks URLs','yeahthemes')
			),
			array( 
				'name' => __('RSS','yeahthemes'),
				'desc' => __('Add your RSS url. (default is http://example.com/feed)','yeahthemes'),
				'id' => 'scl_rss',
				'std' => get_bloginfo('rss2_url'),
				'type' => 'text',
			),
			array( 
				'name' => __('Email Adress','yeahthemes'),
				'desc' => __('Add an Email address.<br>Eg:your.email@domain.com','yeahthemes'),
				'id' => 'scl_email',
				'std' => '',
				'type' => 'text',
			),
			array( 
				'name' => __('Facebook','yeahthemes'),
				'desc' => __('Add your Facebook url.<br>http://www.facebook.com/username','yeahthemes'),
				'id' => 'scl_facebook',
				'std' => '#',
				'type' => 'text',
			),
			array( 
				'name' => __('Twitter','yeahthemes'),
				'desc' => __('Add your Twitter url.<br>https://twitter.com/username','yeahthemes'),
				'id' => 'scl_twitter',
				'std' => '#',
				'type' => 'text',
			),
			array( 
				'name' => __('Google+','yeahthemes'),
				'desc' => __('Add your Google+ url.<br>http://plus.google.com/userID','yeahthemes'),
				'id' => 'scl_googleplus',
				'std' => '#',
				'type' => 'text',
			),
			array( 
				'name' => __('Youtube','yeahthemes'),
				'desc' => __('Add your Youtube url.<br>http://www.youtube.com/user/username','yeahthemes'),
				'id' => 'scl_youtube',
				'std' => '#',
				'type' => 'text',
			),
			array( 
				'name' => __('Vimeo','yeahthemes'),
				'desc' => __('Add your Vimeo url.<br>http://vimeo.com/username','yeahthemes'),
				'id' => 'scl_vimeo',
				'std' => '#',
				'type' => 'text',
			),
			array( 
				'name' => __('Dribbble','yeahthemes'),
				'desc' => __('Add your Dribbble url.<br>http://dribbble.com/username','yeahthemes'),
				'id' => 'scl_dribbble',
				'std' => '#',
				'type' => 'text',
			),
			array( 
				'name' => __('Instagram','yeahthemes'),
				'desc' => __('Add your Instagram url.<br>http://instagram.com/username','yeahthemes'),
				'id' => 'scl_instagram',
				'std' => '#',
				'type' => 'text',
			),
			array( 
				'name' => __('Pinterest','yeahthemes'),
				'desc' => __('Add your Pinterest url.<br>http://pinterest.com/username','yeahthemes'),
				'id' => 'scl_pinterest',
				'std' => '#',
				'type' => 'text',
			),
		
		) ) );

		/**
		 * Advanced Setting
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_advancedsettings', array(
			array( 
				'name' => __('Advanced Settings','yeahthemes'),
				'type' => 'heading',
				'settings' => array(
					'icon' => 'advancedsettings'
				)
			)
		) ) );
		/**
		 * Advanced Setting - Icons.
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_advancedsettings_icons', array(
			array( 
				'name' => __('Icons','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Add favicon ,Apple/Windows icons','yeahthemes'),
			),
			array( 
				'name' 		=> __( 'Site Icons', 'yeahthemes' ),
				'desc'		=> '',
				'id' 		=> 'site_icons',
				'std' 		=> sprintf( __('Oops, This section has been deprecated and moved to Appearance -> Customizer -> Site Identity.<br>Please <a href="%s">click here</a> to reconfig.', 'yeahthemes'), esc_url( add_query_arg(
					array(
							array( 'autofocus' => array( 'section' => 'title_tagline' ) ),
							'return' => urlencode( wp_unslash( $_SERVER['REQUEST_URI'] ) )
						),
						admin_url( 'customize.php' )
					) )
				),
				'type' 		=> 'html'
			),
			
		) ) );
		
		/**
		 * Advanced Settings - Login Area.
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_advancedsettings_loginarea', array(
			array( 
				'name' => __('Login Area','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Styling your Admin Login area','yeahthemes'),
			),
			array( 
				'name' => __('Admin Login Logo','yeahthemes'),
				'desc' => __('Upload a custom logo for admin login page. Dimension: 320px*100px, Retina: 640px*200px','yeahthemes'),
				'id' => 'login_logo',
				'std' => '',
				'type' => 'media'
			),
			array( 
				'name' => __('Link Color','yeahthemes'),
				'desc' => __('This color will be used for links','yeahthemes'),
				'id' => 'login_link_color',
				'std' => '',
				'type' => 'colorpicker'
			),
			array( 
				'name' => __('Background options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => 'login_bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto', 
					'color' => '',
					'image' => ''
				),
				'type' => 'background_options'
			),
		
		) ) );
		
		/**
		 * Advanced Settings - Maintenance.
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_advancedsettings_maintenance', array(
			array( 
				'name' => __('Maintenance','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('Take your site offline for the Maintenance','yeahthemes'),
			),
			array( 
				'name' => __('Take the Site Offline','yeahthemes'),
				'desc' => __('This will show an offline message. Except for administrators, nobody will be able to access the site','yeahthemes'),
				'id' => 'offline_mode',
				'std' => '0',
				'type' => 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'settings' => array(
					'folds' => '0',
				),
			),
			array( 
				'name' => __('Offline heading','yeahthemes'),
				'desc' => __('Heading of Message','yeahthemes'),
				'id' => 'offline_heading',
				'std' => 'We\'ll be back soon!',
				'type' => 'text',
				'settings' => array(
					'fold' => 'offline_mode',
				),
			),
			array( 
				'name' => __('Offline Message','yeahthemes'),
				'desc' => __('Message context','yeahthemes'),
				'id' => 'offline_about_msg',
				'std' => 'We are busy updating the site for you and will be back shortly!<br>So please, Comeback later !',
				'type' => 'textarea',
				'settings' => array(
					'fold' => 'offline_mode',
				),
			),
			array( 
				'name' => __('Meta Description','yeahthemes'),
				'desc' => __('Define a description of your web page that appear on Search engine','yeahthemes'),
				'id' => 'offline_meta_description',
				'std' => 'Everything you need to create a trendy, uniquely beautiful website without any of coding knowledge.',
				'type' => 'textarea',
				'settings' => array(
					'fold' => 'offline_mode',
				),
			),
			array( 
				'name' => __('Footer','yeahthemes'),
				'desc' => __('Footer infomation ( Email, Social networks, ...)','yeahthemes'),
				'id' => 'offline_footer',
				'std' => 'Copyright 2014. We\'re also on <a href="#">Twitter</a>, <a href="#">Facebook</a>, <a href="#">Google+</a>',
				'type' => 'textarea',
				'settings' => array(
					'fold' => 'offline_mode',
				),
			),
			array( 
				'name' => __('Text Color','yeahthemes'),
				'desc' => __('This color will be used for Maintenance page text','yeahthemes'),
				'id' => 'offline_text_color',
				'std' => '',
				'type' => 'colorpicker'
			),
			array( 
				'name' => __('Link Color','yeahthemes'),
				'desc' => __('This color will be used for links','yeahthemes'),
				'id' => 'offline_link_color',
				'std' => '',
				'type' => 'colorpicker'
			),
			
			array( 
				'name' => __('Background options','yeahthemes'),
				'desc' => __('default : no-repeat - center top - local - auto','yeahthemes'),
				'id' => 'offline_bg_options',
				'std' => array(
					'repeat' => 'no-repeat',
					'position' => 'center top',
					'attachment' => 'local',
					'size' => 'auto', 
					'color' => '',
					'image' => ''
				),
				'type' => 'background_options'
			),
			array( 
				'name' => __('Countdown','yeahthemes'),
				'desc' => '',
				'id' => 'offline_countdown',
				'std' => 'show',
				'type' => 'toggles',
				'options' => $show_hide
				
			),
			array( 
				'name' => __('Countdown Launch Date','yeahthemes'),
				'desc' => __('Select a date from the calendar.','yeahthemes'),
				'id' => 'offline_launch_date',
				'std' => '',
				'type' => 'calendar'
			),
			array( 
				'name' => __('Countdown Launch Time','yeahthemes'),
				'desc' => __('Enter the launch time e.g. 10:30','yeahthemes'),
				'id' => 'offline_launch_time',
				'std' => '10:30',
				'type' => 'time'
			),

		) ) );
		/**
		 * Advanced Settings - Miscs.
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_advancedsettings_miscs', array(
			array( 
				'name' => __('Miscs','yeahthemes'),
				'type' => 'subheading',
				'desc' => __('The other settings','yeahthemes'),
			),
			array( 
				'name' => __('Overwrite default media size automatically','yeahthemes'),
				'desc' => '',
				'id' => 'allow_overwrite_media_size',
				'std' => 1,
				'type' => 'checkbox',
				'class' => 'yt-section-toggle-checkbox',
				'settings' => array(
					'label' => __('Switch this off if you want to resize media manually','yeahthemes'),
					
				),				
			),
			array( 
				'name' 	=> __('Header Code','yeahthemes'),
				'desc' 	=> __('Your custom tags in header (eg: Custom Meta tags, CSS, etc ...)','yeahthemes'),
				'id' 	=> 'header_code',
				'std' 	=> '',
				'type' 	=> 'textarea',
				'settings' => array(
					'sanitize' => false
				),
			),
			array( 
				'name' 	=> __('Footer Code ','yeahthemes'),
				'desc' 	=> __('Your custom tags in footer(Analytics, custom script etc ...)','yeahthemes'),
				'id' 	=> 'footer_code',
				'std' 	=> '',
				'type' 	=> 'textarea',
				'settings' => array(
					'sanitize' => false
				),
			)

		) ) );
		
		/**
		 * Backup & Restore
		 */
		$yt_options = array_merge( $yt_options, apply_filters( 'yt_theme_options_backuprestore', array(
			array( 
				'name' => __('Backup & Restore','yeahthemes'),
				'type' => 'heading',
				'desc' => __('Backup/Transfer your Theme options data','yeahthemes'),
				'settings' => array(
					'icon' => 'backuprestore'
				)
			),
			array( 
				'name' => __('Backup and Restore Options','yeahthemes'),
				'desc' => __('You can use the two buttons below to backup your current options, and then restore it back at a later time. This is useful if you want to experiment on the options but would like to keep the old settings in case you need it back.','yeahthemes'),
				'std' => '',
				'type' => 'backup',
				'options' => ''
				
			),
			array( 
				'name' => __('Transfer Theme Options Data','yeahthemes'),
				'std' => '',
				'type' => 'transfer',
				'desc' => __('<br>You can tranfer the saved options data between different installs by copying the text inside the text box. To import data from another install, replace the data in the text box with the one from another install and click "Import Options"','yeahthemes')
				
			)
		) ) );
		
		// Backup Options
		
	}	
}

/**
 * Fontfaces Variable for option
 * 
 * @access public
 * @return array
 * @since 1.0
 */
if( !function_exists( 'yt_get_option_vars' ) ) {
	function yt_get_option_vars( $type = '' ){
		
		if( empty( $type ) )
			return array();

		if( !in_array( $type, array( 'fontfaces', 'footer_columns', 'skins' ) ) )
			return array();

		/**
		 * Fontfaces
		 */
		if( 'fontfaces' == $type )
			$var = apply_filters( 'yt_option_vars_fontfaces', array(
				'Arial, Helvetica, sans-serif'								=> 'Arial, Helvetica, sans-serif',
				'"Comic Sans MS", cursive' 									=> '"Comic Sans MS", cursive',
				'"Courier New", Courier, monospace'							=> '"Courier New", Courier, monospace',
				'Georgia, "Times New Roman", Times, serif' 					=> 'Georgia, "Times New Roman", Times, serif',
				'"Helvetica Neue", Helvetica, Arial, sans-serif'			=> '"Helvetica Neue", Helvetica, Arial, sans-serif',				
				'"Lucida Console", Monaco, monospace'						=> '"Lucida Console", Monaco, monospace',
				'"Lucida Grande", "Lucida Sans Unicode", sans-serif' 		=> '"Lucida Grande", "Lucida Sans Unicode", sans-serif',
				'"MS Serif", "New York", serif' 							=> '"MS Serif", "New York", serif',				
				'"Palatino Linotype", "Book Antiqua", Palatino, serif' 		=> '"Palatino Linotype", "Book Antiqua", Palatino, serif',				
				'Tahoma, Geneva, sans-serif'								=> 'Tahoma, Geneva, sans-serif',
				'"Times New Roman", Times, serif'							=> '"Times New Roman", Times, serif ',
				'"Trebuchet MS", Arial, Helvetica, sans-serif'				=> '"Trebuchet MS", Arial, Helvetica, sans-serif',				
				'Verdana, Geneva, sans-serif' 								=> 'Verdana, Geneva, sans-serif',
			) );
		
		$url =  YEAHTHEMES_FRAMEWORK_URI . 'admin/assets/images/footer-columns/';

		/**
		 * Footer columns
		 */
		if( 'footer_columns' == $type )
			$var = apply_filters( 'yt_option_vars_footer_columns', array(
				'col-sm-12'                                                 => '12',
	            'col-sm-6_col-sm-6'                                         => '6+6',
	            'col-sm-4_col-sm-4_col-sm-4'                                => '4+4+4',
	            'col-sm-3_col-sm-3_col-sm-3_col-sm-3'                       => '3+3+3+3',
	            'col-sm-2_col-sm-2_col-sm-2_col-sm-2_col-sm-2_col-sm-2'     => '2+2+2+2+2+2',
	            'col-sm-4_col-sm-8'                                         => '4+8',
	            'col-sm-8_col-sm-4'                                         => '8+4',
	            'col-sm-3_col-sm-3_col-sm-6'                                => '3+3+6',
	            'col-sm-3_col-sm-6_col-sm-3'                                => '3+6+3',
	            'col-sm-6_col-sm-3_col-sm-3'                                => '6+3+3',
	            'col-sm-2_col-sm-4_col-sm-6'                                => '2+4+6',
	            'col-sm-2_col-sm-2_col-sm-2_col-sm-6'                       => '2+2+2+6',
	            'col-sm-2_col-sm-2_col-sm-4_col-sm-4'                       => '2+2+4+4',

	            'col-sm-2_col-sm-4_col-sm-2_col-sm-4'                       => '2+4+2+4',
	            'col-sm-2_col-sm-4_col-sm-4_col-sm-2'                       => '2+4+4+2',
	            'col-sm-6_col-sm-2_col-sm-2_col-sm-2'                       => '6+2+2+2',
	            'col-sm-2_col-sm-2_col-sm-2_col-sm-3_col-sm-3'              => '2+2+2+3+3',
	            'col-sm-3_col-sm-3_col-sm-2_col-sm-2_col-sm-2'              => '3+3+2+2+2',
	            'col-sm-2_col-sm-3_col-sm-2_col-sm-3_col-sm-2'              => '2+3+2+3+2',
			) );
		
		/**
		 * Skins
		 */
		if( 'skins' == $type )
			$var = apply_filters( 'yt_option_vars_skins', array(
				'#33b3d3' => 'light-blue',
				'#D64343' => 'red',
				'#00a3d3' => 'dodger-blue',
				'#516899' => 'dark-blue',
				'#77cc33' => 'lime-green',
				'#7870CC' => 'blue-marguerite',	
				'#66B58F' => 'silver-tree',
				'#f39c12' => 'orange',
				'#7cc576' => 'light-green',
				'#ea4c89' => 'pink',
				'#A252B1' => 'purple',
				'#58cb8e' => 'spring-green',
				'#7257a3' => 'violet',
				'#7A997B' => 'laurel',
				'#2cae8c' => 'turquoise',
				'#69B980' => 'silver-lime',
				'#34495e' => 'wet-asphalt',
				'#9cb265' => 'green-smoke',
				'#9b59b6' => 'amethyst',
				'#95a5a6' => 'concrete',
				'#27ae60' => 'nephritis',
				'#e74c3c' => 'alizarin',
				'#ee6a4c' => 'burnt-sienna',
				'#2980b9' => 'belize-hole',
				'#2c3e50' => 'midnight-blue',
				'#16a085' => 'green-sea',
				'#766CE4' => 'medium-purple',
				'#E07798' => 'deep-blush'
			) );

		return ( array ) $var;
	}
}