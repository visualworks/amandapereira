<?php
 // This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

/**
 * Admin Helpers
 *
 * @author		Yeahthemes
 * @copyright	Copyright ( c ) Yeahthemes
 * @link		http://wpthms.com
 * @since		Version 1.0
 * @package 	Yeahthemes
 */
 
/**
 * get RevSlider
 *
 * @return    array
 *
 * @access    public
 * @since     1.0
 */
if( !function_exists( 'yt_get_revslider' ) ) {

	function yt_get_revslider(){
		
		if(class_exists('RevSlider')){
			$returnSlider = array();
			$slider = new RevSlider();
			$arrSliders = $slider->getArrSliders();
			
			foreach($arrSliders as $slider) { 
				$returnSlider[$slider->getAlias()] = $slider->getTitle();
			}
			return $returnSlider;
		}
	}	
}

/*---------------------------------------------------------------------------------------------------------*
 * Get icon font list
 *
 * yt_delete_iconfont_transient()
 * yt_get_icon_font_content()
 *---------------------------------------------------------------------------------------------------------*/

/*
 * Delete iconfont cache when updating theme options
 */
add_action( 'ytto_after_update_options','yt_delete_iconfont_transient', 1, 0 );

if( !function_exists( 'yt_delete_iconfont_transient' ) ) {
	function yt_delete_iconfont_transient(){
		if( false !== ($result = get_transient( 'yt_icon_font' ) ) ) {
			delete_transient( 'yt_icon_font' );
		}
	}
}
/*
 * Get icon font classes
 */
if( !function_exists( 'yt_get_icon_font_content' ) ) {
	function yt_get_icon_font_content( $url ){
		
		$temp = array();
		$result = array();
		
		$icon_font_url = $url;
		
		if( $icon_font_url === '' ) {
			
			$result[''] =  esc_html__( 'No icon font found!', 'yeahthemes' );
			return $result;
			
		}else{
		
			/*
			 * if is cached, get the font array
			 */
			if( false !== ( $result = get_transient( 'yt_icon_font' ) ) ) {
				$result = get_transient( 'yt_icon_font' );
			}
			
			/*
			 * else parse a provided URL 
			 */
			else{
				$remote_content = wp_remote_get( $icon_font_url, array( 'sslverify'=>false ) );
				if( is_wp_error( $remote_content ) ) {
					
					$result[''] = esc_html__( 'No icon found!', 'yeahthemes' );
				}
				else{ 
				
					$result[''] = esc_html__( 'Select icon:', 'yeahthemes' );
				
					$raw_css = wp_remote_retrieve_body( $remote_content );
					/*
					 * Parse css
					 */
					if( !class_exists( 'YT_CSS_Parser' ) ){
			
						require_once( YEAHTHEMES_FRAMEWORK_DIR . 'classes/class.css-parser.php' );
					
					}
					$css_parser = new YT_CSS_Parser();
					$raw_class = $css_parser->parse_css( $raw_css );
					
					
					/*
					 * remove the duplicated value;
					 */
					$temp = array_unique( $raw_class );
					sort( $temp );
					foreach( $temp as $k ){
						$result[$k] = str_replace( 'icon-', '', $k );
					}
				}
				
				set_transient( 'yt_icon_font', $result, 0 ); // 12 hour cache 
			};
		}
		
		//print_r($result);
		return $result;
	}
}



/**
 * Delete iconfont cache when updating theme options
 *
 * @since     1.0
 * @return array
 */
 
if( !function_exists( 'yt_get_3rd_party_api_keys' ) ) {
	function yt_get_3rd_party_api_keys(){
		
		$apis = apply_filters( 'yt_third_party_api_keys', array() );
		//var_dump( $apis ); die();
		return (array) $apis;
	}
}

/*---------------------------------------------------------------------------------------------------------*
 * Twitter helper
 *
 * yt_delete_iconfont_transient()
 * yt_get_icon_font_content()
 *---------------------------------------------------------------------------------------------------------*/

/**
 * Delete iconfont cache when updating theme options
 *
 * @since     1.0
 */
 
if( !function_exists( 'yt_twitter_oauth_info' ) ) {
	function yt_twitter_oauth_info(){
		$apis = yt_get_3rd_party_api_keys();
		$oauth = $apis['twitter'];
				
		return $oauth;
	}
}


/**
 * Helper function for fetching json from twitter
 *
 * @since     1.0
 */ 
