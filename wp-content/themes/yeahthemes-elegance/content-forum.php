<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package yeahthemes
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links pagination-nav">' . __( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
				'link_before' => '<span class="page-numbers">',
				'link_after' => '</span>',
			) );
		?>
	</div><!-- .entry-content -->
	<?php edit_post_link( __( '—Edit', 'yeahthemes' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
	
</article><!-- #post-## -->
