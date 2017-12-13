<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;
/**
 * Theme functions
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://yeahthemes.com
 * @since		Version 1.0
 * @package 	Yeah Includes
 */

add_filter( 'yt_sidebar_array', 'yt_site_sidebars' );
/**
 * Sidebars Initialization
 */

if( !function_exists( 'yt_site_sidebars') ) {
	
	function yt_site_sidebars( $sidebars ){
		
		/**
		 * Retrieve Options data
		 */
		$no_of_cols = yt_get_options('footer_columns');
		
		/* =Footer sidebar */		
		/* get the number of column from theme option */
		$footer_columns = isset( $no_of_cols ) && $no_of_cols ? $no_of_cols : 3; 
		$number_of_sidebar = '';
		
		/* if is numeric number*/
		if( is_numeric( $footer_columns ) ){
			$number_of_sidebar = $footer_columns;
		}
		/* else if is string */
		else{
			
			$footer_col_array = explode('_', $footer_columns );


			foreach ($footer_col_array as $k => $v ) {
				if( strpos($v, "clear") !== false || strpos($v, "hr") !== false )
					unset($footer_col_array[$k]);
			}

			//print_r($footer_col_array); die();

			$number_of_sidebar = count( ( array ) $footer_col_array );
			
		}
		
		for( $i = 1; $i <= $number_of_sidebar; $i++){
			
			$sidebars['footer-widget-' . $i] = sprintf( __('Footer Widget %s','yeahthemes'), $i );
			
		}
		
		$sidebars = apply_filters( 'yt_site_sidebars', $sidebars );
		
		return $sidebars;
		
	}
}

/**
 * Post thumbnail widget function
 * @since 1.0.4
 */

