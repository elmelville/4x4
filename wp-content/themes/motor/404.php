<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package motor
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

<!-- Breadcrumbs -->
<div class="b-crumbs-wrap">
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
	<div class="cont b-crumbs">
		<?php woocommerce_breadcrumb(array('wrap_before'=>'<ul>', 'wrap_after'=>'</ul>', 'before'=>'<li>', 'after'=>'</li>', 'delimiter'=>'')); ?>
	</div>
	<?php endif; ?>
</div>

<div class="cont maincont">
	<h1><span><?php esc_html_e( 'Error', 'motor' ); ?></span></h1>
	<span class="maincont-line1"></span>
	<span class="maincont-line2"></span>

	<!-- Error 404 -->
	<div class="pagecont err404">
		<img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/err404.png" alt="" class="err404-img">
		<p><?php esc_html_e( 'We are so sorry, but the page you requested is not available', 'motor' ); ?></p>
		<?php get_template_part('searchform-simple'); ?>
	</div>
</div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>