<?php
// This file is not called from WordPress. We don't like that.
! defined( 'ABSPATH' ) and exit;

add_action( 'after_setup_theme', 'yt_wc_after_setup_theme' );

function yt_wc_after_setup_theme() {

	add_theme_support( 'woocommerce' );

}

/***************************************************************************************/
/* WooCommerce Overrides */
/***************************************************************************************/
if ( class_exists( 'woocommerce' )) {

add_filter( 'yt_theme_options', 'yt_wc_theme_options', 10 );
/**
 * Theme options
 */
function yt_wc_theme_options( $options ) {

	$show_hide = array(
		'show' => __('Show', 'yeahthemes'), 
		'hide' => __('Hide', 'yeahthemes')
	);
	

	$wc_options = apply_filters( 'yt_wc_theme_options' , array(
		
		array( 
			'name' => __('Woocommerce','yeahthemes'),
			'desc' => '',
			'type' => 'heading',
			'customize' => 1,
			'settings' => array(
				'icon' => 'ecommerce'
			)
			
		),
		array(
			'name' => __('Product per page ( Main shop page )','yeahthemes'),
			'desc' => '',
			'id' => 'wc_products_per_page',
			'std' => 12,
			'type' => 'number',
			'customize' => 1,
		),
		array(
			'name' => __('Mini cart (on Main Menu)','yeahthemes'),
			'desc' => '',
			'id' => 'wc_main_menu_mini_cart',
			'std' => 'show',
			'type' => 'toggles',
			'options' => $show_hide  ,
			'customize' => 1,
		),
		array(
			'name' => __('Single product tabs','yeahthemes'),
			'desc' => '',
			'id' => 'wc_single_product_tabs',
			'std' => 'show',
			'type' => 'toggles',
			'options' => $show_hide  ,
			'customize' => 1,
		),
		array(
			'name' => __('Related product ( Single product )','yeahthemes'),
			'desc' => '',
			'id' => 'wc_single_product_related_products',
			'std' => 'show',
			'type' => 'toggles',
			'options' => $show_hide  ,
			'customize' => 1,
		),
		array(
			'name' => __('Add to cart button ( Product archives )','yeahthemes'),
			'desc' => '',
			'id' => 'wc_archive_add_to_cart_button',
			'std' => 'show',
			'type' => 'toggles',
			'options' => $show_hide  ,
			'customize' => 1,
		),
		array(
			'name' => __('Sale badge (Product archives)','yeahthemes'),
			'desc' => '',
			'id' => 'wc_product_archive_sale_badge',
			'std' => 'show',
			'type' => 'toggles',
			'options' => $show_hide  ,
			'customize' => 1,
		)

	) );
	


	return array_merge( $options, $wc_options );
}


add_filter( 'woocommerce_enqueue_styles', 'yt_wc_dequeue_styles' );
/**
 * Remove each style one by one
 */
function yt_wc_dequeue_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );	// Remove the gloss
	unset( $enqueue_styles['woocommerce-layout'] );		// Remove the layout
	unset( $enqueue_styles['woocommerce-smallscreen'] );	// Remove the smallscreen optimisation
	return $enqueue_styles;
}

add_action( 'yt_theme_scripts_before_enqueue_styles', 'yt_wc_default_style' );
/**
 * Load default framework css
 */
function yt_wc_default_style(){

	$css_dir = get_template_directory_uri() . '/css/';
	$js_dir = get_template_directory_uri() . '/js/';

	// Dequeue Increment plugin
	wp_dequeue_style( 'wcqi-css' );

	wp_enqueue_style('woocommerce-layout'			, $css_dir . 'woocommerce-layout.css');
	wp_enqueue_style('woocommerce-smallscreen'		, $css_dir . 'woocommerce-smallscreen.css', 'woocommerce-layout', '1.0' );
	wp_enqueue_style('woocommerce-general'			, $css_dir . 'woocommerce.css');
		
}


/*************************************************************************************
 * LAYOUT
 *************************************************************************************/

// Remove WC sidebar
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
// Adjust markup on all woocommerce pages
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', 'yt_wc_before_content', 10);
add_action('woocommerce_after_main_content', 'yt_wc_after_content', 20);

/**
 * Woocommerce template: before content
 */
if (!function_exists('yt_wc_before_content')) {
	function yt_wc_before_content() {
		
		yt_before_primary(); ?>
	
		<div id="primary" <?php yt_section_classes( 'content-area', 'primary' );?>>
			
			<?php yt_primary_start(); ?>
			
			<div id="content" <?php yt_section_classes( 'site-content', 'content' );?> role="main">
	    <?php
	}
}
/**
 * Woocommerce template: after content
 */
if (!function_exists('yt_wc_after_content')) {
	function yt_wc_after_content() {
		?>
		
		</div><!-- #content -->
			
				<?php yt_primary_end(); ?>
				
			</div><!-- #primary -->
			
			<?php yt_after_primary(); 
			
			if( apply_filters( 'yt_site_secondary_content_condition', ( 'fullwidth' !== yt_site_get_current_page_layout() ) ) ) 
				get_sidebar();
			}
}