if( !function_exists( 'yt_site_post_list') ) {

	function yt_site_post_list( $instance = array() ){
		

		global $post;

		$instance['show_rating'] = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : false;
		$instance['wrapper'] = isset( $instance['wrapper'] ) ? (bool) $instance['wrapper'] : true;
		$instance['item_wrapper'] = !empty( $instance['item_wrapper'] ) ? $instance['item_wrapper'] : 'article';
		$instance['offset'] = !empty( $instance['offset'] ) ? $instance['offset'] : 0;
		$instance['excerpt'] = !empty( $instance['excerpt'] ) ? 1 : 0;

		$args = array( 
			'posts_per_page' 	=> isset( $instance['number'] ) ? intval( $instance['number'] ) : 10,
			'post_type' 		=> array( 'post' ),
			'order'				=> $instance['order'],
			'orderby' 			=> $instance['orderby'],
			'offset' 			=> intval( $instance['offset'] ),
		);

		$ul_class = array( 
			'post-list',
			'post-list-with-thumbnail',
			'post-list-with-format-icon',
			'secondary-2-primary',
		);
		// Number style
		if( 'number' == $instance['style'] ){
			$ul_class[] = 'number-style';
		}
		// Direction
		$direction = !empty( $instance['direction'] ) && 'horizontal' == $instance['direction'] ? 'horizontal' : 'vertical';
		$ul_class[] = $direction;

		if( 'horizontal' == $direction ){
			$ul_class[] = 'row';
			$ul_class[] = 'col-' . $instance['column'];

		}
		// push offset number for infinite scroll
		// $instance['offset'] = !empty( $instance['scroll_infinitely'] ) ? intval( $instance['offset'] ) : ( isset( $instance['number'] ) ? absint( $instance['number'] ) : 10 );

		if( !empty( $instance['wrapper'] ) ):
		?>
		<ul class="<?php echo !empty( $ul_class ) ? esc_attr( join(' ',  $ul_class ) ) : '';?>" data-settings="<?php echo esc_attr( json_encode( $instance ) );?>">
		<?php
		endif;

			if( !empty( $instance['category'] ) ){
				$args['category__in'] = !is_array( $instance['category'] ) ? explode(',', $instance['category'] ) : $instance['category'];
			}

			if( !empty( $instance['tags'] ) ){
				$args['tag__in'] = is_array( $instance['tags'] ) ? $instance['tags'] : explode(',', $instance['tags'] );
			}

			if( 'meta_value_num' == $instance['orderby'] ){
				$args['meta_key'] = apply_filters( 'yt_simple_post_views_tracker_meta_key', '_post_views' );
				$args['meta_value_num'] = '0';
				$args['meta_compare'] = '>';
			}

			

			if(class_exists( 'YT_Post_Helpers') && !empty( YT_Post_Helpers::$listed_post ) && apply_filters( 'yt_avoid_duplicated_posts', false ) ){
				$args['post__not_in'] = (array) YT_Post_Helpers::$listed_post;
			}

			if( is_singular('post' ) ){
				$args['post__not_in'][] = get_the_ID();
			}
			/*Date Parameters*/
			if( 'default' !== $instance['time_period'] ){
				
				$this_year = date('Y');
				$this_month = date('m');
				$this_week = date('W');

				if( 'this_week' == $instance['time_period'] ){
					$args['date_query'] = array(
						array(
							'year' => $this_year,
							'week' => $this_week,
						),
					);
				}elseif( 'last_week' == $instance['time_period'] ){

					if ( $this_week != 1 )
						$lastweek = $this_week - 1;
					else
						$lastweek = 52;

					if ($lastweek == 52)
						$this_year = $this_year - 1;

					$args['date_query'] = array(
						array(
							'year' => $this_year,
							'week' => $lastweek,
						),
					);
				}elseif( 'this_month' == $instance['time_period'] ){

					$args['date_query'] = array(
						array(
							'year' => $this_year,
							'month' => $this_month,
						),
					);
				}elseif( 'last_month' == $instance['time_period'] ){
					if ( $this_month != 1 )
						$this_month = $this_month - 1;
					else
						$this_month = 12;

					if ($this_month == 12)
						$this_year = $this_year - 1;

					$args['date_query'] = array(
						array(
							'year' => $this_year,
							'month' => $this_month,
						),
					);

					//yt_pretty_print( $args['date_query'] ); die();
				}elseif( 'last_30days' == $instance['time_period'] ){
					$args['date_query'] = array(
						array(
							'after'     => date('F j, Y', strtotime('today - 30 days')),
							'before'    => date('F j, Y'),
							'inclusive' => true,
						),
					);
				}elseif( 'last_60days' == $instance['time_period'] ){
					$args['date_query'] = array(
						array(
							'after'     => date('F j, Y', strtotime('today - 60 days')),
							'before'    => date('F j, Y'),
							'inclusive' => true,
						),
					);
				}elseif( 'last_90days' == $instance['time_period'] ){
					$args['date_query'] = array(
						array(
							'after'     => date('F j, Y', strtotime('today - 90 days')),
							'before'    => date('F j, Y'),
							'inclusive' => true,
						),
					);
				}
			}

			if( !empty( $instance['exclude_format'] ) && $instance['exclude_format'] ){
				$exclude_format_temp = array();
				foreach( $instance['exclude_format'] as $format ){
					$exclude_format_temp[] = "post-format-$format";
				}

				$args['tax_query'] = array(
				    array(
				      'taxonomy' 	=> 'post_format',
				      'field' 		=> 'slug',
				      'terms' 		=> $exclude_format_temp,
				      'operator' 	=> 'NOT IN'
				    )
				);
			}

			// Backup global post
			$temp_post = $GLOBALS['post'];

			// print_r( $args ); die();
			
			$myposts = get_posts( apply_filters( 'yt_posts_with_thumnail_widget_query', $args ) );
		// print_r( $args ); die();
			$image_size = $instance['style'];

			$count = 0;
			$post_ids = array();
			//print_r($args);
			foreach ( $myposts as $post ) : 
				setup_postdata( $post );
				// Assign global post to post item for functions
				$GLOBALS['post'] = $post;
				
				$count++;
				$format = get_post_format();
				if ( false === $format ) {
					$format = 'standard';
				}

				$format_icon = '';
				// if( 'video' == $format )
				// 	$format_icon = 'play';
				// elseif( 'audio' == $format )
				// 	$format_icon = 'music';
				// elseif( 'gallery' == $format )
				// 	$format_icon = 'picture-o';
				// elseif( 'quote' == $format )
				// 	$format_icon = 'quote-left';
				// elseif( 'link' == $format )
				// 	$format_icon = 'link';

				// $format_icon = $format_icon ? ' <i class="fa fa-' . $format_icon . ' format-icon gray-icon"></i>' : '';

				if( class_exists( 'YT_Post_Helpers') )
					YT_Post_Helpers::$listed_post[] = get_the_ID();

				$post_ids[] = get_the_ID();
				$categories = get_the_category();
				$cat_tag 			= '';
			
				if( !empty( $instance['show_cat'] ) && !empty( $categories[0] ) && apply_filters( 'yt_posts_with_thumnail_widget_cat', true ) ){
					$category 	= $categories[0];
					$cat_tag 	.= '<span class="cat-tag ' . esc_attr( $category->slug ) . '">' . esc_html($category->cat_name ) . '</span>';
					
				}

				$liClass = array();
				if( 'large' == $instance['style'] 
					|| ( in_array( $instance['style'], array( 'thumb_first', 'mixed' ) ) 
					&& 1 == $count ) )

					$liClass[] = 'post-with-large-thumbnail';
				
				if( ( in_array( $instance['style'], array( 'thumb_first', 'mixed' ) ) && 1 == $count ) )
					$liClass[] = 'title-alt';

				if( 'none' == $instance['item_wrapper'] )
					$liClass[] =  "format-{$format}";

				if( 'horizontal' == $direction ){
					$liClass[] = 'col-xs-6';
					$liClass[] = 'col-sm-6';
					$liColumn = intval( $instance['column'] ) ? $instance['column'] : 4;

					if( 6 == $liColumn ){
						$liClass[] = 'col-md-2';
					}elseif( 4 == $liColumn ){
						$liClass[] = 'col-md-3';
					}elseif( 3 == $liColumn ){
						$liClass[] = 'col-md-4';
					}elseif( 2 == $liColumn ){
						$liClass[] = 'col-md-6';
					}
				}

				$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), ('small' == $instance['style'] ? 'medium' : 'post-thumbnail') );
			?>
				<li data-id="<?php echo esc_attr( get_the_ID() ); ?>"<?php echo !empty( $liClass ) ? ' class="' . esc_attr( join(' ', $liClass ) ) . '"' : '';?>>
					<?php if( 'none' !== $instance['item_wrapper'] ){?>
					<<?php echo esc_attr($instance['item_wrapper'] );?> class="hentry">
					<?php }//end if none wrapper?>
						<?php if( in_array( $instance['style'], array( 'small','nothumb', 'number') ) || ( ( in_array( $instance['style'], array( 'mixed', 'thumb_first') ) ) && 1 !== $count ) ){?>
						<span class="entry-meta clearfix">
							<?php if( !empty( $instance['show_date'] ) ){?>
								<?php
								$time_string = '<time class="entry-date published pull-left" datetime="%1$s">%2$s</time>';
								if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
									$time_string .= '<time class="updated hidden" datetime="%3$s">%4$s</time>';
							
								$time_string = sprintf( $time_string,
									esc_attr( get_the_date( 'c' ) ),
									esc_html( get_the_date() ),
									esc_attr( get_the_modified_date( 'c' ) ),
									esc_html( get_the_modified_date() )
								);

								echo $time_string;
								?>
							
							<?php }?>

							<?php echo 'none' !== $instance['item_wrapper'] ? sprintf( '<span class="hidden"> by %s</span>', 
									sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
										esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
										esc_attr( sprintf( __( 'View all posts by %s', 'yeahthemes' ), get_the_author() ) ),
										esc_html( get_the_author() )
									) 
								) : '';
							?>
							<?php

							if( !empty( $instance['show_icon'] ) ){
								if( 'meta_value_num' == $instance['orderby'] && function_exists('yt_simple_post_views_tracker_display') ){
								echo '<span class="small gray-icon post-views pull-right" title="' . sprintf( __( '%d Views', 'yeahthemes') , number_format( yt_simple_post_views_tracker_display( get_the_ID(), false ) ) ). '">' . apply_filters('yt_icon_postviews', '<i class="fa fa-eye"></i>') . ' ';
									number_format( yt_simple_post_views_tracker_display( get_the_ID() ) );
								echo '</span>';	
								}else{
								echo '<span class="small gray-icon with-cmt pull-right">' . apply_filters('yt_icon_comment', '<i class="fa fa-comments"></i>') . ' ';
									comments_number( __( '0', 'yeahthemes' ), __( '1', 'yeahthemes' ), __( '%', 'yeahthemes' ));
								echo '</span>';
								}
							}
							
							?>
						</span>
						<?php
						}

						if( !in_array( $instance['style'], array( 'number', 'nothumb') ) && has_post_thumbnail() && get_the_post_thumbnail() ) :?>
							<?php if( ( 'thumb_first' == $instance['style'] && 1 == $count ) || in_array( $instance['style'], array( 'small', 'large', 'mixed' ) )):?>
								
								
								<div class="post-thumb<?php echo esc_attr('thumb_first' == $instance['style'] && 1 == $count || 'large' == $instance['style'] || ( 'mixed' == $instance['style'] && 1 == $count ) ? ' large' : ' small' );?>">
									
									
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

									<?php echo $cat_tag;?>
									<?php 
										if (function_exists('wp_review_show_total') && !empty( $instance['show_rating'] ) ) {
											$review_type = get_post_meta( $post->ID, 'wp_review_type', true );
												if( 'star' !== $review_type )
													wp_review_show_total(true, 'review-total-only review-mark'); 
										}
									?>
								</div>
							<?php 
							endif;
						endif;?>
						
						<?php 

						if( 'large' == $instance['style'] || ( in_array($instance['style'] , array( 'thumb_first', 'mixed') ) && 1 == $count ) ){?>
							<span class="entry-meta clearfix">
								<?php if( !empty( $instance['show_date'] ) ){
								
									$time_string = '<time class="entry-date published pull-left" datetime="%1$s">%2$s</time>';
									if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) )
										$time_string .= '<time class="updated hidden" datetime="%3$s">%4$s</time>';
								
									$time_string = sprintf( $time_string,
										esc_attr( get_the_date( 'c' ) ),
										esc_html( get_the_date() ),
										esc_attr( get_the_modified_date( 'c' ) ),
										esc_html( get_the_modified_date() )
									);

									echo $time_string;
									
									?>
								<?php }?>
								<?php echo 'none' !== $instance['item_wrapper'] ? sprintf( '<span class="hidden"> by %s</span>', 
										sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
											esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
											esc_attr( sprintf( __( 'View all posts by %s', 'yeahthemes' ), get_the_author() ) ),
											esc_html( get_the_author() )
										) 
									) : '';
								?>
								<?php

								if( !empty( $instance['show_icon'] ) ){
									if( 'meta_value_num' == $instance['orderby'] && function_exists('yt_simple_post_views_tracker_display') ){
									echo '<span class="small gray-icon post-views pull-right" title="' . esc_attr( sprintf( __( '%d Views', 'yeahthemes') , number_format( yt_simple_post_views_tracker_display( get_the_ID(), false ) ) ) ) . '">' . apply_filters('yt_icon_postviews', '<i class="fa fa-eye"></i>') . ' ';
										echo number_format( yt_simple_post_views_tracker_display( get_the_ID(), false ) ) ;
									echo '</span>';	
									}else{
									echo '<span class="small gray-icon with-cmt pull-right">' . apply_filters('yt_icon_comment', '<i class="fa fa-comments"></i>') . ' ';
										comments_number( __( '0', 'yeahthemes' ), __( '1', 'yeahthemes' ), __( '%', 'yeahthemes' ));
									echo '</span>';
									}

									
								}
								
								?>
							</span>
						<?php
						}

						if( ( in_array($instance['style'] , array( 'thumb_first', 'mixed') ) ) && 1 == $count){?>
							<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>" class="post-title">
								<?php the_title(); ?>
							</a>
						<?php
							if( 'large' !== $instance['style'] && $instance['excerpt'] ){
								$excerpt = get_the_excerpt();
								$excerpt_length = !empty( $instance['excerpt_length'] ) ? absint( $excerpt_length ) : 20;
								$excerpt_length = $excerpt_length ? $excerpt_length : 20;
								$trimmed_excerpt = wp_trim_words( $excerpt, $excerpt_length, '...');
								echo sprintf('<span class="clear">%s</span>', $trimmed_excerpt );

							}
							
						}else{
						?>
							<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="<?php echo esc_attr( strip_tags( get_the_title() ) ); ?>" class="post-title">
								<?php echo  'number' == $instance['style'] ? '<span class="gray-2-secondary number">' . ( $count < 10 ? '0'. $count : $count ) . '</span>' : '' ;?>
								<?php the_title(); ?>
							</a>
						<?php
						}

						if( 'large' == $instance['style'] && $instance['excerpt'] ){
							$excerpt = get_the_excerpt();
							$excerpt_length = !empty( $instance['excerpt_length'] ) ? absint( $excerpt_length ) : 20;
							$excerpt_length = $excerpt_length ? $excerpt_length : 20;
							$trimmed_excerpt = wp_trim_words( $excerpt, $excerpt_length, '...');
							echo sprintf('<span class="clear">%s</span>', $trimmed_excerpt );

						}
						?>
						
						<?php 
							if (function_exists('wp_review_show_total') && !empty( $instance['show_rating'] ) ) {
								$review_type = get_post_meta( $post->ID, 'wp_review_type', true );
									if( 'star' == $review_type )
										wp_review_show_total(true, 'review-total-only review-mark'); 
							}
						?>
					<?php if( 'none' !== $instance['item_wrapper'] ){?>
					</<?php echo esc_attr($instance['item_wrapper'] );?>>
					<?php }?>
				</li>
			<?php

				if( !empty( $instance['adscode'] ) && $count % intval( $instance['adscode_between'] ) == 0  )
					echo sprintf( '<li class="text-center ad-space">%s</li>', do_shortcode( $instance['adscode'] ) );
			endforeach;
			$count = 0;
			wp_reset_postdata();
			
			// Restore Global post
			$GLOBALS['post'] = $temp_post;
			//var_dump( YT_Post_Helpers::$listed_post );
		if( !empty( $instance['wrapper'] ) ):
		?>
		</ul>
		<?php
		endif;

		if( !empty( $instance['scroll_infinitely'] ) )
			echo '<div data-action="load-more-post" data-role="milestone" data-listed="' . esc_attr( join(',', $post_ids) ) .'"></div>';
	}
}

