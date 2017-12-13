<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package yeahthemes
 */

get_header(); ?>

	<?php yt_before_primary(); ?>
	
	<div id="primary" <?php yt_section_classes( 'content-area', 'primary' );?>>
		
		<?php yt_primary_start(); ?>
		
		<main id="content" <?php yt_section_classes( 'site-content', 'content' );?> role="main">
			
			<?php yt_before_loop(); ?>
			
			<?php while ( have_posts() ) : the_post(); ?>
				
				<?php yt_loop_start(); ?>

				<?php get_template_part( 'content', 'forum' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template();
				?>
				
				<?php yt_loop_end(); ?>

			<?php endwhile; // end of the loop. ?>
			
			<?php yt_after_loop(); ?>

		</main><!-- #content -->
	
		<?php yt_primary_end(); ?>
		
	</div><!-- #primary -->
	
	<?php yt_after_primary(); ?>
	
	<?php 
	if( apply_filters( 'yt_site_secondary_content_condition', ( 'fullwidth' !== yt_site_get_current_page_layout() ) ) ) 
		get_sidebar();
	?>
	
<?php get_footer(); ?>
