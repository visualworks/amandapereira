<?php
/**
 *	Plugin Name: Instagram Feed
 *	Description: Display your Social Network links
 */
class YT_Instagram_Feed_Widget extends WP_Widget {

	/**
	 * Widget Setup
	 */
 	function __construct() {
		
		$widget_ops = array(
			'classname' => 'yt-instagram-widget yt-widget',
			'description' => esc_html__('Display your Instagram Feed', 'yeahthemes')
		);
		
		// Widget control settings
		$control_ops = array(
			'id_base' => 'yt-instagram-widget',
			//'width'	=> 400
		);
		
		parent::__construct( 
			'yt-instagram-widget', 
			esc_html__('(Theme) Instagram Feed', 'yeahthemes'), 
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

		

		echo sprintf('<div class="instagram-content layout-%s">', esc_attr( $instance['layout'] ) );
		
		if( function_exists( 'yt_instagram_feed') ){
			$show_profile = !empty( $instance['profile'] ) ? true : false;
			$userid = !empty( $instance['userdata']['id'] ) ? $instance['userdata']['id'] : '';
			yt_instagram_feed( $userid , $instance['count'], $show_profile );
			
		}
		
		
		echo '</div>';

		//$links_count = $links_count ? $links_count : 1;
		
		echo !empty( $after_widget ) ? $after_widget : $after_widget;
	}

	/**
	 * Widget Settings
	 */
	function form( $instance ) {
		
		// Set up some default widget settings
		$defaults = array(
			'title' => esc_html__('Instagram','yeahthemes'),
			'layout' => 'two-columns',
			'count' => 6,
			'username' => '',
			'profile' => 0,
			'userdata' => array()
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 


		
		?>
		
	    
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Instagram:','yeahthemes')?></label>
			<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
	    
		
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e('Username:','yeahthemes')?>
			<?php
				if( class_exists( 'YT_Widget_Framework' ) ){ 
					$field_args = array(
						'id' => $this->get_field_id('username'),
						'name' => $this->get_field_name('username'),
						'data_name' => '',
						'value' => $instance['username'],
					);

					echo YT_Widget_Framework::field('text', $field_args ); 
				}
			?> </label>
			<!-- <input id="<?php echo esc_attr( $this->get_field_id( 'userdata' ) ); ?>" type="hidden" name="<?php echo esc_attr( $this->get_field_name( 'userdata' ) ); ?>" value="<?php echo esc_attr( $instance['userdata'] ); ?>" /> -->
		</p>

		<?php if(!empty( $instance['userdata'] )):?>
	
		<?php
		//print_r( $instance['userdata'] );
			echo !empty( $instance['userdata']['profile_picture'] ) ? sprintf( '<img src="%s" alt="%s">', esc_url( $instance['userdata']['profile_picture'] ), esc_attr($instance['userdata']['full_name'] ) ) : '';

		 ?>
		<?php endif;?>
		<p><label>
			<?php

			if( class_exists( 'YT_Widget_Framework' ) ){ 
				$field_args = array(
					'id' => $this->get_field_id('profile'),
					'name' => $this->get_field_name('profile'),
					'data_name' => '',
					'value' => !empty( $instance['profile'] ) ? $instance['profile'] : 0
				);

				echo YT_Widget_Framework::field('checkbox', $field_args ); 
			}
			?> <?php esc_html_e('Display Profile','yeahthemes')?></label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e('Layout:','yeahthemes')?></label>
			<?php
				if( class_exists( 'YT_Widget_Framework' ) ){ 
					$field_args = array(
						'id' => $this->get_field_id('layout'),
						'name' => $this->get_field_name('layout'),
						'data_name' => '',
						'value' => $instance['layout'],
						'options' => array(
							'two-columns' => __('Default (2 Columns)', 'yeahthemes'),
							'three-columns' => __('3 Columns', 'yeahthemes'),
						)
					);

					echo YT_Widget_Framework::field('select', $field_args ); 
				}
			?>
		</p>
		
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'count' ) ); ?>"><?php esc_html_e('Count:','yeahthemes')?>
			<?php
				if( class_exists( 'YT_Widget_Framework' ) ){ 
					$field_args = array(
						'id' => $this->get_field_id('count'),
						'name' => $this->get_field_name('count'),
						'data_name' => '',
						'value' => $instance['count'],
					);

					echo YT_Widget_Framework::field('number', $field_args ); 
				}
			?> </label>
		</p>
		<?php if( !yt_get_options('instagram_user_id') && !yt_get_options('instagram_access_token') ){ ?>
		<em><?php printf( __('You need to config Access token and User ID for Instagram first, Please go to %s -> Subscribe & Connect -> API','yeahthemes' ), sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=yt-theme-options#yt-option-api-subscribeconnect' ), __('Theme Options', 'yeahthemes' ) ) ); ?> </em>
		<?php }else{

		}?>
	    

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
		$instance['layout'] = $new_instance['layout'];
		$instance['count'] = 0 == intval( $new_instance['count'] ) ? 6 : intval( $new_instance['count'] );
		$instance['username'] = $new_instance['username'];
		$instance['profile'] = $new_instance['profile'] ? 'on' : 0;
		$access_token = yt_get_options('instagram_access_token');
		if( $instance['username'] && $old_instance['username'] !== $new_instance['username'] && $access_token ){	
			
		    $api_url = "https://api.instagram.com/v1/users/search?q=" . urlencode( $instance['username'] ) . "&access_token={$access_token}";
		    $response = wp_remote_get( $api_url, array( 'sslverify'   => true ) );

		    $body = '';
		    if ( is_wp_error( $response ) ) {
		        $error_message = $response->get_error_message();
		        //echo sprintf( __('Something went wrong: %s', 'yeahthemes' ), $error_message );
		    } else {
		        $body = wp_remote_retrieve_body( $response );
		    }

		    $results = json_decode( $body, true );
		    if(!empty( $results['data'] )){
		    	foreach ($results['data'] as $data) {
		    		if( !empty( $data['username'] ) && $instance['username'] == $data['username'] )
		    			$instance['userdata'] = $data;
		    	}
		    }
			
		}elseif( empty( $instance['username'] ) ){
			$instance['userdata'] = array();
		}
		
		return $instance;
	}
}