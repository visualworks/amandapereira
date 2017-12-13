<!--Author Info-->
<div class="post-author-area hidden-print text-center">
    <a href="<?php echo esc_url( get_the_author_meta('user_url') ); ?>" class="gravatar"><?php echo get_avatar( get_the_author_meta('user_email'), '75', '' ); ?></a>
    <h3 class="secondary-2-primary"><a href="<?php echo esc_url( get_the_author_meta('user_url') );?>"><strong><?php esc_html_e( get_the_author_meta('display_name') )?></strong></a></h3>
    
		<?php 
		if(get_the_author_meta('description'))
			
			echo wpautop( get_the_author_meta('description') );
		

		?>
    

	<?php echo sprintf( '%s', esc_html__('View all articles by ','yeahthemes') ); ?>
    <strong class="primary-2-secondary"><?php the_author_posts_link();?></strong>

</div>

<!--/Author Info-->