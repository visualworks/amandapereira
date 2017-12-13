<?php
do_action( 'yt_start_the_awesomeness' );
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package yeahthemes
 */
?><!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<!--<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">-->
<meta charset="<?php bloginfo( 'charset' ); ?>">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>

<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() . '/includes/js/html5shiv.js' );?>"></script>
	<script src="<?php echo esc_url( get_template_directory_uri() . '/includes/js/respond.min.js' );?>"></script>
<![endif]-->
</head>

<body <?php body_class(); ?>>

<!--[if lt IE 9]>
<div id="yt-ancient-browser-notification">
	<div class="container">
		<?php echo '<p>Oops! Your browser is <strong><em>ancient!</em></strong> :( - <a href="http://' . 'browsehappy.com/" target="_blank">Upgrade to a different browser</a> or <a href="http://' . 'www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p>';?>
	</div>
</div>
<![endif]-->

<?php yt_before_wrapper();?>

<div id="page" <?php yt_section_classes( 'hfeed site', 'wrapper' );?>>

<div class="inner-wrapper">
	
<?php yt_wrapper_start(); ?> 

	<?php yt_before_header(); ?>
	
	<header id="masthead" <?php yt_section_classes( 'site-header', 'header' );?> role="banner">
	
		<?php 
			
			yt_header_start(); 
			
			yt_inside_header(); 
			
			yt_header_end();
		?>
		
	</header><!-- #masthead -->
	
	<?php yt_after_header(); //Call Action Hooks?>
	
	<div id="main" <?php yt_section_classes( 'site-main', 'main' );?>>
		<?php yt_main_start(); ?>