add_action( 'yt_ajax_yt-site-ajax-load-posts-infinitely', 'yt_site_ajax_load_posts_infinitely' );
add_action( 'yt_ajax_nopriv_yt-site-ajax-load-posts-infinitely', 'yt_site_ajax_load_posts_infinitely' );
/**
 * Endless scrolling for Post thumbnail widget via ajax
 * @since 1.0.4
 */
if ( !function_exists( 'yt_site_ajax_load_posts_infinitely') ) {
	# code...

	function yt_site_ajax_load_posts_infinitely(){
		if( empty( $_GET['data'] ) )
			return '';

		$data = stripslashes_deep( $_GET['data'] );
		$data['scroll_infinitely'] = false;
		$data['wrapper'] = false;

		$output = '';
		if( function_exists( 'yt_site_post_list' ) && is_callable( 'yt_site_post_list' ) ){
			ob_start();
				yt_site_post_list( $data );	
				
				$output .= ob_get_contents();
			ob_end_clean();
		}

		$return = array(
			'success' => true,
			'html'	=> $output ? $output : __( 'No more posts', 'yeahthemes'),
			'offset'		=> intval( $data['offset'] ) + intval($data['number']),
			'all_loaded' => $output ? false : true 
		);

		//
		wp_send_json( $return );
		
		//print_r( $data );
		die();
	}
}

/**
 * Gallery Settings for Media Uploader
 */
class YT_Theme_Gallery_Settings{
	function __construct() {
		

		if( class_exists( 'Jetpack_Tiled_Gallery' )  ){
			//echo 'Jetpack_Tiled_Gallery module is enabled';
			add_filter( 'jetpack_gallery_types', array( $this, 'jetpack_gallery_types' )  );

		}else{
			add_action( 'admin_init', array( $this, 'admin_init' ) );
		}
	}

