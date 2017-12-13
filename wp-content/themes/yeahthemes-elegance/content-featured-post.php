<?php
/**
 * @package yeahthemes
 */
 
	$output_excerpt = '';
	if( 'automatic' == yt_get_options( 'excerpt_output' ) ){

		$excerpt = get_the_excerpt();

		$output_excerpt = sprintf('%s', apply_filters( 'the_content', $excerpt ) );
	}


		
	$readmore_content = 'show' == yt_get_options( 'blog_readmore_button' ) ? '<a class="more-tag btn btn-default" href="'. esc_url( get_permalink( get_the_ID() ) ) . ( 'manual' == yt_get_options( 'excerpt_output' ) ? "#more-" . esc_attr( get_the_ID() ) : '' ) . '"> ' . esc_html__('Read More...','yeahthemes') . '</a>' : '';

	//$readmore_content= '';

	/*Rating*/
	$rating_content = '';
	if( is_home() && function_exists( 'wp_review_show_total') ){
		$rating = wp_review_show_total(false, 'review-total-only review-mark inline-block');
		if( $rating )
			$rating_content = '<div class="rating">' . sprintf(esc_html__('Rating: %s', 'yeahthemes'), $rating ) . '</div>';
	}

	$extra_class = '';
	
	YT_Post_Helpers::$featured_post_counter++;
	$extra_class = YT_Post_Helpers::$featured_post_counter % 2 == 0 ? "even" : "odd";
?>
<?php 
/**
 * Standard format
 ******************************************************************
 */
?>
<article id="post-<?php echo esc_attr( get_the_ID() ); ?>" <?php post_class( esc_attr( $extra_class ) ); ?> <?php yt_post_atts();?>>

	<?php do_action( 'yt_before_hero_post_entry_header' );?>

	<header class="entry-header">

		<?php do_action( 'yt_hero_post_entry_header_start' );?>
		
		<?php 
			$thumbnail = wp_get_attachment_url( get_post_thumbnail_id(), 'post-thumbnail' );
			// Support Lazyload plugin
			if ( class_exists( 'LazyLoad_Images' ) ) {
				
				$template = '<div class="wp-post-image" data-lazy-src="%s"></div>';

			}else{
				$template = '<div class="wp-post-image" style="background-image:url(%s);"></div>';
			}
		?>
		<div class="entry-thumbnail">
			
			<a class="post-thumbnail" href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="<?php echo esc_attr( get_the_title() ); ?>">
	            <?php echo !empty( $thumbnail ) ? sprintf( $template, esc_attr( $thumbnail ) ) : '<div class="wp-post-image"></div>' ;?>
	        </a>

		</div>

		<?php the_title( sprintf( '<h2 class="entry-title secondary-2-primary"><a href="%s" rel="bookmark" title="%s">', esc_url( get_permalink() ), esc_attr( get_the_title() ) ), '</a></h2>' ); ?>
		
		<?php if( !is_search() && 'hide' !== yt_get_options( 'blog_post_meta_info_mode' )): ?>
		<div class="entry-meta hidden-print">
			<?php if( function_exists( 'yt_post_meta_description' ))
				yt_post_meta_description(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php do_action( 'yt_hero_post_entry_header_end' );?>

	</header><!-- .entry-header -->

	<?php do_action( 'yt_before_hero_post_entry_content' );?>
	
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary margin-bottom">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">		

		<?php do_action( 'yt_hero_post_entry_content_start' );?>
		
		<?php 
		$allowed_tags = array(
			'a' => array(
		        'href' => array(),
		        'title' => array(),
		        'class' => array(),
		    ),
			'strong' => array(
				'class' => array(),
			),
			'p' => array(
				'class' => array(),
			),
			'b' => array(),
			'em' => array(),
			'br' => array(),
			'i' => array(
				'class' => array(),
				'style' => array()
			)
		);
		if( 'automatic' == yt_get_options( 'excerpt_output' ) ){ 
			
			echo wp_kses( $output_excerpt . $readmore_content, $allowed_tags );

		}else{
			the_content( wp_kses( $readmore_content, $allowed_tags ) );

			wp_link_pages( array(
				'before' => '<div class="page-links pagination-nav">' . esc_html__( 'Pages:', 'yeahthemes' ),
				'after'  => '</div>',
				'link_before' => '<span class="page-numbers">',
				'link_after' => '</span>',
			) );
		}
		
		?>
		
		<?php do_action( 'yt_hero_post_entry_content_end' );?>

	</div><!-- .entry-content -->
	<?php endif; ?>

	
	<footer class="entry-meta clearfix">
		<?php if ( current_user_can('edit_post', get_the_ID()) ) {?>
			<?php edit_post_link( esc_html__( '—Edit—', 'yeahthemes' ), '<span class="edit-link">', '</span>' ); ?>
		<?php }?>
	</footer><!-- .entry-meta -->
	
	<?php do_action( 'yt_after_hero_post_entry_footer' );?>
	
</article><!-- #post-<?php the_ID(); ?>## -->