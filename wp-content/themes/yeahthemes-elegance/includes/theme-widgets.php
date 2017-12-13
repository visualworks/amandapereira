<?php
/**
 * Widget init
 *
 *
 * @package yeahthemes
 * @since 1.0
 * @framework 1.0
 */

/**
 * Register theme Widgets
 *
 * @since 1.0
 */

include_once( 'widgets/widget-posts-with-thumnail.php' );
include_once( 'widgets/widget-social-networks.php' );
include_once( 'widgets/widget-instagram-feed.php' );

add_filter( 'yt_framework_widgets_init', 'yt_framework_widgets_init' );

function yt_framework_widgets_init( $widgets ) {
	$widgets[] = 'YT_Posts_With_Thubnail_Widget';
	$widgets[] = 'YT_Social_Media_Links_Widget';
	$widgets[] = 'YT_Instagram_Feed_Widget';
	
	return $widgets;
}

/**
 * Custom field for all available widgets
 *
 * @since 1.0
 */
class YT_Widgets_Custom_Form_Fields{

	/*Silent is golden*/
	function __construct(){

	}

	public static function instance(){
		if( is_admin() ){
			add_action( 'in_widget_form', array( __CLASS__, 'widget_admin_fields' ), 10, 3 );
			add_filter( 'widget_update_callback', array( __CLASS__, 'widget_update' ), 10, 3 );
		}else{
			add_filter('dynamic_sidebar_params', array( __CLASS__, 'dynamic_sidebar_params' ), 10, 3 );
		}
	}

	/**
	 * On an AJAX update of the widget settings, process the display conditions.
	 *
	 * @param array $new_instance New settings for this instance as input by the user.
	 * @param array $old_instance Old settings for this instance.
	 * @return array Modified settings.
	 */
	public static function widget_update( $instance, $new_instance, $old_instance ) {

		$instance['border'] = $new_instance['border'] ? 'on' : 0;
		return $instance;
	}
	/**
	 * Add the widget conditions to each widget in the admin.
	 *
	 * @param $widget unused.
	 * @param $return unused.
	 * @param array $instance The widget settings.
	 */
	public static function widget_admin_fields( $widget, $return, $instance ) {
		$border = 0;

		$instance = wp_parse_args( (array) $instance, array( 'border' => 0) );

		if ( isset( $instance['border'] ) )
			$border = $instance['border'];
		?>
		 <p>
	    	<label><input id="<?php echo esc_attr( $widget->get_field_id( 'border' ) ); ?>" name="<?php echo esc_attr( $widget->get_field_name( 'border' ) ); ?>" type="checkbox" <?php checked( $instance['border'], 'on' ); ?>/>
	    	<?php esc_html_e( 'Display widget content in box', 'yeahthemes' ); ?></label>
	    </p>
		<?php
	}

	public static function dynamic_sidebar_params( $params ){
		global $wp_registered_widgets;
	    $widget_id = $params[0]['widget_id'];
	    if( empty( $widget_id ) )
	    	return $params;
	    $widget_obj = $wp_registered_widgets[$widget_id];
	    $widget_opt = get_option($widget_obj['callback'][0]->option_name);

	    //print_r( $widget_obj );
	    $widget_num = $widget_obj['params'][0]['number'];
	    //print_r( $widget_num ); die();
	    if (!empty( $widget_opt[$widget_num]['border'])){
            
        	$params[0]['before_widget'] = str_replace( array( 'class="', 'class=\''), array( 'class="border-box ', 'class=\'border-box '), $params[0]['before_widget'] );
	    }
	    return $params;

	}
}

/*Kickstart it*/
add_action( 'init', array( 'YT_Widgets_Custom_Form_Fields', 'instance' ) );