	/**
	 * Jetpack compatibility
	 */
	function jetpack_gallery_types( $types ){
		$types['flexslider'] = __( 'Flexslider (Theme)', 'yeahthemes' );
		//$types['flexslider-3d'] = __( 'Flexslider (3d)', 'yeahthemes' );
		return $types;
	}

	/**
	 * Jetpack compatibility
	 */
	function admin_init() {
		$this->gallery_types = apply_filters( 'yt_media_gallery_setting_types', 
			array( 
				'default' => __( 'Thumbnail Grid', 'yeahthemes' ),
				'flexslider' => __( 'Flexslider (default)', 'yeahthemes' ),
				//'flexslider-3d' => __( 'Flexslider (3d)', 'yeahthemes' ),
			) 
		);

		// Enqueue the media UI only if needed.
		if ( count( $this->gallery_types ) > 1 ) {
			//add_action( 'wp_enqueue_media', array( $this, 'wp_enqueue_media' ) );
			add_action('admin_print_footer_scripts', array( $this, 'footer_scripts'));
			add_action( 'print_media_templates', array( $this, 'print_media_templates' ) );
		}
	}

	/**
	 * Registers/enqueues the gallery settings admin js.
	 */
	function footer_scripts() {
		if( !is_admin() )
			return;
		if ( ! wp_script_is( 'jetpack-gallery-settings', 'enqueued' ) ){
			//media-views
			?>
			<script type="text/javascript">
			(function($) {
				if( wp.media === undefined || wp.media === null ){
					
				}else{
					var media = wp.media;

					// Wrap the render() function to append controls.
					media.view.Settings.Gallery = media.view.Settings.Gallery.extend({
						render: function() {
							var $el = this.$el;

							media.view.Settings.prototype.render.apply( this, arguments );

							// Append the type template and update the settings.
							$el.append( media.template( 'yt-gallery-settings' ) );
							media.gallery.defaults.type = 'default'; // lil hack that lets media know there's a type attribute.
							this.update.apply( this, ['type'] );

							return this;
						}
					});
				}
			})(jQuery);
			</script>
			<?php
		}
	}

	/**
	 * Outputs a view template which can be used with wp.media.template
	 */
	function print_media_templates() {
		if( !is_admin() )
			return;
		$default_gallery_type = apply_filters( 'yt_default_gallery_type', 'default' );

		?>
		<script type="text/html" id="tmpl-yt-gallery-settings">
			<label class="setting">
				<span><?php _e( 'Type', 'yeahthemes' ); ?></span>
				<select class="type" name="type" data-setting="type">
					<?php foreach ( $this->gallery_types as $value => $caption ) : ?>
						<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $value, $default_gallery_type ); ?>><?php echo esc_html( $caption ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</script>
		<?php
	}
}
//Init
$GLOBALS['yt_theme_gallery_settings'] = new YT_Theme_Gallery_Settings;

/**
 * Override Default gallery shortcode
 */
add_filter( 'post_gallery', 'yt_site_gallery_shortcode', 1001/*trick Jetpack carousel*/ , 2);

function yt_site_gallery_shortcode( $output, $attr) {
	if( empty( $attr['type'] ) )
		$attr['type'] = 'default';

	if( !in_array( $attr['type'], array( 'flexslider') ) )
		return $output;

	$post = get_post();

	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) )
			$attr['orderby'] = 'post__in';
		$attr['include'] = $attr['ids'];
	}

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( ! $attr['orderby'] ) {
			unset( $attr['orderby'] );
		}
	}

	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post ? $post->ID : 0,
		'size'		 => 'thumbnail',
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
	), $attr, 'gallery' );

	$id = intval( $atts['id'] );
	if ( 'RAND' == $atts['order'] ) {
		$atts['orderby'] = 'none';
	}

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}
		return $output;
	}

	$selector = "gallery-{$instance}";

	$gallery_style = '';

	

	$slider_settings = apply_filters( 'yt_wp_default_gallery_settings', array(
		'directionNav' => true,
		'controlNav' => true,
		'pausePlay' => false,
		'animation' => 'slide',
		'slideshow' => true,
		'css3Effect'		=> '',
	), 'gallery');
	$class = "yeahslider gallery-{$attr['type']}";
	// class='gallery galleryid-{$id}'
	$gallery_div = "<div id='" . esc_attr( $selector ) . "' class='" .esc_attr( $class ) ."' data-settings='" . esc_attr( json_encode( $slider_settings ) ) . "'>
		<ul class='slides'>";

	/**
	 * Filter the default gallery shortcode CSS styles.
	 *
	 * @since 2.5.0
	 *
	 * @param string $gallery_style Default gallery shortcode CSS styles.
	 * @param string $gallery_div   Opening HTML div container for the gallery shortcode output.
	 */
	$output = apply_filters( 'gallery_style',  $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
			$image_output = wp_get_attachment_link( $id, $atts['size'], false, false );
		} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
			$image_output = wp_get_attachment_image( $id, $atts['size'], false );
		} else {
			$image_output = wp_get_attachment_link( $id, $atts['size'], true, false );
		}

		$image_output .= ( !empty( $attachment->post_excerpt ) ? sprintf( '<div class="thumbnail-caption">%s</div>', $attachment->post_excerpt ) : '' );
		$image_meta  = wp_get_attachment_metadata( $id );

		$orientation = '';
		if ( isset( $image_meta['height'], $image_meta['width'] ) ) {
			$orientation = ( $image_meta['height'] > $image_meta['width'] ) ? 'portrait' : 'landscape';
		}
		//  class='gallery-item {$orientation}'
		$output .= "<li>";
		$output .= $image_output;
		
		$output .= "</li>";
	}

	$output .= "</ul>
		</div>\n";

	return $output;
}


//add_filter( 'jp_carousel_force_enable', function(){return true;});
//add_filter( 'yt_gallery_wp_get_attachment_link', 'yt_site_add_data_to_gallery_images' ,10, 2);
 
