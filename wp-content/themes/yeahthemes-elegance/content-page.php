<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package yeahthemes
 */

?>

<article id="post-<?php echo esc_attr( get_the_ID() ); ?>" <?php post_class(); ?> <?php yt_post_atts();?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php if( has_post_thumbnail()): ?>
		<div class="entry-thumbnail">
			 
				<?php the_post_thumbnail( 'post-thumbnail'); ?>
			
		</div>
		<?php endif;?>
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links pagination-nav">' . esc_html__( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
				'link_before' => '<span class="page-numbers">',
				'link_after' => '</span>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php edit_post_link( esc_html__( '—Edit', 'yeahthemes' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
</article><!-- #post-## -->