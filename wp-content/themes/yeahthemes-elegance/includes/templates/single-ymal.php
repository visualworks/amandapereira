<?php
do_action( 'yt_site_single_post_you_might_also_like_before' );

global $post;

$backup_post = $post;

if( empty( $post ) )
	return;

/*Get current post's categories*/
$post_cats_temp = get_the_category( $post->ID );
$post_cats = array();
if( !empty( $post_cats_temp )){
	foreach ( $post_cats_temp as $cat) {
		$post_cats[] = $cat->cat_ID;
	}
}

if( empty( $post_cats ) )
	return;


// global $wpdb;
// $querystr = "
// 	SELECT $wpdb->posts . * 
// 	FROM $wpdb->posts, $wpdb->postmeta 
// 	WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
// 	AND $wpdb->postmeta.meta_key = '_post_views' 
// 	AND $wpdb->postmeta.meta_value > 1 
// 	AND $wpdb->posts.post_status = 'publish' 
// 	AND $wpdb->posts.post_type = 'post' 
// 	ORDER BY $wpdb->postmeta.meta_value DESC LIMIT 4
// ";

// foreach ($wpdb->get_results(  $querystr ) as $key) {

// 	echo "$key->post_title<br>";
	
// }

/*Prepare the query, allow modifying by applying filter*/
$myposts = get_posts( apply_filters( 'yt_site_single_post_you_might_also_like_query', array( 
	// 'order' 			=> 'DESC',
	// 'orderby' 			=> 'rand',
	'post_type' 		=> 'post',
	'post_status' 		=> 'publish',
	'post__not_in'		=> array( $post->ID ),
	'suppress_filters'  => false,
	'posts_per_page' => 6, 
	'category__in' => $post_cats,
	'tax_query' => array(
		// array(
		// 	'taxonomy' 	=> 'post_format',
		// 	'field' 	=> 'slug',
		// 	'terms' 	=> array( 'post-format-image', 'post-format-link', 'post-format-quote'),
		// 	'operator' 	=> 'NOT IN'
		// )
	),
	// 'date_query' => array(
	// 	array(
	// 		'year'  => date('Y', current_time('timestamp')),
	// 		'month' => date('m', current_time('timestamp')),
	// 	),
	// )
) ) );

if ( $myposts ) :
	echo '<div class="entry-stuff secondary-2-primary you-might-also-like-articles hidden-print">';
		echo sprintf( '<h3 class="you-may-also-like-title">%s</h3>', __( 'You May Also Like', 'yeahthemes' ) );
		echo '<ul class="post-list post-list-with-thumbnail post-list-with-format-icon horizontal row">';
		$count = 0;
		foreach ( $myposts as $post ) :
			setup_postdata( $post );

			
			$count++;

			$categories = get_the_category();
			$cat_tag 			= '';
	
			if( !empty( $categories[0] ) ){
				$category 	= $categories[0];
				$cat_tag 	.= '<span class="cat-tag ' . esc_attr( $category->slug ) . '">'.$category->cat_name.'</span>';
			}

			$format = get_post_format();
			if ( false === $format ) {
				$format = 'standard';
			}

			if( $count > 1 && $count%3 == 1){
				echo '<li class="clear hidden-xs"></li>';
			}
			$class = array();
			$class[] = $count % 2 == 0 ? "even" : "odd";	
			$class[] = "format-$format";
			
			YT_Post_Helpers::$listed_post[] = get_the_ID();
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'medium' );
			?>
			<li class="post-with-large-thumbnail col-xs-6 col-sm-4 col-md-4 <?php echo esc_attr( join(' ', $class ) );?>">
				
					<?php if( has_post_thumbnail() && get_the_post_thumbnail() && ! post_password_required() ): ?>
					<div class="post-thumb large">
						<div class="entry-thumbnail">
							<?php 
								// Support Lazyload plugin
								if ( class_exists( 'LazyLoad_Images' ) ) {
									
									$template = '<div class="wp-post-image" data-lazy-src="%s"></div>';

								}else{
									$template = '<div class="wp-post-image" style="background-image:url(%s);"></div>';
								}
							?>
							<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true">
				                <?php echo !empty( $thumbnail[0] ) ? sprintf( $template, esc_attr( $thumbnail[0] ) ) : '<div class="wp-post-image"></div>' ;?>
				            </a>
						</div>
						<?php echo !empty( $cat_tag ) ? $cat_tag : '';?>
						<?php 
							if (function_exists('wp_review_show_total') && !empty( $instance['show_rating'] ) ) {
								$review_type = get_post_meta( $post->ID, 'wp_review_type', true );
									if( 'star' !== $review_type )
										wp_review_show_total(true, 'review-total-only review-mark'); 
							}
						?>
					</div>

					<?php endif;?>
					
					<time class="entry-date published" datetime="<?php echo esc_attr( get_the_time('c') ); ?>"><?php echo get_the_date();?></time>
					<?php
						if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
							echo sprintf( '<time class="updated hidden" datetime="%1$s">%2$s</time>', 
								esc_attr( get_the_modified_date( 'c' ) ),
								esc_html( get_the_modified_date() )
							);
					?>
					<h2><a href="<?php echo esc_attr( get_permalink() );?>" rel="bookmark" class="post-title" title="<?php echo esc_attr( get_the_title() );?>"><?php the_title();?></a></h2>
					<?php 
					if (function_exists('wp_review_show_total') ) {
						$review_type = get_post_meta( $post->ID, 'wp_review_type', true );
							if( 'star' == $review_type )
								wp_review_show_total(true, 'review-total-only review-mark'); 
					}
					?>
				
			</li>
			<?php

		endforeach;
		echo '</ul>';
	echo '</div>';
endif;

/*Reset post data & restore global post*/
wp_reset_postdata();

/*Restore post*/
$post = $backup_post;

do_action( 'yt_site_single_post_you_might_also_like_after' );