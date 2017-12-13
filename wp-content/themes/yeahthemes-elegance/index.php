<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package yeahthemes
 */

get_header(); ?>
	
	<?php yt_before_primary(); ?>
	
	<div id="primary" <?php yt_section_classes( 'content-area', 'primary' );?>>
		
		<?php yt_primary_start(); ?>
		
		<main id="content" <?php yt_section_classes( 'site-content', 'content' );?> role="main">
				
		<?php yt_before_loop(); ?>

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			
			<?php while ( have_posts() ) : the_post(); ?>
			
				<?php yt_loop_start(); ?>
				
				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to overload this in a child theme then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>
				
				<?php yt_loop_end(); ?>
				
			<?php endwhile; ?>

			<?php 
			if( 'number' == yt_get_options( 'blog_pagination' ) )
				yt_pagination_nav( 'gray-2-secondary' );
			else
				yt_direction_nav( 'nav-below', 'secondary-2-primary' ); 
			?>

		<?php else : ?>

			<?php get_template_part( 'no-results', 'index' ); ?>

		<?php endif; ?>
		
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