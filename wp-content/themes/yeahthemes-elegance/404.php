<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package yeahthemes
 */

get_header(); ?>
	
	<?php yt_before_primary(); ?>
	
	<div id="primary" <?php yt_section_classes( 'content-area', 'primary' );?>>
		
		<?php yt_primary_start(); ?>
		
		<main id="content" <?php yt_section_classes( 'site-content', 'content' );?> role="main">

			<section class="error-404 not-found">
				<header class="page-header">
					<h1 class="page-title"><strong class="e404">404</strong></h1>
					<h3><?php _e( 'Oops! That page could not be found.<br/>Try using the search box.', 'yeahthemes' ); ?></h3>
				</header><!-- .page-header -->

				<div class="page-content">
					
					
				</div>

			</section><!-- .error-404 -->

		</main><!-- #content -->
		
		<?php yt_primary_end(); ?>
	
	</div><!-- #primary -->
	
	<?php yt_after_primary(); ?>
	
	<?php 
	if( apply_filters( 'yt_site_secondary_content_condition', ( 'fullwidth' !== yt_site_get_current_page_layout() ) ) ) 
		get_sidebar();
	?>
	
<?php get_footer(); ?>