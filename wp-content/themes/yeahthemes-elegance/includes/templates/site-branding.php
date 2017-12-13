<?php 
/**
 * Retrieve Options data
 */
$heading = is_front_page() || is_home() ? 'h1' : 'h3';
$tagline = is_front_page() || is_home() ? 'h2' : 'h4';


//Branding

$type = yt_get_options( 'site_logo_types' );
$title = get_bloginfo( 'name', 'display' );
$description = get_bloginfo( 'description', 'display' );
$logo = yt_get_options('logo');
$logo = $logo ? $logo : get_template_directory_uri() . '/images/logo.png';

if( 'default' == $type || 'default' !== $type && !$logo ){
	$branding = '<div class="site-branding">';
		$branding .= '<'.$heading.' class="site-title site-logo plain-text-logo">
			<a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr( $title ) . '" rel="home">' . $title . '</a>
		</'.$heading.'>';
		$branding .= '<'.$tagline.' class="site-description">' . esc_html( $description ) . '</'.$tagline.'>';
	$branding .= '</div>';
}else{
	$branding = '<div class="site-branding">';
		
		$branding .= sprintf('<%2$s class="hidden">%1$s</%2$s>', esc_html( $description ), esc_html( $heading  ) );
		$branding .= sprintf( '<a class="site-title site-logo image-logo" href="%1$s" title="%2$s" rel="home"><img src="%3$s" alt="%2$s" %4$s></a>',
			esc_url( home_url( '/' ) ),
			esc_attr( $title ),
			esc_url( $logo ),
			'width="300"'
		);

		$branding .= 'image_tagline' == $type ? sprintf( '<%2$s class="site-description">%1$s</%2$s>', esc_html( $description ), esc_html( $tagline  ) ) : '';
		
	$branding .= '</div>';
}


echo apply_filters( 'yt_site_branding', $branding );