if ( ! function_exists( 'yt_site_add_data_to_gallery_images' ) ) {
	function yt_site_add_data_to_gallery_images( $html, $attachment_id ) {
		
		//if ( $this->first_run ) // not in a gallery
			//return $html;

		$attachment_id   = intval( $attachment_id );
		$orig_file       = wp_get_attachment_image_src( $attachment_id, 'full' );
		$orig_file       = isset( $orig_file[0] ) ? $orig_file[0] : wp_get_attachment_url( $attachment_id );
		$meta            = wp_get_attachment_metadata( $attachment_id );
		$size            = isset( $meta['width'] ) ? intval( $meta['width'] ) . ',' . intval( $meta['height'] ) : '';
		$img_meta        = ( ! empty( $meta['image_meta'] ) ) ? (array) $meta['image_meta'] : array();
		$comments_opened = intval( comments_open( $attachment_id ) );

		/*
		 * Note: Cannot generate a filename from the width and height wp_get_attachment_image_src() returns because
		 * it takes the $content_width global variable themes can set in consideration, therefore returning sizes
		 * which when used to generate a filename will likely result in a 404 on the image.
		 * $content_width has no filter we could temporarily de-register, run wp_get_attachment_image_src(), then
		 * re-register. So using returned file URL instead, which we can define the sizes from through filename
		 * parsing in the JS, as this is a failsafe file reference.
		 *
		 * EG with Twenty Eleven activated:
		 * array(4) { [0]=> string(82) "http://vanillawpinstall.blah/wp-content/uploads/2012/06/IMG_3534-1024x764.jpg" [1]=> int(584) [2]=> int(435) [3]=> bool(true) }
		 *
		 * EG with Twenty Ten activated:
		 * array(4) { [0]=> string(82) "http://vanillawpinstall.blah/wp-content/uploads/2012/06/IMG_3534-1024x764.jpg" [1]=> int(640) [2]=> int(477) [3]=> bool(true) }
		 */

		$medium_file_info = wp_get_attachment_image_src( $attachment_id, 'medium' );
		$medium_file      = isset( $medium_file_info[0] ) ? $medium_file_info[0] : '';

		$large_file_info  = wp_get_attachment_image_src( $attachment_id, 'large' );
		$large_file       = isset( $large_file_info[0] ) ? $large_file_info[0] : '';

		$attachment       = get_post( $attachment_id );
		$attachment_title = wptexturize( $attachment->post_title );
		$attachment_desc  = wpautop( wptexturize( $attachment->post_content ) );

		// Not yet providing geo-data, need to "fuzzify" for privacy
		if ( ! empty( $img_meta ) ) {
			foreach ( $img_meta as $k => $v ) {
				if ( 'latitude' == $k || 'longitude' == $k )
					unset( $img_meta[$k] );
			}
		}

		$img_meta = json_encode( array_map( 'strval', $img_meta ) );

		$html = str_replace(
			'<img ',
			sprintf(
				'<img data-attachment-id="%1$d" data-orig-file="%2$s" data-orig-size="%3$s" data-comments-opened="%4$s" data-image-meta="%5$s" data-image-title="%6$s" data-image-description="%7$s" data-medium-file="%8$s" data-large-file="%9$s" ',
				esc_attr( $attachment_id ),
				esc_attr( $orig_file ),
				esc_attr( $size ),
				esc_attr( $comments_opened ),
				esc_attr( $img_meta ),
				esc_attr( $attachment_title ),
				esc_attr( $attachment_desc ),
				esc_attr( $medium_file ),
				esc_attr( $large_file )
			),
			$html
		);

		$html = apply_filters( 'jp_carousel_add_data_to_images', $html, $attachment_id );

		return $html;
	}
}
/*
 * Social Sharing buttons
 * @since 1.0.0
 */

function yt_site_social_sharing_buttons( $_styles = array(), $_services = array(), $_ex_class='', $_wrapper = 'div', $atts = true, $_echo = true ){

	$styles = wp_parse_args( $_styles, array(
		'style' => 'color',
		'size'	=> 'large',
		'counter' => false
	) );

	$_wrapper = $_wrapper ? $_wrapper : 'div';
	
	$id = get_the_ID();
	$url = apply_filters( 'yt_site_sharing_buttons_link', get_permalink( $id) );
	$title = get_the_title( $id);
	$thumb = wp_get_attachment_url( get_post_thumbnail_id( $id ) );	

	$attr = 'data-url="' . esc_url( $url ) . '" data-title="' . esc_attr( $title ) . '" data-source="' . esc_url( home_url('/') ) . '"';
	$attr .= $thumb ? ' data-media="' . esc_url( $thumb ) . '"' : '';

	/*apply_filters( 'yt_site_social_sharing_services_styles', array(
		'style' => 'color',
		'size'	=> 'large'
	), 'style2' )*/
	
	if( !empty( $_services )){
		foreach ( ( array )$_services as $_s => $_a) {
			# code...
			if( is_array( $_a ) )
				$services[$_s] = wp_parse_args( $_a, array(
					'icon' => '',
					'title' => '',
					'show' => false,
					'label' => false,
					'via' => ''
				) );
		}
	}

	$services = apply_filters( 'yt_site_social_sharing_services', $services );

	$social_services_class = array();
	$social_services_class[] = 'social-share-buttons';
	if($_ex_class) $social_services_class[] = $_ex_class;

	foreach ( (array) $_styles as $key => $value) {
		
		if( $key == 'counter' ){
			if( $value )
				$social_services_class[] = 'with-counter';

		}
		else{
			$social_services_class[] = "$key-$value";
		}
	}
	/**
	 * Counter
	 */
	$results = array();
	/*if( $styles['counter'] ):

		$transient_name = "yt_share_btn_count_{$id}";

		//var_dump( get_transient( $transient_name ) );
		if ( false === ($results = get_transient( $transient_name ) ) ) {

			$api_url = 'http' . ( is_ssl() ? 's' : '' ) . '://api.sharedcount.com' . '/?url=' . urlencode( $url );

			$response = wp_remote_get( $api_url, array( 'sslverify'   => true ) );

			$body = '';
			if ( is_wp_error( $response ) ) {
				$error_message = $response->get_error_message();
				//echo sprintf( __('Something went wrong: %s', 'yeahthemes' ), $error_message );
			} else {
				$body = wp_remote_retrieve_body( $response );
			}

			$results = json_decode( $body, true );
			// 1 hour 
			set_transient( $transient_name, $results, 60*60 );
		

		}else{
		}

		if( !empty( $results ) ){

			//if( isset( $body_temp[] ))

			if( !empty( $services['twitter'] ) && !empty( $results['Twitter'] ) )
				$services['twitter']['count'] = $results['Twitter'];
			
			if( !empty( $services['facebook'] ) && !empty( $results['Facebook']['total_count'] ) )
				$services['facebook']['count'] = $results['Facebook']['total_count'];
			
			if( !empty( $services['google-plus'] ) && !empty( $results['GooglePlusOne'] ) )
				$services['google-plus']['count'] = $results['GooglePlusOne'];
			
			if( !empty( $services['pinterest'] ) && !empty( $results['Pinterest'] ) )
				$services['pinterest']['count'] = $results['Pinterest'];
			
			if( !empty( $services['linkedin'] ) && !empty( $results['LinkedIn'] ) )
				$services['linkedin']['count'] = $results['LinkedIn'];
			
			if( !empty( $services['delicious'] ) && !empty( $results['Delicious'] ) )
				$services['delicious']['count'] = $results['Delicious'];
			
			if( !empty( $services['stumble-upon'] ) && !empty( $results['StumbleUpon'] ) )
				$services['stumble-upon']['count'] = $results['StumbleUpon'];
			
			if( !empty( $services['reddit'] ) && !empty( $results['Reddit'] ) )
				$services['reddit']['count'] = $results['Reddit'];
			
			if( !empty( $services['diggs'] ) && !empty( $results['Diggs'] ) )
				$services['diggs']['count'] = $results['Diggs'];
		}
	endif;
*/
	$services = apply_filters( 'yt_site_global_sharing_buttons', $services, $results );

		$output = '';
		$count = 0;
		foreach ((array) $services as $key => $value) {
			$count++;
			$label_class = 'hidden-xs';
			$label_class .= $value['label'] ? '' : ' hidden';
			$label_class = ' class="'. esc_attr( $label_class ) . '"';

			$span_class = strtolower( $key );
			$span_class .= $value['show'] ? '' : ' hidden';

			$child = 'ul' == $_wrapper ? 'li' : 'span';
			$num = 0;
			if( $styles['counter'] && isset( $value['count'] ) ){
				$num = $value['count'];
				$num = yt_beautify_number( $num );
			}
			$output .= '';
			# code...

			$output .= sprintf( '<%1$s%2$s class="%3$s"%4$s data-service="%5$s" data-show="%6$s"%7$s>%8$s%9$s</%1$s>',
				$child,
				// title
				( !empty( $value['title'] ) ? ' title="'.esc_attr( $value['title'] ).'"' : '' ),
				// Class
				esc_attr( $span_class ),
				// Via
				( !empty( $value['via'] ) ? ' data-via="'.esc_attr( $value['via'] ).'"' : '' ),
				// Sevice
				esc_attr( strtolower( $key ) ),
				// Show on init
				esc_attr( $value['show'] ? 'true' : 'false' ),
				// Settings
				( !empty( $value['settings'] ) ? ' data-settings="' . esc_attr( json_encode( $value['settings'] ) ).'"' : '' ),
				// Icon
				( !empty( $value['icon'] ) ? '<i class="'. esc_attr( $value['icon']  ).'"></i>' : ''),
				// label
				( !empty( $value['title'] ) ? '<label'. $label_class .'>'.esc_html( $value['title'] ).'</label>' : '' )
					// Counter
					. ( 'more' !== $key && $styles['counter'] && isset( $num ) ? '<span class="counter" style="display:none;">' . esc_html( $num ) . '</span>' : '' )
			);
		}


	$template = '<'.$_wrapper.' class="' . esc_attr( join(' ', $social_services_class) ) . '"' . ( $atts ? " $attr" : '' ) . '>%s</'.$_wrapper.'>';
	
	if( $_echo )
		echo sprintf( $template, $output );
	else
		return sprintf( $template, $output );
		
}

