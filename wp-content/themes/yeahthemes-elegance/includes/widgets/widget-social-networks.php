<?php
/**
 *	Plugin Name: Social Media Links
 *	Description: Display your Social Network links
 */
class YT_Social_Media_Links_Widget extends WP_Widget {

	/**
	 * Widget Setup
	 */
 	function __construct() {
		
		$widget_ops = array(
			'classname' => 'yt-social-media-links-widget yt-widget',
			'description' => esc_html__('Display your Social Network links such as Facebook, Twitter', 'yeahthemes')
		);
		
		// Widget control settings
		$control_ops = array(
			'id_base' => 'yt-social-media-links-widget',
			//'width'	=> 400
		);
		
		parent::__construct( 
			'yt-social-media-links-widget', 
			esc_html__('(Theme) Social Media Links', 'yeahthemes'), 
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
			echo !empty( $title ) ? ( $before_title . $title . $after_title ) : '';
		$output = '';

		if( function_exists( 'yt_site_social_networks' ) ){
			echo yt_site_social_networks( array(
				'template' => '<ul class="site-social-networks secondary-2-primary' . ( !empty( $instance['style'] ) ? esc_attr(" style-{$instance['style']}") : '' ) . ( !empty( $instance['show_title'] ) ? ' show-title' : '' ) . '">%s</ul>', 
				'show_title' => !empty( $instance['show_title'] ) ? true : false,
				'link_before' => '<li>',
				'link_after' => '</li>',
			) );
		}

		echo !empty( $after_widget ) ? $after_widget : '';	
	}

	/**
	 * Widget Settings
	 */
	function form( $instance ) {
		
		// Set up some default widget settings
		$defaults = array(
			'title' => esc_html__('Follow us','yeahthemes'),
			'style' => 'default',
			'show_title' => '',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		?>
		
	    
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:','yeahthemes')?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e('Select style:','yeahthemes')?></label>
			<?php
				if( class_exists( 'YT_Widget_Framework' ) ){ 
					$field_args = array(
						'id' => $this->get_field_id('style'),
						'name' => $this->get_field_name('style'),
						'data_name' => '',
						'value' => $instance['style'],
						'options' => array(
							'default' => __('Default', 'yeahthemes'),
							'color' => __('Color', 'yeahthemes'),
							'plain' => __('Plain', 'yeahthemes'),
						)
					);

					echo YT_Widget_Framework::field('select', $field_args ); 
				}
			?>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'show_title' ) ); ?>">
			<?php
				if( class_exists( 'YT_Widget_Framework' ) ){ 
					$field_args = array(
						'id' => $this->get_field_id('show_title'),
						'name' => $this->get_field_name('show_title'),
						'data_name' => '',
						'value' =>!empty( $instance['show_title'] ) ? $instance['show_title'] : ''
					);

					echo YT_Widget_Framework::field('checkbox', $field_args ); 
				}
			?> <?php esc_html_e('Show link title','yeahthemes')?></label>
		</p>
		<em><?php printf( __('To Config Links, Please go to %s -> Subscribe & Connect -> Connect','yeahthemes' ), sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=yt-theme-options' ), __('Theme Options', 'yeahthemes' ) ) ); ?> </em>
	    

	<?php
	}

	/**
	 * Update Widget
	 */
	function update( $new_instance, $old_instance ) {
		
		// processes widget options to be saved
		$instance = $old_instance;

		//Strip tags for title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['style'] = $new_instance['style'];
		$instance['show_title'] = $new_instance['show_title'] ? 'on' : 0;
		
		return $instance;
	}
}