/**
 * Woocommerce template: after content
 */

// add_filter( 'yt_theme_dynamic_sidebars_default', 'yt_wc_default_sidebar', 10, 1 );

// if (!function_exists('yt_wc_default_sidebar')) {
// 	function yt_wc_default_sidebar( $default, $meta_key ) {
// 		if( yt_is_woocommerce() && wc_get_page_id('shop') ){
// 			if( !empty( $GLOBALS['post'] )){
// 				$post = $GLOBALS['post'];
// 				$post_id = $post->ID;
// 			}else{
// 				$post_id = 0;
// 			}

		
// 			$post_id = wc_get_page_id('shop');

// 			/*Retrieve page layout from meta key*/ 
// 			$sidebar = get_post_meta( $post_id, $meta_key, true );

// 			if( 'none' !== $sidebar ){
// 				return $sidebar;
// 			}
// 		}
// 		return $default;
// 	}
// }



add_filter( 'woocommerce_product_review_comment_form_args', 'yt_wc_product_review_comment_form_args', 10 );
/**
 * Custom Woocommerce Product preview form
 */
if (!function_exists('yt_wc_product_review_comment_form_args')) {
	function yt_wc_product_review_comment_form_args( $comment_form ){
		$commenter = wp_get_current_commenter();
		$comment_form['fields'] = array(
			'author' => '<p class="comment-form-author col-md-4 col-sm-4">' . '<label for="author">' . __( 'Name', 'yeahthemes' ) . ' <span class="required">*</span></label> ' .
			            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
			'email'  => '<p class="comment-form-email col-md-4 col-sm-4"><label for="email">' . __( 'Email', 'yeahthemes' ) . ' <span class="required">*</span></label> ' .
			            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p>',
		);

		if ( get_option( 'woocommerce_enable_review_rating' ) === 'yes' ) {
			$comment_form['comment_field'] = '<p class="comment-form-rating col-xs-12"><label for="rating">' . __( 'Your Rating', 'yeahthemes' ) .'</label><select name="rating" id="rating">
				<option value="">' . __( 'Rate&hellip;', 'yeahthemes' ) . '</option>
				<option value="5">' . __( 'Perfect', 'yeahthemes' ) . '</option>
				<option value="4">' . __( 'Good', 'yeahthemes' ) . '</option>
				<option value="3">' . __( 'Average', 'yeahthemes' ) . '</option>
				<option value="2">' . __( 'Not that bad', 'yeahthemes' ) . '</option>
				<option value="1">' . __( 'Very Poor', 'yeahthemes' ) . '</option>
			</select></p>';
		}else{
			$comment_form['comment_field'] = '';
		}
		$comment_form['comment_field'] .= '<p class="comment-form-comment col-xs-12"><label for="comment">' . __( 'Your Review', 'yeahthemes' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>' . wp_nonce_field( 'woocommerce-comment_rating', '_wpnonce', true, false ) . '</p>';
		return $comment_form;
	}
}

add_filter( 'woocommerce_breadcrumb_defaults', 'yt_wc_breadcrumb_defaults' );
/**
 * Breadcrumb
 *
 * @since 1.0
 */
function yt_wc_breadcrumb_defaults( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	return array(
        'delimiter'   => '<span class="breadcrumb-delimeter">&#47;</span>',
        'wrap_before' => '<nav class="woocommerce-breadcrumb gray-2-primary">',
        'wrap_after'  => '</nav>',
        'before'      => '',
        'after'       => '',
        'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
    );
}
/**
 * Custom Menu item: Mini Cart
 *
 * @since 1.0
 */

add_filter( 'wp_nav_menu_items', 'yt_wc_custom_menu_item', 15, 2 );

if ( ! function_exists( 'yt_wc_custom_menu_item' ) ) {

	function yt_wc_custom_menu_item ( $items, $args ) {
		global $woocommerce;
		if( $args->theme_location == 'primary' && 'hide' !== yt_get_options( 'wc_main_menu_mini_cart' ) && !empty( $woocommerce->cart->cart_contents_count )){
			
			$cart = '';
			$items .= sprintf( '<li class="menu-item menu-item-type-custom menu-item-shopping-cart default-dropdown sub-menu-left pull-right">
				<a href="%s" class="cart-contents" title="' .  __('View your cart', 'yeahthemes' ) . '"><i class="fa fa-shopping-cart"></i> %s (%d)</a>
			</li>',
			esc_attr( $woocommerce->cart->get_cart_url() ),
			$woocommerce->cart->get_cart_total(),
			$woocommerce->cart->cart_contents_count
			);
		}

		return $items;
	}
}
/**
 * Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
 */
add_filter('add_to_cart_fragments', 'yt_wc_header_add_to_cart_fragment', 10);
 
function yt_wc_header_add_to_cart_fragment( $fragments ) {

	global $woocommerce;
	
	$cart = sprintf('<a href="%s" class="cart-contents" title="%s"><i class="fa fa-shopping-cart"></i> %s (%d)</a>', 
		esc_attr( $woocommerce->cart->get_cart_url() ),
		esc_attr( __('View your cart', 'yeahthemes' )),
		$woocommerce->cart->get_cart_total(),
		$woocommerce->cart->cart_contents_count
	);
	
	$fragments['a.cart-contents'] = $cart ;
	
	return $fragments;
	
}

add_filter( 'woocommerce_widget_cart_item_quantity', 'yt_wc_item_remove_link', 20, 3 );
/**
 * Empty cart url
 *
 * @since 1.0
 */
function yt_wc_item_remove_link( $item_quantity, $cart_item, $cart_item_key ){

	global $woocommerce;

	$item_quantity = $item_quantity . sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'woocommerce' ) );

	return $item_quantity;
}



