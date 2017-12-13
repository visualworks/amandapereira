<?php
/**
 *	Plugin Name: Posts with thumbnail
 *	Description: Display Posts with thumbnail
 */
// This is required to be sure Walker_Category_Checklist class is available
//require_once ABSPATH . 'wp-admin/includes/template.php';

class YT_Posts_With_Thubnail_Widget extends WP_Widget {

	/**
	 * Widget Setup
	 */
 	function __construct() {
		
		$widget_ops = array(
			'classname' => 'yt-posts-with-thumbnail-widget yt-widget',
			'description' => __('Display Posts with thumbnail', 'yeahthemes')
		);
		
		
		// Widget control settings
		$control_ops = array(
			'id_base' => 'yt-posts-with-thumnail-widget',
		);
		
		parent::__construct( 
			'yt-posts-with-thumnail-widget', 
			__('(Theme) Posts with thumbnail', 'yeahthemes'), 
			$widget_ops, 
			$control_ops);

	}
	
	/**
	 * Display Widget
	 */
	function widget( $args, $instance ) {
		
		// outputs the content of the widget

		extract( $args );
		
		// Our variables from the widget settings
		$title = apply_filters('widget_title', !empty( $instance['title'] ) ? $instance['title'] : '', $instance, $this->id_base );
		
		echo !empty( $before_widget ) ? $before_widget : '';
		
		//Echo widget title
		echo !empty(  $title ) ? $before_title . $title . $after_title : '';
		
		
		$instance['item_wrapper'] = 'none';
		if( function_exists( 'yt_site_post_list') ){
			echo '<div>';
				yt_site_post_list( $instance );
			echo '</div>';
		}
		?>
		<?php
		echo !empty( $after_widget ) ? $after_widget : '';
	}

