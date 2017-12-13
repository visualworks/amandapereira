<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package yeahthemes
 */
?>

<article class="no-results not-found post">
	<header class="entry-header">
		<h2 class="entry-title"><?php esc_html_e( 'Nothing Found', 'yeahthemes' ); ?></h1>
	</header><!-- .page-header -->

	<div class="entry-content">
		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p><?php printf( esc_html__( 'Ready to publish your first post? %s.', 'yeahthemes' ), sprintf('<a href="%s">%s</a>', esc_url( admin_url( 'post-new.php' ) ), esc_html__( 'Get started here', 'yeahthemes' ) ) ); ?></p>

		<?php elseif ( is_search() ) : ?>

			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'yeahthemes' ); ?></p>
			<?php get_search_form(); ?>

		<?php else : ?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'yeahthemes' ); ?></p>
			<?php get_search_form(); ?>

		<?php endif; ?>
	</div><!-- .page-content -->
</article><!-- .no-results -->
