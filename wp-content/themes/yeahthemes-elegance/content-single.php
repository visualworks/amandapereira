<?php
/**
 * @package yeahthemes
 */
	$format = get_post_format();
	
	if( false === $format )
		$format = 'standard';
	
	/**
	 *Extra class for entry title
	 */
	$entry_title_class = ' no-margin-bottom';
	
	$entry_title = get_the_title( get_the_ID() );

	$feature_image = yt_get_options('blog_single_post_featured_image');
?>

<article id="post-<?php echo esc_attr( get_the_ID() ); ?>" <?php post_class(); ?> <?php yt_post_atts();?>>


	<?php do_action( 'yt_before_single_post_entry_header' );?>

	<header class="entry-header">

		<?php do_action( 'yt_single_post_entry_header_start' );?>

		<?php the_title( sprintf('<h1 class="entry-title%s">', esc_attr( $entry_title_class ) ), '</h1>' ); ?>
		
		<?php if( 'hide' !== yt_get_options( 'blog_post_meta_info_mode' )): ?>
		<div class="entry-meta hidden-print">
			<?php if( function_exists( 'yt_post_meta_description' ))
				yt_post_meta_description(); ?>
		</div><!-- .entry-meta -->
		<?php endif?>

		<?php
		/*Standard*/
		if ( !in_array($format, $feature_image ) && has_post_thumbnail() && !post_password_required() ) : ?>
		<div class="entry-thumbnail">
			<?php the_post_thumbnail( 'post-thumbnail'); ?>
			<?php

			if( $thumb_excerpt = yt_get_thumbnail_meta( get_post_thumbnail_id( get_the_ID() ), 'post_excerpt' ) ){
				echo sprintf( '<span class="entry-caption thumbnail-caption">%s</span>' , $thumb_excerpt );
			}
			//echo yt_get_post_thumbnail_meta( get_the_ID(), 'post_excerpt' ) ? yt_get_post_thumbnail_meta(null) : '';

			//print_r( yt_get_post_thumbnail_meta());
			?>
		</div>
		<?php endif; ?>

		<?php do_action( 'yt_single_post_entry_header_end' );?>
	</header><!-- .entry-header -->

	<?php do_action( 'yt_before_single_post_entry_content' );?>

	<div class="entry-content">

		<?php do_action( 'yt_single_post_entry_content_start' );?>
		
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links pagination-nav">' . esc_html__( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
				'link_before' => '<span class="page-numbers">',
				'link_after' => '</span>',
			) );
		?>

		<?php do_action( 'yt_single_post_entry_content_end' );?>

	</div><!-- .entry-content -->
	
	<?php do_action( 'yt_before_single_post_entry_footer' );?>

	

	<footer class="entry-meta hidden-print">
		<?php do_action( 'yt_single_post_entry_footer_start' );?>

		<?php
			$meta_text = '';
			/* translators: used between list items, there is a space after the comma */
			$meta_text .= sprintf('<div class="entry-cats alt-font gray-2-secondary">%s</div>', get_the_category_list( __( ', ', 'yeahthemes' ) ) );


			$tag_list = get_the_tag_list( '', ', ' );
			if ( $tag_list ) :
			
			
				$post_tags = '';
				if ( '' != $tag_list ) {
					$post_tags = '<div class="entry-tags gray-2-secondary">';
					$post_tags .=  '<strong class="tag-heading">%s</strong> %s';
					$post_tags .= '</div>';
				} 

				$meta_text .= sprintf(
					$post_tags,
					apply_filters('yt_icon_tag', '<i class="fa fa-tag"></i>') . esc_html__( 'Tags:', 'yeahthemes' ),
					$tag_list
				);

			endif;

			echo( $meta_text );
		?>
		<?php do_action( 'yt_single_post_entry_footer_end' );?>
	</footer><!-- .entry-meta -->



	<?php do_action( 'yt_after_single_post_entry_footer' );?>
	
</article><!-- #post-<?php the_ID(); ?>## -->