	/**
	 * Widget Settings
	 */
	function form($instance) {
		
		// Set up some default widget settings
		$defaults = array(
			'title' => __( 'Popular Posts', 'yeahthemes' ),
			'category' => array(),
			'show_icon' => 0,
			'show_cat'	=> 0,
			'show_date' => 0,
			'show_rating' => 0,
			'scroll_infinitely' => 0,
			'order' => 'DESC',
			'orderby' => 'comment_count',
			'time_period' => 'default',
			'style' => 'small',
			'tags' => array(),
			'number' => 5,
			'adscode' => '',
			'adscode_between' => 5
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		$number   = isset( $instance['number'] ) ? absint( $instance['number'] ) : 6;
		?>
		
		<p><label><?php _e('Title:', 'yeahthemes'); ?> <input class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" /></label></p>
		
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id('category') ); ?>"><strong><?php esc_html_e( 'Category:', 'yeahthemes' ); ?></strong></label>
		<?php

		if( class_exists( 'YT_Widget_Framework' ) ){ 
			$field_args = array(
				'id' => $this->get_field_id('category'),
				'name' => $this->get_field_name('category'),
				'data_name' => 'widget-' . $this->id_base . '[' . $this->number . '][category]',
				'value' => !empty( $instance['category'] ) ? $instance['category'] : '',
				'settings' => array(
					'tax' => 'category'
				)
			);

			echo YT_Widget_Framework::field('tag_search', $field_args ); 
		}
		?>
		</p>
		<p><em><?php _e('Select categories you wish to show, if no categories selected, show all!','yeahthemes')?></em></p>
		<p>
			<label for="<?php echo esc_html( $this->get_field_id('tags') ); ?>"><strong><?php esc_html_e( 'Tagged In (optional):', 'yeahthemes' ); ?></strong></label>
			<?php
			if( class_exists( 'YT_Widget_Framework' ) ){ 
				$field_args = array(
					'id' => $this->get_field_id('tags'),
					'name' => $this->get_field_name('tags'),
					'data_name' => 'widget-' . $this->id_base . '[' . $this->number . '][tags]',
					'value' => !empty( $instance['tags'] ) ? $instance['tags'] : '',
					'settings' => array()
				);

				echo YT_Widget_Framework::field('tag_search', $field_args ); 
			}
			?>
		</p>
		<p><em><?php esc_html_e('Specify tags to retrieve posts from. Keep typing and you will be suggested','yeahthemes')?></em></p>
		<p>
			<label><strong><?php _e( 'Thumbnail style:', 'yeahthemes' ); ?></strong></label>
			<select name="<?php echo esc_attr( $this->get_field_name('style') ); ?>" id="<?php echo esc_attr( $this->get_field_id('style') ); ?>" class="widefat">
				<option value="small"<?php selected( $instance['style'], 'small' ); ?>><?php esc_html_e('Default (Small)', 'yeahthemes'); ?></option>
				<option value="large"<?php selected( $instance['style'], 'large' ); ?>><?php esc_html_e('Large', 'yeahthemes'); ?></option>
				<option value="mixed"<?php selected( $instance['style'], 'mixed' ); ?>><?php esc_html_e('First Large', 'yeahthemes'); ?></option>
				<option value="number"<?php selected( $instance['style'], 'number' ); ?>><?php esc_html_e('Number (no thumb)', 'yeahthemes'); ?></option>
				<option value="nothumb"<?php selected( $instance['style'], 'nothumb' ); ?>><?php esc_html_e('Title Only', 'yeahthemes'); ?></option>
				<option value="thumb_first"<?php selected( $instance['style'], 'thumb_first' ); ?>><?php esc_html_e('First item have thumbnail', 'yeahthemes'); ?></option>
			</select></label>
		</p>
		
		<p>
			<label><strong><?php _e( 'Order:', 'yeahthemes' ); ?></strong>
			<select name="<?php echo esc_attr( $this->get_field_name('order') ); ?>" id="<?php echo esc_attr( $this->get_field_id('order') ); ?>" class="widefat">
				<option value="DESC"<?php selected( $instance['order'], 'DESC' ); ?>><?php esc_html_e('Descending', 'yeahthemes'); ?></option>
				<option value="ASC"<?php selected( $instance['order'], 'ASC' ); ?>><?php esc_html_e('Ascending', 'yeahthemes'); ?></option>
			</select></label>
		</p>
		
		<p>
			<label><strong><?php _e( 'Order by:', 'yeahthemes' ); ?></strong>
			<select name="<?php echo esc_attr( $this->get_field_name('orderby') ); ?>" id="<?php echo esc_attr( $this->get_field_id('orderby') ); ?>" class="widefat">
				
				<option value="date"<?php selected( $instance['orderby'], 'date' ); ?>><?php esc_html_e('Date', 'yeahthemes'); ?></option>
				<option value="title"<?php selected( $instance['orderby'], 'title' ); ?>><?php esc_html_e('Title', 'yeahthemes'); ?></option>
				<option value="name"<?php selected( $instance['orderby'], 'name' ); ?>><?php esc_html_e( 'Post slug' , 'yeahthemes'); ?></option>
				<option value="author"<?php selected( $instance['orderby'], 'author' ); ?>><?php esc_html_e( 'Author' , 'yeahthemes'); ?></option>
				<option value="comment_count"<?php selected( $instance['orderby'], 'comment_count' ); ?>><?php esc_html_e( 'Number of comments' , 'yeahthemes'); ?></option>
				<option value="modified"<?php selected( $instance['orderby'], 'modified' ); ?>><?php esc_html_e( 'Last modified date' , 'yeahthemes'); ?></option>
				<option value="rand"<?php selected( $instance['orderby'], 'rand' ); ?>><?php esc_html_e( 'Random order' , 'yeahthemes'); ?></option>
			
				<?php if( function_exists('yt_simple_post_views_tracker_display') ){ ?>
				<option value="meta_value_num"<?php selected( $instance['orderby'], 'meta_value_num' ); ?>><?php esc_html_e('Post views', 'yeahthemes'); ?></option>
				<?php } ?>
			</select></label>
		</p>
		<p>
			<label><strong><?php _e( 'Time period:', 'yeahthemes' ); ?></strong>
			<select name="<?php echo esc_attr( $this->get_field_name('time_period') ); ?>" id="<?php echo esc_attr( $this->get_field_id('time_period') ); ?>" class="widefat">
				<option value="default"<?php selected( $instance['time_period'], 'default' ); ?>><?php esc_html_e('Default', 'yeahthemes'); ?></option>
				<option value="this_week"<?php selected( $instance['time_period'], 'this_week' ); ?>><?php esc_html_e('This week', 'yeahthemes'); ?></option>
				<option value="last_week"<?php selected( $instance['time_period'], 'last_week' ); ?>><?php esc_html_e('Last week', 'yeahthemes'); ?></option>
				<option value="this_month"<?php selected( $instance['time_period'], 'this_month' ); ?>><?php esc_html_e('This Month', 'yeahthemes'); ?></option>
				<option value="last_month"<?php selected( $instance['time_period'], 'last_month' ); ?>><?php esc_html_e('Last Month', 'yeahthemes'); ?></option>
				<option value="last_30days"<?php selected( $instance['time_period'], 'last_30days' ); ?>><?php esc_html_e('Last 30 days', 'yeahthemes'); ?></option>
			</select></label>
		</p>
		<p><label><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_icon') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_icon') ); ?>" <?php checked($instance['show_icon'], 'on') ?>/> <strong><?php esc_html_e('Show views/comment counter', 'yeahthemes'); ?></strong></label></p>
		
		<p><label><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_cat') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_cat') ); ?>" <?php checked($instance['show_cat'], 'on') ?>/> <strong><?php esc_html_e('Show category tag', 'yeahthemes'); ?></strong></label></p>
		
		<p><label><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_date') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_date') ); ?>" <?php checked($instance['show_date'], 'on') ?>/> <strong><?php esc_html_e('Show post date', 'yeahthemes'); ?></strong></label></p>
		
		<?php if( function_exists('wp_review_show_total') ) {?>
		<p><label><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_rating') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_rating') ); ?>" <?php checked($instance['show_rating'], 'on') ?>/> <strong><?php esc_html_e('Show review result', 'yeahthemes'); ?></strong></label></p>
		<?php }?>
		
		<p><label><?php esc_html_e( 'Number of posts to show:', 'yeahthemes' ); ?> 
		<input id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" value="<?php echo esc_attr( $number ); ?>" size="3" style="width:60px;" /></label></p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'adscode' ) ); ?>"><?php esc_html_e('Ads code:','yeahthemes')?></label>
			<textarea rows="5" name="<?php echo esc_attr( $this->get_field_name('adscode') ); ?>" class="widefat" id="<?php echo esc_attr( $this->get_field_id('adscode') ); ?>"><?php echo !empty( $instance['adscode'] ) ? esc_textarea( $instance['adscode'] ) : '';?></textarea>
			<em><?php esc_html_e('Not support Google Adsense code when "Scroll infinitely!" is enabled','yeahthemes')?></em>
		</p>
		<p><label><?php printf( __( 'Display ads every %s posts', 'yeahthemes' ), '<input id="' . esc_attr( $this->get_field_id( 'adscode_between' ) ) . '" name="' . esc_attr( $this->get_field_name( 'adscode_between' ) ) . '" type="number" min="2" value="' . esc_attr( $instance['adscode_between'] ) . '" size="3" style="width:60px;" />' ); ?>
		</label></p>

		<p><label><input class="checkbox" type="checkbox" id="<?php echo esc_attr( $this->get_field_id('scroll_infinitely') ); ?>" name="<?php echo esc_attr( $this->get_field_name('scroll_infinitely') ); ?>" <?php checked($instance['scroll_infinitely'], 'on') ?>/> <strong><?php esc_html_e('Scroll infinitely!', 'yeahthemes'); ?></strong></label></p>
		<?php
	}

	/**
	 * Update Widget
	 */
	function update( $new_instance, $old_instance ) {
		
		// processes widget options to be saved
		$instance = $old_instance;
	
		$new_instance = wp_parse_args((array) $new_instance, array( 
			'title' => '',
			'order' => 'DESC',
			'orderby' => 'date',
			'time_period' => 'default',
			'number' => 0,
			'category' => array(),
			'tags' => array(),
			'style' => 'small',
			'show_icon' => 0,
			'show_cat' => 0,
			'show_date' => 0,
			'show_rating' => 0,
			'scroll_infinitely' => 0
		));
		$instance['title'] = $new_instance['title'];
		//Strip tags for title and name to remove HTML 
		
		
		if ( in_array( $new_instance['order'], array( 'DESC', 'ASC' ) ) ) {
			$instance['order'] = $new_instance['order'];
		} else {
			$instance['order'] = 'DESC';
		}
		
		if ( in_array( $new_instance['orderby'], array( 'meta_value_num', 'date', 'title', 'name', 'author', 'comment_count', 'modified', 'rand' ) ) ) {
			$instance['orderby'] = $new_instance['orderby'];
		} else {
			$instance['orderby'] = 'date';
		}
		if ( in_array( $new_instance['time_period'], array( 'default', 'this_week', 'last_week', 'this_month', 'last_month', 'last_30days') ) ) {
			$instance['time_period'] = $new_instance['time_period'];
		} else {
			$instance['time_period'] = 'default';
		}
		
		$instance['category'] = $new_instance['category'];
		$instance['tags'] = $new_instance['tags'];
		$instance['style'] = $new_instance['style'];
		$instance['number'] = (int) $new_instance['number'] == 0 ? 10 : $new_instance['number'];

		$instance['show_icon'] = $new_instance['show_icon'] ? 'on' : 0;
		$instance['show_cat'] = $new_instance['show_cat'] ? 'on' : 0;
		$instance['show_date'] = $new_instance['show_date'] ? 'on' : 0;
		$instance['show_rating'] = $new_instance['show_rating'] ? 'on' : 0;
		$instance['scroll_infinitely'] = $new_instance['scroll_infinitely'] ? 'on' : 0;
		$instance['adscode'] = $new_instance['adscode'];
		$instance['adscode_between'] = $new_instance['adscode_between'];
		
		return $instance;
	}
}