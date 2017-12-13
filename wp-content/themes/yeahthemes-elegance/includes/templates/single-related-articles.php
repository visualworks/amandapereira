<?php
global $post;

$backup_post = $post;

if( !empty( $post )){
	$post_id = $post->ID;
}else{
	$post_id = 0;
}

$tags = wp_get_post_tags( $post_id );
if ($tags):

	$tag_ids = array();  
	foreach( $tags as $tag )
		$tag_ids[] = $tag->term_id; 

	$args = array(  
		'tag__in' => $tag_ids,  
		'post__not_in' => array( $post_id ),  
		'posts_per_page'=> 3, //number of posts to display
		'order' 			=> 'DESC',
		'orderby' 			=> 'date',
	);       
	$related_articles = get_posts( apply_filters( 'yt_site_single_post_related_articles_query', $args, $post_id ) );
	if( $related_articles ):?>
		<ul class="related-articles">
		<?php
		foreach ( $related_articles as $post ) : 
				setup_postdata( $post ); 

				$format = get_post_format();
			if ( false === $format ) {
				$format = 'standard';
			}
				?>
			<li class="<?php echo esc_attr( "format-$format" );?>"><a href="<?php echo esc_attr( get_permalink() );?>" class="post-title" title="<?php echo esc_attr( get_the_title() );?>"><?php the_title();?></a> - <time class="entry-date published" datetime="<?php echo esc_attr( get_the_time('c') ); ?>"><?php echo get_the_date();?></time></li>
			<?php endforeach;?>

		</ul>
		<?php
		endif;
	wp_reset_postdata();
endif;

$post = $backup_post;