/***************************************************************************************/
/* PRODUCTS */
/***************************************************************************************/


add_filter( 'woocommerce_sale_flash', 'yt_wc_outofstock_sale'); 
/**
 * Sale flash
 *
 * @since 1.0
 */
function yt_wc_outofstock_sale() { 
	global $post, $product;
	if ( !$product->is_in_stock() )

		return '<span class="onsale outofstock"><span>' . __( 'Out of Stock', 'yeahthemes' ) . '</span></span>';

	else

		return '<span class="onsale"><span>' . __( 'Sale!', 'yeahthemes' ) . '</span></span>';
}



add_action( 'woocommerce_before_shop_loop_item_title', 'yt_wc_outofstock_flash', 10 );
add_action( 'woocommerce_before_single_product_summary', 'yt_wc_outofstock_flash', 10 );
/**
 * Outstock flash
 *
 * @since 1.0
 */
function yt_wc_outofstock_flash( ) { 
	global $post, $product;
	if ( !$product->is_in_stock() )

		echo '<span class="onsale outofstock"><span>' . __( 'Out of Stock', 'yeahthemes' ) . '</span></span>';

}

// Remove add to cart button on archives
if( 'hide' == yt_get_options( 'wc_archive_add_to_cart_button' ) )
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

// Remove sale flash on archives
if( 'hide' == yt_get_options( 'wc_product_archive_sale_badge' ) )
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );


add_filter('loop_shop_per_page', 'yt_wc_loop_shop_per_page');
/**
 * Number of products per page
 *
 * @since 1.0
 */
if (!function_exists('yt_wc_loop_shop_per_page')) {
	function yt_wc_loop_shop_per_page() {

		if ( absint( yt_get_options( 'wc_products_per_page' ) ) ) {
			return yt_get_options( 'wc_products_per_page' );
		}

		return 12;
		
	}
}

add_action('wp_head','yt_wc_tab_check');
/**
 * Display product tabs?
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_wc_tab_check' ) ) {
	function yt_wc_tab_check() {
		
		if ( yt_get_options( 'wc_single_product_tabs' ) && 'hide' == yt_get_options( 'wc_single_product_tabs' ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
		}
	}
}


add_action('wp_head','yt_wc_related_products_mode');
/**
 * Display related products?
 *
 * @since 1.0
 */
if ( ! function_exists( 'yt_wc_related_products_mode' ) ) {
	function yt_wc_related_products_mode() {
		if ( yt_get_options( 'wc_single_product_related_products' ) && 'hide' == yt_get_options( 'wc_single_product_related_products' ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		}
	}
}

add_filter( 'woocommerce_product_thumbnails_columns', 'yt_wc_custom_product_thumbnails_columns' );
/**
 * Change thumbs on the single page to 4 per column
 *
 * @since 1.0
 */
if (!function_exists('yt_wc_custom_product_thumbnails_columns')) {
	function yt_wc_custom_product_thumbnails_columns() {
		return 4;
	}
}

add_filter('loop_shop_columns', 'yt_wc_loop_columns');
/**
 * Change number or products per row to 4
 *
 * @since 1.0
 */
if (!function_exists('yt_wc_loop_columns')) {
	function yt_wc_loop_columns() {
		return 4;
	}
}

/***************************************************************************************/
/* SINGLE PRODUCTS */
/***************************************************************************************/

add_filter( 'woocommerce_output_related_products_args', 'yt_wc_related_products' );
/**
 * Modify related product args
 *
 * @since 1.0
 */
function yt_wc_related_products() {
	$args = array(
		'posts_per_page' => 4,
		'columns'        => 4,
	);
	return $args;
}

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
add_action( 'woocommerce_after_single_product_summary', 'yt_wc_upsell_display', 15 );
/**
 * Upsell Products
 *
 * @since 1.0
 */
if (!function_exists('yt_wc_upsell_display')) {
	function yt_wc_upsell_display() {
	    woocommerce_upsell_display( -1, 4 );
	}
}
add_action( 'woocommerce_single_product_summary','yt_wc_single_product_summary', 50);

if ( ! function_exists( 'yt_wc_single_product_summary' ) ) {
	 
	function yt_wc_single_product_summary(){
		

	}
}

}//end woocommerce overrides


if( class_exists( 'YITH_WCWL_UI')){
	
add_action( 'admin_print_styles', 'yt_wc_settings_print_styles' );

function yt_wc_settings_print_styles(){
	echo '<style>.yith_banner{display: none !important;}</style>';
}

}