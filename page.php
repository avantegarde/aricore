<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package aricore
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<?php // Top A
		if ( is_active_sidebar( 'top-a' ) ) : ?>
			<div class="top-a">
				<?php dynamic_sidebar( 'top-a' ); ?>
			</div>
		<?php endif; ?>
		<?php // Top B
		if ( is_active_sidebar( 'top-b' ) ) : ?>
			<div class="top-b">
				<?php dynamic_sidebar( 'top-b' ); ?>
			</div>
		<?php endif; ?>

		<main id="main" class="site-main" role="main">

			<?php // Inner Top
			if ( is_active_sidebar( 'inner-top' ) ) : ?>
				<div class="inner-top">
					<?php dynamic_sidebar( 'inner-top' ); ?>
				</div>
			<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // end of the loop. ?>

			<?php // Inner Bottom
			if ( is_active_sidebar( 'inner-bottom' ) ) : ?>
				<div class="inner-bottom">
					<?php dynamic_sidebar( 'inner-bottom' ); ?>
				</div>
			<?php endif; ?>

		</main><!-- #main -->

		<?php // Bottom A
		if ( is_active_sidebar( 'bottom-a' ) ) : ?>
			<div class="bottom-a">
				<?php dynamic_sidebar( 'bottom-a' ); ?>
			</div>
		<?php endif; ?>
		<?php // Bottom B
		if ( is_active_sidebar( 'bottom-b' ) ) : ?>
			<div class="bottom-b">
				<?php dynamic_sidebar( 'bottom-b' ); ?>
			</div>
		<?php endif; ?>
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
