<?php


$data = yt_get_options('blog_post_meta_info');
$data = array_filter( $data );
foreach ( (array) $data as $key => $value) {
	
	switch ( $value ) {
		case 'sticky':
			if( is_sticky() )
			echo sprintf( '<span class="featured-post"><i class="fa fa-star"></i>%s</span>', esc_html__( 'Featured', 'yeahthemes' ) );

		break;

		case 'date':

			$time_string = '<time class="entry-date published%5$s" datetime="%1$s">%2$s</time>';
			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
				$time_string .= '<time class="updated hidden" datetime="%3$s">%4$s</time>';
		
			$time_string = sprintf( $time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() ),
				get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ? '' : ' updated'
			);
			
			printf( '<span class="posted-on gray-2-primary"><i class="fa fa-clock-o"></i> %s</span>',
				sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
					esc_url( get_permalink() ),
					esc_attr( get_the_time() ),
					$time_string
				)
			);

		break;

		case 'category':
			printf( __('<span class="byline gray-2-primary"><i class="fa fa-comments"></i> in %s</span>', 'yeahthemes' ), 
				get_the_category_list( __( ', ', 'yeahthemes' ) )
			);
		break;

		case 'comments':
			echo '<span class="with-cmt gray-2-primary"><i class="fa fa-comments"></i> ';
				comments_popup_link( __( '0', 'yeahthemes' ), __( '1', 'yeahthemes' ), __( '%', 'yeahthemes' ));
			echo '</span>';
		break;

		case 'author':
			printf( __( '<span class="by-author primary-2-secondary">by %s</span>', 'yeahthemes' ), 
				sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_attr( sprintf( __( 'View all posts by %s', 'yeahthemes' ), get_the_author() ) ),
					esc_html( get_the_author() )
				)
			);
		break;
		
		default:
			# code...
			do_action( 'yt_theme_post_meta_description', $value );
		break;
	}
}

?>

<?php

if( function_exists( 'yt_simple_post_views_tracker_display' ) ){
	echo '<span class="post-views gray-2-primary"><i class="fa fa-eye"></i> ';
		yt_simple_post_views_tracker_display( get_the_ID(), true );
	echo '</span>';
}

if( function_exists('yt_impressive_like_display') ){
	echo yt_impressive_like_display(get_the_ID(), false, 'likey');
}