if( !function_exists( 'yt_twitter_helper' ) ) {
	function yt_twitter_helper( $requestMethod = 'GET', $url, $getfield ){
		
		
		/** Set access tokens here - see: //dev.twitter.com/apps/ **/
		$settings = yt_twitter_oauth_info();

		if( $url === '' || $getfield === '' || $requestMethod === '' ) return;
		
		if ( !class_exists('TwitterAPIExchange' ) ) 
			include_once( 'extended/class.twitter-api-exchange.php' );

		$exchanger = new TwitterAPIExchange( $settings );

		if( is_wp_error( $exchanger ) )
			return '';
		
		$return = $exchanger->setGetfield( $getfield )
					 ->buildOauth( $url, $requestMethod )
					 ->performRequest(); 
					 
		return $return;
	}
}

/**
 * get user profile
 *
 * @since     1.0
 */
 
if( !function_exists( 'yt_twitter_user_profile' ) ) {
	function yt_twitter_user_profile( $user = 'Yeahthemes' ){
		
		$transName = sanitize_title( 'yt_twitter_profile_'. $user );
		$cacheTime = 1 * HOUR_IN_SECONDS; 
		
		$url = 'https://api.twitter.com/1.1/users/show.json';
		$getfield = '?screen_name=' . $user;
		$requestMethod = 'GET';
		$return = array();

		$transient_value = get_transient( $transName );

		if ( empty(  $transient_value ) ) {
			$return = yt_twitter_helper( $requestMethod, $url, $getfield ); 
			$return = json_decode( $return, true );
			
			/*
			 * Save our new transient.
			 */
			if( !empty( $return ) ){
			// 1 hour 
				$results = yt_encode( maybe_serialize( $return ) );
				//$results = $data['data'];
				set_transient( $transName, $results, $cacheTime );
			}

		}else{
			$return = maybe_unserialize( yt_decode( $transient_value ) );
		}

		return ( array ) $return;
		
	}
}

/*
 * get user timeline
 *
 * @since     1.0
 */
