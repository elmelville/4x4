<?php
get_header();
?>

<div id="primary" class="content-area<?php echo ' width-'.esc_attr($page_width); ?>">
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
		<h1><span><?php the_title(); ?></span></h1>
		<span class="maincont-line1"></span>
		<span class="maincont-line2"></span>

		<div class="container page-styling">

			<div class="row">

<?php
$gallery_sections = get_categories( array(
	'parent'       => 0,
	'orderby'      => 'ID',
	'taxonomy'     => 'gallery_category',
	'hide_empty' => true
) );

if (!empty($gallery_sections)) : ?>
	<ul class="cont-sections">
		<?php foreach ($gallery_sections as $key=>$gallery_section) : ?>
			<li>
				<a href="<?php echo get_term_link($gallery_section->term_id); ?>"><?php echo esc_attr($gallery_section->name); ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<?php
$orderby = 'date';
$order = 'DESC';
$items_per_page = 9;
$css_class = '';
$gallery_category = (!empty($wp_query->query['gallery_category'])) ? $wp_query->query['gallery_category'] : '';

include(locate_template('inc/shortcodes/gallery-content.php'));
?>

			</div>

		</div>

	</div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>