<?php

/**********************************************************************************************************
 *        __   _______    _    _   _ _____ _   _ _____ __  __ _____ ____         ____ ___  __  __
 *        \ \ / / ____|  / \  | | | |_   _| | | | ____|  \/  | ____/ ___|       / ___/ _ \|  \/  |
 *         \ V /|  _|   / _ \ | |_| | | | | |_| |  _| | |\/| |  _| \___ \      | |  | | | | |\/| |
 *          | | | |___ / ___ \|  _  | | | |  _  | |___| |  | | |___ ___) |  _  | |__| |_| | |  | |
 *          |_| |_____/_/   \_\_| |_| |_| |_| |_|_____|_|  |_|_____|____/  (_)  \____\___/|_|  |_|
 *
 *				  		Premium WordPress Themes by YeahThemes.com - http://Yeahthemes.com
 *												Copyright (c) YeahThemes
 *											http://themeforest.net/user/Yhthms
 *
 * 									-------------------------------------------
 * 												DO NOT EDIT THIS FILE
 * 									-------------------------------------------
 *
 * To add custom PHP functions to the theme, create a new 'custom-functions.php' file in the theme folder.
 *
 **********************************************************************************************************/

define( 'YEAHTHEMES_DIR', get_template_directory() . '/' );
define( 'YEAHTHEMES_URI', get_template_directory_uri() . '/' );

if ( isset( $content_width ) )
	$content_width = 650;

/**
 * Include theme framework
 */
$theme_includes = apply_filters( 'yt_locate_template_yeahthemes_functions_includes',
	array(	
		'framework/admin/init.php',
		'framework/yt-admin-init.php',
		//'framework/elite-builder/builder-init.php',
		'includes/theme-init.php',
		'custom-functions.php',
	)
);
			
foreach ( $theme_includes as $include ) { 
	locate_template( $include, true ); 
}// get taxonomies terms links