if( !function_exists( 'yt_twitter_user_timelines' ) ) {
	function yt_twitter_user_timelines($count = 3, $user = 'Yeahthemes'){
		
		$transName = sanitize_title( 'yt_tweets_'. $user . '_count_' . $count );
		$cacheTime = 1 * HOUR_IN_SECONDS; 
		
		/** URL for REST request, see: //dev.twitter.com/docs/api/1.1/ **/
		$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		$getfield = '?screen_name=' . $user . '&count=' . $count;
		$requestMethod = 'GET';
		
		
		
		$return = array();
		
		$transient_value = get_transient( $transName );

		if ( empty(  $transient_value ) ) {
		
			$tweets = yt_twitter_helper( $requestMethod, $url, $getfield ); 
			$tweets = json_decode( $tweets, true );
			
			$i = 0;
			
			if( empty( $tweets ) )
				return $return;

			foreach( $tweets as $tweet ){
				
				$tweet_text = !empty( $tweet['text'] ) ? $tweet['text'] : '';
				$tweet_date = !empty( $tweet['created_at'] ) ? yt_twitter_time( $tweet['created_at'] ) : '';
				
				/*
				 * Replace URLs to working Links
				 */
				$tweet_text = preg_replace( '/\b(?:(http(s?):\/\/)|(?=www\.))(\S+)/is', '<a href="http$2://$3" target="_blank">$1$3</a>', $tweet_text ); 
				/*
				 * match name@address
				 */
				$tweet_text = preg_replace( "/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $tweet_text );
				
				/*
				 * Replace username start by @ to working link
				 */
				$tweet_text = preg_replace( '/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\">@$2</a>$3 ", $tweet_text );
				
				/*
				 * Replace hash (#) to search link
				 */
				$tweet_text = preg_replace( '/\s#(\w+)/', ' <a href="//twitter.com/search?q=$1">#$1</a>', $tweet_text );
				
				$return[$i]['tweet'] = $tweet_text;
				$return[$i]['time'] = $tweet_date;
				
				$i++;
			}
			


			/*
			 * Save our new transient.
			 */
			if( !empty( $return ) ){
			// 1 hour 
				$results = yt_encode( maybe_serialize( $return ) );
				//$results = $data['data'];
				set_transient( $transName, $results, $cacheTime );
			}
			
		
		}else{
			$return = maybe_unserialize( yt_decode( $transient_value ) );
		}


				
		//print_r($return);
		return ( array ) $return;
		
	}
}

/**
 * Twitter relative time
 *
 * @since     1.0
 */
if( !function_exists( 'yt_twitter_time' ) ) {
	function yt_twitter_time( $time ) {
		//get current timestampt 
		$b = strtotime( "now" ); 
		//get timestamp when tweet created 
		$c = strtotime( $time ); 
		//get difference 
		$d = $b - $c; 
		//calculate different time values 
		$minute = 60; 
		$hour = $minute * 60; 
		$day = $hour * 24; 
		$week = $day * 7; 
		if(is_numeric($d) && $d > 0) { 
			//if less then 3 seconds 
			if( $d < 3 ) return esc_html__( 'right now', 'yeahthemes' ); 
			//if less then minute 
			if( $d < $minute ) return floor( $d ) . esc_html__( ' seconds ago', 'yeahthemes' ); 
			//if less then 2 minutes 
			if( $d < $minute * 2 ) return esc_html__( 'about a minute ago', 'yeahthemes' ); 
			//if less then hour 
			if( $d < $hour ) return floor( $d / $minute ) . esc_html__(' minutes ago', 'yeahthemes' );
			//if less then 2 hours 
			if( $d < $hour * 2 ) return esc_html__('about an hour ago', 'yeahthemes' ); 
			//if less then day 
			if( $d < $day ) return floor( $d / $hour ) . esc_html__( ' hours ago','yeahthemes' ); 
			//if more then day, but less then 2 days 
			if( $d > $day && $d < $day * 2 ) return esc_html__( 'yesterday','yeahthemes' ); 
			//if less then year 
			if( $d < $day * 365 ) return floor( $d / $day ) . esc_html__( ' days ago', 'yeahthemes' ); 
			//else return more than a year return "over a year ago"; 
		} 
	}
}

/**
 * Get Social Network counts/subscribers
 *
 * @since     1.0
 */
if( !function_exists( 'yt_get_social_network_counts' ) ) {
	function yt_get_social_network_counts( $service = '', $id = '' ){

		if( empty( $service ) || empty( $id ) )
			return '';

		if( !in_array( $service, apply_filters( 'yt_get_social_network_counts_services', array( 'twitter', 'facebook', 'dribbble', 'youtube', 'googleplus' ) ) ) )
			return '';

		$transient_name = sprintf( 'yt_social_media_counts_%s_%s', $service , $id );

		$transient = get_transient( $transient_name  );

		$return = '';

		if( !empty( $transient )){
			
			$return = $transient;

		}else{
			$apis = yt_get_3rd_party_api_keys();
			$google_api = $apis['google'];

			switch ( $service) {
				case 'twitter':
					$response_data = yt_twitter_user_profile( $id );
					$return = !is_wp_error( $response_data ) && !empty( $response_data ) && !empty( $response_data['followers_count']) ? $response_data['followers_count'] : 0;
				break;

				case 'facebook':
					$response_data = wp_remote_get( "//graph.facebook.com/$id", array( 'sslverify'=>false ) );
					$response_data = !is_wp_error( $response_data ) ? wp_remote_retrieve_body( $response_data ) : '';
					$response_data_content = $response_data ? json_decode( $response_data, true ) : '';
					$return = isset( $response_data_content['likes'] ) ? $response_data_content['likes'] : 0;
				break;

				case 'youtube':
					$response_data = wp_remote_get( "//gdata.youtube.com/feeds/api/users/$id?alt=json", array( 'sslverify' => false ) );
					$response_data = !is_wp_error( $response_data ) ? wp_remote_retrieve_body( $response_data ) : '';
					$response_data_content = $response_data ? json_decode( $response_data, true ) : '';
					$return = isset( $response_data_content['entry']['yt$statistics']['subscriberCount'] ) ? $response_data_content['entry']['yt$statistics']['subscriberCount'] : 0;
				break;

				case 'googleplus':
					$response_data = wp_remote_get("//www.googleapis.com/plus/v1/people/$id?key=$google_api", array( 'sslverify' => false ));  
					$response_data = !is_wp_error( $response_data ) ? wp_remote_retrieve_body( $response_data ) : ''; 
					$response_data_content = $response_data ? json_decode( $response_data, true ) : '';
					$return = isset( $response_data_content['circledByCount'] ) ? $response_data_content['circledByCount'] : 0;
					
				break;
				default:
					do_action( 'yt_get_social_network_counts', $service, $id );
				break;
			}

			set_transient( $transient_name, $return, 60 * 30 );
		}

		$return = intval( $return );
		$prettier_number = yt_beautify_number( intval( $return ) );
		
		return $prettier_number ;

	}
}