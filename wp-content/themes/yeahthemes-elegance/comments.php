<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to yt_comment() which is
 * located in the inc/template-tags.php file.
 *
 * @package yeahthemes
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() )
	return;
?>
<?php do_action('yt_before_comment_template' ); ?>
	<div id="comments" class="comments-area hidden-print">
	
	<?php do_action('yt_comment_template_start' ); ?>
	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h3 class="comments-title">
			<?php
				printf( _nx( '1 comment', '%1$s Comments', get_comments_number(), 'comments title', 'yeahthemes' ),
					number_format_i18n( get_comments_number() ));
			?>
		</h3>
		
		<p class="write-comment-link"><a href="#respond"><?php echo esc_html__( 'Leave a reply &rarr;', 'yeahthemes' ) ;?></a></p>
		
		<div class="clearfix"></div>
		
		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation clearfix" role="navigation">
			<h3 class="screen-reader-text sr-only"><?php _e( 'Comment navigation', 'yeahthemes' ); ?></h3>
			<div class="nav-previous pull-left"><?php previous_comments_link( __( '&larr; Older Comments', 'yeahthemes' ) ); ?></div>
			<div class="nav-next pull-right"><?php next_comments_link( __( 'Newer Comments &rarr;', 'yeahthemes' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list">
			<?php
				/* Loop through and list the comments. Tell wp_list_comments()
				 * to use yt_comment() to format the comments.
				 * If you want to overload this in a child theme then you can
				 * define yt_comment() and that will be used instead.
				 * See yt_comment() in inc/template-tags.php for more.
				 */
				wp_list_comments( array( 'callback' => 'yt_comment', 'walker' => new YT_Walker_Comment(), 'avatar_size'       => 64,) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation clearfix" role="navigation">
			<h3 class="screen-reader-text sr-only"><?php esc_html_e( 'Comment navigation', 'yeahthemes' ); ?></h3>
			<div class="nav-previous pull-left"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'yeahthemes' ) ); ?></div>
			<div class="nav-next pull-right"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'yeahthemes' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'yeahthemes' ); ?></p>
	<?php endif; ?>

	<?php comment_form(); ?>
	<?php do_action('yt_comment_template_end' ); ?>

</div><!-- #comments -->
<?php do_action('yt_after_comment_template' ); ?>
