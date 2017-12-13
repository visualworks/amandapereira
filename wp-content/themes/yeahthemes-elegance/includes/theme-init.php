<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Theme Init
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeah Includes
 */

define( 'YEAHTHEMES_INCLUDES_URI', 			YEAHTHEMES_URI . 'includes/' );
define( 'YEAHTHEMES_INCLUDES_DIR', 			YEAHTHEMES_DIR . 'includes/' );

/**
 * Static helper counter for Archive loop
 *
 * @since 1.0
 */
if( !class_exists( 'YT_Post_Helpers') ){
	class YT_Post_Helpers{
		public static $main_post_counter = 0;
		public static $featured_post_counter = 0;
		public static $listed_post = array();
	}
}
/**
 * Load functions
 *
 */
$yt_includes_includes  = apply_filters( 'yt_locate_template_includes_includes',
	array(
	
		'theme-meta-boxes.php',
		'theme-scripts.php',
		'theme-shortcodes.php',
		'theme-general-functions.php',
		'theme-template-functions.php',
		'theme-config.php',
		'theme-functions.php',
		'theme-interface.php',
		'theme-styling.php',
		'theme-wpml.php',
		'theme-woocommerce.php',
		'theme-bbpress.php',
		'theme-widgets.php',
		'theme-hooks.php'
	)
);
	
foreach ( $yt_includes_includes as $include ) { 
	locate_template( 'includes/' . $include, true );
}