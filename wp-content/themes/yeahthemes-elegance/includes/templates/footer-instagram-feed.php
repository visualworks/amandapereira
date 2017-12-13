<?php if( '' !== ( $ig_id = yt_get_options('instagram_user_id') ) && function_exists( 'yt_instagram_feed' ) ) :?>
<div class="footer-instagram-feed hidden-xs">
	<?php
		//Echo and get the user info
		yt_instagram_feed( $ig_id, 16, true);
	?>

</div>
<?php endif;?>