/**
 * Site social media networks
 *
 * @since 1.0
 */
	function yt_get_social_networks_args(){
		$yt_data = yt_get_options();

		$network_array = apply_filters( 'yt_site_social_networks_args', array( 
			'facebook' => array(
				'title' => __('Facebook', 'yeahthemes'),
				'icon' => 'fa fa-facebook',
				'url' => $yt_data['scl_facebook']
			), 
			'twitter' => array(
				'title' => __('Twitter', 'yeahthemes'),
				'icon' => 'fa fa-twitter',
				'url' => $yt_data['scl_twitter']
			), 
			'instagram' => array(
				'title' => __('Instagram', 'yeahthemes'),
				'icon' => 'fa fa-instagram',
				'url' => $yt_data['scl_instagram']
			),
			'pinterest' => array(
				'title' => __('Pinterest', 'yeahthemes'),
				'icon' => 'fa fa-pinterest',
				'url' => $yt_data['scl_pinterest']
			),
			'bloglovin' => array(
				'title' => __('Bloglovin', 'yeahthemes'),
				'icon' => 'fa fa-heart',
				'url' => $yt_data['scl_bloglovin']
			),
			'googleplus' => array(
				'title' => __('Google+', 'yeahthemes'), 
				'icon' => 'fa fa-google-plus',
				'url' => $yt_data['scl_googleplus']
			),
			'youtube' => array(
				'title' => __('Youtube', 'yeahthemes'), 
				'icon' => 'fa fa-youtube-play',
				'url' => $yt_data['scl_youtube']
			),
			'vimeo' => array(
				'title' => __('Vimeo', 'yeahthemes'),
				'icon' => 'fa fa-vimeo-square',
				'url' => $yt_data['scl_vimeo']
			),
			'dribbble' => array(
				'title' => __('Dribbble', 'yeahthemes'),
				'icon' => 'fa fa-dribbble',
				'url' => $yt_data['scl_dribbble']
			),
			'email' => array(
				'title' => __('Send an Email', 'yeahthemes'), 
				'icon' => 'fa fa-envelope',
				'url' => $yt_data['scl_email']
			),
			'rss' => array(
				'title' => __('RSS Feed', 'yeahthemes'),
				'icon' => 'fa fa-rss',
				'url' => $yt_data['scl_rss']
			),
		) );


		return $network_array;
	}
if ( ! function_exists( 'yt_site_social_networks' ) ) {
	 
	function yt_site_social_networks( $args = array()){
		
		$args = wp_parse_args( $args, array( 
			'template' => '%s', 
			'show_title' => false, 
			'echo' => false,
			'link_before' => '',
			'link_after' => '',
		) );
		
		$network_array = yt_get_social_networks_args();
		
		$social_networks ='';

		if( false === strpos( $args['template'], '%s' ) ){
			$args['template'] = '%s';
		}

		foreach( $network_array as $network	 => $settings ){

			if( !empty( $settings['url'] ) ){
				if( 'email' == $network ){
					$value = esc_attr( 'mailto:' . antispambot( $settings['url'] ) );
				}else{
					$value = esc_url( $settings['url'] );
				}

				$social_networks .= $args['link_before'];
				$social_networks .= sprintf('<a href="%s" class="%s" target="_blank" title="%s"><i class="%s"></i>%s</a>',
					$value ,
					esc_attr( $network ),
					esc_attr( $settings['title'] ),
					esc_attr( $settings['icon'] ),
					$args['show_title'] ? $settings['title'] : '' 
				);
				$social_networks .= $args['link_after'];
			}
		}

		if( !$args['echo']  )
			return sprintf( $args['template'], $social_networks );
		else
			echo sprintf( $args['template'], $social_networks );
	}
}
/*
 * Get current page layout
 * @since 1.0
 */
