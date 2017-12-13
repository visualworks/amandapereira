<?php
/**
 * The template for displaying image attachments.
 *
 * @package yeahthemes
 */

get_header();?>
		
	<?php yt_before_primary(); ?>
	
	<div id="primary" <?php yt_section_classes( 'content-area image-attachment', 'primary' );?>>
		
		<?php yt_primary_start(); ?>
		
		<main id="content" <?php yt_section_classes( 'site-content', 'content' );?> role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

					<div class="entry-meta hidden-print">
						<?php
							$metadata = wp_get_attachment_metadata();
							printf( __( 'Published <span class="entry-date"><time class="entry-date" datetime="%1$s">%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a>', 'yeahthemes' ),
								esc_attr( get_the_date( 'c' ) ),
								esc_html( get_the_date() ),
								esc_url( wp_get_attachment_url() ),
								esc_html($metadata['width']),
								esc_html($metadata['height']),
								esc_url( get_permalink( $post->post_parent ) ),
								esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
								esc_html( get_the_title( $post->post_parent ) )
							);
						?>
					</div><!-- .entry-meta -->

					<nav role="navigation" id="image-navigation" class="image-navigation">
						<span class="nav-previous"><?php previous_image_link( false, __( '<span class="meta-nav">&larr;</span> Previous', 'yeahthemes' ) ); ?></span>
						<span class="nav-next"><?php next_image_link( false, __( 'Next <span class="meta-nav">&rarr;</span>', 'yeahthemes' ) ); ?></span>
					</nav><!-- #image-navigation -->
				</header><!-- .entry-header -->

				<div class="entry-content">
					<div class="entry-thumbnail">
						<?php yt_the_attached_image(); ?>

						<?php if ( has_excerpt() ) : ?>
						<div class="entry-caption">
							<?php the_excerpt(); ?>
						</div><!-- .entry-caption -->
						<?php endif; ?>
					</div><!-- .entry-attachment -->

					<?php
						the_content();
						wp_link_pages( array(
							'before' => '<div class="page-links pagination-nav">' . esc_html__( 'Pages:', 'yeahthemes' ),
							'after'  => '</div>',
							'link_before' => '<span class="page-numbers">',
							'link_after' => '</span>',
						) );
					?>
				</div><!-- .entry-content -->

				<footer class="entry-meta">
					<?php
						if ( comments_open() && pings_open() ) : // Comments and trackbacks open
							printf( __( '<a class="comment-link" href="#respond" title="Post a comment">Post a comment</a> or leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'yeahthemes' ), esc_url( get_trackback_url() ) );
						elseif ( ! comments_open() && pings_open() ) : // Only trackbacks open
							printf( __( 'Comments are closed, but you can leave a trackback: <a class="trackback-link" href="%s" title="Trackback URL for your post" rel="trackback">Trackback URL</a>.', 'yeahthemes' ), esc_url( get_trackback_url() ) );
						elseif ( comments_open() && ! pings_open() ) : // Only comments open
							 _e( 'Trackbacks are closed, but you can <a class="comment-link" href="#respond" title="Post a comment">post a comment</a>.', 'yeahthemes' );
						elseif ( ! comments_open() && ! pings_open() ) : // Comments and trackbacks closed
							_e( 'Both comments and trackbacks are currently closed.', 'yeahthemes' );
						endif;

						edit_post_link( esc_html__( '—Edit—', 'yeahthemes' ), ' <span class="edit-link">', '</span>' );
					?>
				</footer><!-- .entry-meta -->
			</article><!-- #post-## -->

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() )
					comments_template();
			?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #content -->
		
		<?php yt_primary_end(); ?>
	
	</div><!-- #primary -->
	
	<?php yt_after_primary(); ?>
	
	<?php 
	if( apply_filters( 'yt_site_secondary_content_condition', ( 'fullwidth' !== yt_site_get_current_page_layout() ) ) ) 
		get_sidebar();
	?>
	
<?php get_footer(); ?>