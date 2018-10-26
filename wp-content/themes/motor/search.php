<?php get_header(); ?>

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



<?php if ( have_posts() ) : ?>

	<?php
	$products_count = 0;
	$posts_count = 0;
	$others_count = 0;
	while ( have_posts() ) : the_post();
		if ( 'product' === get_post_type() ) {
			$products_count++;
		} elseif ( 'post' === get_post_type() ) {
			$posts_count++;
		} else {
			$others_count++;
		}
	endwhile;
	?>

	<?php if ($products_count > 0) : ?>

	<div class="cont maincont">

		<h1><span><?php esc_html_e('Search Products by', 'motor'); ?> "<?php echo get_search_query(); ?>"</span></h1>
		<span class="maincont-line1"></span>
		<span class="maincont-line2"></span>

		<p class="section-count"><?php echo esc_attr($products_count); ?> <?php echo _n( 'ITEM', 'ITEMS', intval($products_count), 'motor' ); ?></p>

		<div class="prod-litems section-list">
		<?php
		while ( have_posts() ) : the_post();
		?>
			<?php if ( 'product' === get_post_type() ) : ?>
				<?php include(locate_template('woocommerce/content-product.php')); ?>
			<?php endif; ?>
		<?php
		endwhile;
		?>
		</div>
		
	</div>

	<?php endif; ?>

	<?php if ($posts_count > 0) : ?>

	<div class="cont maincont">

		<h1><span><?php esc_html_e('Search Posts by', 'motor'); ?> "<?php echo get_search_query(); ?>"</span></h1>
		<span class="maincont-line1"></span>
		<span class="maincont-line2"></span>

		<p class="section-count"><?php echo esc_attr($posts_count); ?> <?php echo _n( 'ITEM', 'ITEMS', intval($posts_count), 'motor' ); ?></p>


		<div class="blog blog-full">
			<div class="blog-cont">
				<div id="blog-grid">

				<?php
				while ( have_posts() ) : the_post();
					if ( 'post' !== get_post_type() ) {
						continue;
					}
					$category = get_the_category();
					$blog_video = get_post_meta($post->ID, 'motor_post_text', true);
					$blog_slider = get_post_meta($post->ID, 'motor_post_repeatable_img', true);
					?>
					<?php include(locate_template('template-parts/loop-post.php')); ?>
				<?php
				endwhile;
				?>
				</div>
			</div>
		</div>


	</div>

	<?php endif; ?>

	<?php if ($others_count > 0) : ?>

	<div class="cont maincont">

		<h1><span><?php esc_html_e('Search Pages by', 'motor'); ?> "<?php echo get_search_query(); ?>"</span></h1>
		<span class="maincont-line1"></span>
		<span class="maincont-line2"></span>

		<p class="section-count"><?php echo esc_attr($others_count); ?> <?php echo _n( 'ITEM', 'ITEMS', intval($others_count), 'motor' ); ?></p>


		<div class="page-cont">
			<?php
			while ( have_posts() ) : the_post();
				if ( 'post' == get_post_type() || 'product' == get_post_type()) {
					continue;
				}
				?>
				<article class="search-item">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				</article>
			<?php
			endwhile;
			?>
		</div>


	</div>

	<?php endif; ?>


	<div class="cont maincont">
	<?php
	echo paginate_links( array(
		'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
		'format'       => '',
		'add_args'     => '',
		'current'      => max( 1, get_query_var( 'paged' ) ),
		'total'        => $wp_query->max_num_pages,
		'prev_text'    => '<i class="fa fa-angle-left"></i>',
		'next_text'    => '<i class="fa fa-angle-right"></i>',
		'type'         => 'list',
		'end_size'     => 1,
		'mid_size'     => 2
	) );
	?>
	</div>

<?php
else :
	$search_query = get_search_query();
?>
<div class="cont maincont">
	<h1><span><?php esc_html_e( 'Nothing not found', 'motor' ); ?></span></h1>
	<span class="maincont-line1"></span>
	<span class="maincont-line2"></span>

	<div class="pagecont err404">
		<p class="search-err-ttl"><?php echo sprintf( esc_html__( 'Nothing not found for your query "%s". Please try again', 'motor' ), $search_query ); ?></p>
		<?php get_template_part('searchform-simple'); ?>
	</div>
</div>
<?php
endif;
?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>