function yt_site_get_current_page_layout(){
	$theme_layouts = array(
		'default', 
		'right-sidebar',
		'left-sidebar',
		'fullwidth',
	);
	$general_layout = yt_get_options('layout');
	$general_layout = in_array( $general_layout, $theme_layouts ) ? $general_layout : 'default';
	$page_layout = $general_layout;
	$conditional = is_page() || ( (is_home() || is_single()) && get_option( 'page_for_posts' ) ) || yt_is_woocommerce();

	if( apply_filters( 'yt_site_get_current_page_layout_conditional', $conditional ) ){

		if( !empty( $GLOBALS['post'] )){
			$post = $GLOBALS['post'];
			$post_id = $post->ID;
		}else{
			$post_id = 0;
		}
		
		if( ( is_home() || is_single() ) && get_option( 'page_for_posts' )  )
			$post_id = get_option( 'page_for_posts' );

		if( yt_is_woocommerce() && function_exists('wc_get_page_id') && wc_get_page_id('shop'))
			$post_id = wc_get_page_id('shop');

		/*Retrieve page layout from meta key*/ 
		$page_layout = get_post_meta( $post_id, 'yt_page_sidebar_layout', true );

		
		
		if( !$page_layout || 'default' == $page_layout )
			$page_layout = $general_layout;


		
	}
	return apply_filters( 'yt_site_get_current_page_layout', $page_layout );
}


function yt_instagram_feed( $id = '', $count = 6, $profile = false, $size = 'low_resolution', $dimension = 150 ){

	$ig_id = $id ? $id : yt_get_options('instagram_user_id');

	if(empty( $ig_id ) )
		return;

	$size = !in_array( $size, array( 'low_resolution', 'thumbnail', 'standard_resolution') ) ? 'low_resolution' : $size;
	$ig_access_token = yt_get_options('instagram_access_token');
	$transient_name = "yt_igfeed_{$ig_id}{$count}";

	if( empty( $ig_id ) || empty( $ig_access_token ) )
		return;

	if ( false === ($results = get_transient( $transient_name ) ) ) {

		$api_url = "https://api.instagram.com/v1/users/{$ig_id}/media/recent/?access_token={$ig_access_token}&count={$count}";
		$response = wp_remote_get( $api_url, array( 'sslverify'   => true ) );

		$body = '';
		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			//echo sprintf( __('Something went wrong: %s', 'yeahthemes' ), $error_message );
		} else {
			$body = wp_remote_retrieve_body( $response );
		}

		$data = json_decode( $body, true );
		if( !empty( $data['data'] )){
			foreach ($data['data'] as $k => $v) {
				if( isset( $data['data'][$k]['comments'] ) )
					unset( $data['data'][$k]['comments'] );
				if( isset( $data['data'][$k]['location'] ) )
					unset( $data['data'][$k]['location'] );
				if( isset( $data['data'][$k]['likes'] ) )
					unset( $data['data'][$k]['likes'] );
				if( isset( $data['data'][$k]['users_in_photo'] ) )
					unset( $data['data'][$k]['users_in_photo'] );
				if( isset( $data['data'][$k]['user_has_liked'] ) )
					unset( $data['data'][$k]['user_has_liked'] );
				
				# code...
			}
		}

		//print_r( $data );
		if( !empty( $data['data'] ) ){
		// 1 hour 
			$results = yt_encode( maybe_serialize( $data['data'] ) );
			//$results = $data['data'];
			set_transient( $transient_name, $results, 60*60 );
		}
	

	}else{
		
	}
	$feed = array();
	//print_r( $results );
	$info = array();

	if( $results ){

		$results = maybe_unserialize( yt_decode( $results ) );
		
		// Support Lazyload plugin
		if ( class_exists( 'LazyLoad_Images' ) ) {
			
			$template = '<div data-lazy-src="%s"></div>';
			
		}else{
			$template = '<div style="background-image:url(%s);"></div>';
		}

		foreach ( $results as $key => $value ) {
			
				# code...
				// $media = sprintf('<img src="%s" alt="%s" width="%s" height="%s" />', 
				// 	!empty( $value['images'][$size]['url'] ) ? esc_url( $value['images'][$size]['url'] ) : '', 
				// 	!empty( $value['caption']['text'] ) ? esc_attr( $value['caption']['text'] ) : '', 
				// 	$dimension, 
				// 	+$dimension );
				$media = sprintf($template, 
					!empty( $value['images'][$size]['url'] ) ? esc_url( $value['images'][$size]['url'] ) : '' );

				$feed[] = sprintf( '<a target="_blank" href="%s" title="%s">%s</a>',
					!empty( $value['link'] ) ? esc_url( $value['link'] ) : '',
					!empty( $value['caption']['text'] ) ? esc_attr( $value['caption']['text'] ) : '',
					$media
				);

				if( empty( $info ) && array_key_exists( 'user', $value ) )
					$info = $value['user'];
			
		}
		
	}else{
		$feed[] = esc_html__('Something went wrong while retrieving feed, please refresh browser to try again. Also make sure your instagram info is corrected.');
	}

	$info = wp_parse_args( $info, array(
		'profile_picture' => '',
		'full_name' => '',
		'username' => ''
	) );


	$output = '';
	
	if( $profile ){
		$output .= '<div class="ig-user-info">'; 

			$output .= sprintf( '<img src="%s" alt="%s" width="80" height="80" />', esc_url( $info['profile_picture'] ), esc_attr( $info['full_name'] ) );
			//$output .= '<span>' . esc_html( $info['full_name'] ) . '</span>';
			$output .= sprintf('<a href="%s" title="%s"><i class="fa fa-instagram"></i>%s</a>',
				esc_url( "instagram.com/{$info['username']}" ),
				esc_attr( $info['full_name'] ),
				esc_attr( "@{$info['username']}" )
			);
		
		$output .= '</div>';
	}

	$output .= '<div class="ig-user-feed">';
		shuffle( $feed );
		$output .= join('', $feed );
	$output .= '</div>';
	echo sprintf('<div class="ig-feed-wrapper">%s</div>', $output );
}

/**
 * The Elegancé Featured Article
 */
class YT_Site_Featured_Articles {

	/**
	 * The maximum number of posts a Featured Content area can contain.
	 *
	 * We define a default value here but themes can override
	 * this by defining a "max_posts" entry in the second parameter
	 * passed in the call to add_theme_support( 'featured-content' ).
	 *
	 * @see Featured_Content::init()
	 *
	 * @since The Elegancé 1.0
	 *
	 * @static
	 * @access public
	 * @var int
	 */
	public static $max_posts = 15;

	private static $_this;

	public static $settings = array();

	/**
	 * Instantiate.
	 *
	 * All custom functionality will be hooked into the "init" action.
	 *
	 * @static
	 * @access public
	 * @since The Elegancé 1.0
	 */
	public static function setup( $settings ) {
		self::$settings = $settings;
		// echo self::get_current_lang();
		self::init();
	}

	public static function get_current_lang(){
		$lang = '';
		if( defined( 'ICL_LANGUAGE_CODE' ) && isset( $GLOBALS['sitepress'] ) && ICL_LANGUAGE_CODE !== $GLOBALS['sitepress']->get_default_language() ) {
	
			$lang = '_' . ICL_LANGUAGE_CODE;
			
		}

		return $lang;
	}

