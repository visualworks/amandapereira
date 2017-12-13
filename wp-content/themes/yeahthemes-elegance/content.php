<?php
/**
 * @package yeahthemes
 */
 
	$format = get_post_format();
	
	if( false === $format )
		$format = 'standard';

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
			$rating_content = '<div class="rating">' . sprintf( '<strong>%s</strong>', esc_html__('Rating: ', 'yeahthemes') ) . $rating . '</div>';
	}

	$extra_class = '';

	//if( !is_sticky( )){
		YT_Post_Helpers::$main_post_counter++;
		$extra_class = YT_Post_Helpers::$main_post_counter % 2 == 0 ? "even" : "odd";
	//}
	$post_layout = yt_get_options( is_archive() ? 'archive_post_layout' : 'blog_post_layout' );
	$post_layout = $post_layout ? $post_layout : 'default';
	$thumb_size = 'default' == $post_layout  ? 'post-thumbnail' : 'medium';
	if( 'classic' == $post_layout ){
		$thumb_size = is_sticky()  ? 'large' : 'medium';	
	}
	
	$extra_class .= " layout-{$post_layout}";
	$extra_class .= is_sticky() ? ' sticky' : '';
	$thumb_size = 'large';	

?>

<article id="post-<?php echo esc_attr( get_the_ID() ); ?>" <?php post_class( esc_attr( $extra_class ) ); ?> <?php yt_post_atts();?>>

	<?php do_action( 'yt_before_archive_post_entry_header' );?>

	<header class="entry-header">

		<?php do_action( 'yt_archive_post_entry_header_start' );?>
		
		<?php if ( has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() && 'default' !== $post_layout ) : ?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="entry-thumbnail" rel="bookmark" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_post_thumbnail( $thumb_size ); ?></a>
		<?php endif; ?>

		<?php if( !is_search() && 'hide' !== yt_get_options( 'blog_post_meta_info_mode' ) && 'classic' == $post_layout ): ?>
		<div class="entry-meta hidden-print">
			<?php if( function_exists( 'yt_post_meta_description' ))
				yt_post_meta_description(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php the_title( sprintf( '<h2 class="entry-title secondary-2-primary"><a href="%s" rel="bookmark" title="%s">', esc_url( get_permalink() ), esc_attr( get_the_title() ) ), '</a></h2>' ); ?>
		
		<?php if( !is_search() && 'hide' !== yt_get_options( 'blog_post_meta_info_mode' ) && 'classic' !== $post_layout ): ?>
		<div class="entry-meta hidden-print">
			<?php if( function_exists( 'yt_post_meta_description' ))
				yt_post_meta_description(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php do_action( 'yt_archive_post_entry_header_end' );?>

	</header><!-- .entry-header -->

	<?php do_action( 'yt_before_archive_post_entry_content' );?>
	
	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary margin-bottom">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">		

		<?php do_action( 'yt_archive_post_entry_content_start' );?>
		
		<?php if (  has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() && 'default' == $post_layout && !in_array($format, yt_get_options('mainblog_hide_thumb') ) ) : ?>
		<a href="<?php echo esc_url( get_permalink() ); ?>" class="entry-thumbnail" rel="bookmark" title="<?php echo esc_attr( get_the_title() ); ?>"><?php the_post_thumbnail( $thumb_size ); ?></a>
		<?php endif; ?>


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

		echo $rating_content;
		
		?>
		
		<?php do_action( 'yt_archive_post_entry_content_end' );?>

	</div><!-- .entry-content -->
	<?php endif; 

	if ( !is_search() ) :
	?>
	
	
	<footer class="entry-meta clearfix">
		<?php if ( current_user_can('edit_post', get_the_ID()) ) {?>
			<?php edit_post_link( esc_html__( '—Edit—', 'yeahthemes' ), '<span class="edit-link">', '</span>' ); ?>
		<?php }?>
	</footer><!-- .entry-meta -->

	<?php endif;  ?>
	
	<?php do_action( 'yt_after_archive_post_entry_footer' );?>
	
</article><!-- #post-<?php the_ID(); ?>## -->