	/**
	 * Conditionally hook into WordPress.
	 *
	 * Theme must declare that they support this module by adding
	 * add_theme_support( 'featured-content' ); during after_setup_theme.
	 *
	 * If no theme support is found there is no need to hook into WordPress.
	 * We'll just return early instead.
	 *
	 * @static
	 * @access public
	 * @since The Elegancé 1.0
	 */
	public static function init() {

		add_action( 'switch_theme',                       array( __CLASS__, 'delete_transient'   )    );
		add_action( 'save_post',                          array( __CLASS__, 'delete_transient'   )    );
		add_action( 'ytto_after_option_data_saved',       array( __CLASS__, 'delete_transient'   )    );
		add_action( 'pre_get_posts',                      array( __CLASS__, 'pre_get_posts'      )    );
	}
	/**
	 *
	 * @static
	 * @access public
	 * @since The Elegancé 1.0.1
	 */
	function __construct(){
		self::$_this = $this;
	}

	static function this() {
		
    	return self::$_this;
 	}

	/**
	 * Get featured posts.
	 *
	 * @static
	 * @access public
	 * @since The Elegancé 1.0
	 *
	 * @return array Array of featured posts.
	 */
	public static function get_featured_posts() {
		$post_ids = self::get_featured_post_ids();


		// No need to query if there is are no featured posts.
		if ( empty( $post_ids ) ) {
			return array();
		}

		$featured_posts = get_posts( array(
			'include'        => $post_ids,
			'posts_per_page' => count( $post_ids ),
			'suppress_filters' => false
		) );

		return $featured_posts;
	}

	/**
	 * Get featured post IDs
	 *
	 * This function will return the an array containing the
	 * post IDs of all featured posts.
	 *
	 * Sets the "featured_article_ids".self::get_current_lang() transient.
	 *
	 * @static
	 * @access public
	 * @since The Elegancé 1.0
	 *
	 * @return array Array of post IDs.
	 */
	public static function get_featured_post_ids() {
		// Get array of cached results if they exist.
		$featured_ids = get_transient( 'featured_article_ids'.self::get_current_lang() ); 

		// print_r( $featured_ids );

		if ( false === $featured_ids ) {
			$settings = self::$settings;
			
			// if( empty( $settings['cat'] ) || empty( $settings['tag']) )
			// 	return array();

			if ( !empty( $settings ) ) {



				$posts_per_page = !empty( $settings['posts_per_page'] ) ? intval( $settings['posts_per_page'] ) : 5;

				if( !empty( $settings['layout_type'] ) ){

					if( 'default' == $settings['layout_type'] )
						$posts_per_page = 1;
					else if( 'static-alt' == $settings['layout_type'] )
						$posts_per_page = 2;
					else if( 'blocks' == $settings['layout_type'] )
						$posts_per_page = 5;
					else if( 'blocks-alt' == $settings['layout_type'] )
						$posts_per_page = 3;
				}

				$args = array(
					'fields'           => 'ids',
					//'numberposts'      => self::$max_posts,
					'category__in' => !empty( $settings['cat'] ) ? explode( ',', $settings['cat'] ) : array(),
					// 'cat' => !empty( $settings['cat'] ) && !is_array( $settings['cat'] ) ? $settings['cat'] : '',
					'tag__in' => !empty( $settings['tag']) ? explode( ',', $settings['tag'] ) : array(), 
					'posts_per_page' => $posts_per_page,
					'order' => !empty( $settings['order'] ) ? $settings['order'] : 'DESC', 
					'orderby' => !empty( $settings['orderby'] ) ? $settings['orderby'] : 'date',
					// 'ignore_sticky_posts' => true,
					'category__not_in' => !empty( $settings['cat_not_in'] ) ? explode( ',', $settings['cat_not_in'] ) : array(),
					'tag__not_in' => !empty( $settings['tag_not_in'] ) ? explode( ',', $settings['tag_not_in'] ) : array(),
					'suppress_filters' => false
				);
				
				if( !empty( $settings['exclude_format'] ) ){
					$exclude_format_temp = array();
					foreach( $settings['exclude_format'] as $format ){
						$exclude_format_temp[] = "post-format-$format";
					}

					$args['tax_query'][] = array(
					    array(
					      'taxonomy' 	=> 'post_format',
					      'field' 		=> 'slug',
					      'terms' 		=> $exclude_format_temp,
					      'operator' 	=> 'NOT IN'
					    )
					);
				}

				$featured_ids = get_posts( apply_filters( 'yt_get_featured_post_ids_args', $args ) );
			}

			// Get sticky posts if no Featured Content exists.
			if ( ! $featured_ids ) {
				$featured_ids = self::get_sticky_posts();
			}

			set_transient( 'featured_article_ids'.self::get_current_lang(), $featured_ids, HOUR_IN_SECONDS );
		}

		$featured_ids = apply_filters( 'yt_before_return_featured_post_ids', $featured_ids );

		// Ensure correct format before return.
		return array_map( 'absint', $featured_ids );
	}

	/**
	 * Return an array with IDs of posts maked as sticky.
	 *
	 * @static
	 * @access public
	 * @since The Elegancé 1.0
	 *
	 * @return array Array of sticky posts.
	 */
	public static function get_sticky_posts() {
		return array_slice( get_option( 'sticky_posts', array() ), 0, self::$max_posts );
	}

	/**
	 * Delete featured content ids transient.
	 *
	 * Hooks in the "save_post" action.
	 *
	 * @see Featured_Content::validate_settings().
	 *
	 * @static
	 * @access public
	 * @since The Elegancé 1.0
	 */
	public static function delete_transient() {
		delete_transient( 'featured_article_ids'.self::get_current_lang() );
	}

	/**
	 * Exclude featured posts from the home page blog query.
	 *
	 * Filter the home page posts, and remove any featured post ID's from it.
	 * Hooked onto the 'pre_get_posts' action, this changes the parameters of
	 * the query before it gets any posts.
	 *
	 * @static
	 * @access public
	 * @since The Elegancé 1.0
	 *
	 * @param WP_Query $query WP_Query object.
	 * @return WP_Query Possibly-modified WP_Query.
	 */
	public static function pre_get_posts( $query ) {
		// add_filter( 'yt_exclude_featured_post_ids', '__return_false' );
		
		if( !apply_filters( 'yt_exclude_featured_post_ids', true ) )
			return;

		// Bail if not home or not main query.
		if ( ! $query->is_home() || ! $query->is_main_query() ) {
			return;
		}

		$featured = self::get_featured_post_ids();

		// Bail if no featured posts.
		if ( ! $featured ) {
			return;
		}

		// We need to respect post ids already in the blacklist.
		$post__not_in = $query->get( 'post__not_in' );

		if ( ! empty( $post__not_in ) ) {
			$featured = array_merge( (array) $post__not_in, $featured );
			$featured = array_unique( $featured );
		}

		$query->set( 'post__not_in', $featured );
	}

	
} // Featured_Content