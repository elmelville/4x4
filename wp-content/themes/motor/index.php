<?php
get_header();

global $motor_options;
$sidebar_pos = $motor_options['blog_sidebar'];
?>

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
	<h1><span><?php
	if (!empty($wp_query->query['post_type']) && $wp_query->query['post_type'] == 'events') {
		echo '<h1 class="maincont-ttl">'.esc_html($wp_query->query['post_type']).'</h1>';
	} else {
		$categ_ttl = single_cat_title('', false);
		if (!empty($categ_ttl)) {
			echo '<h1 class="maincont-ttl">'.esc_html($categ_ttl).'</h1>';
		} elseif (!empty($wp_query->queried_object->post_title)) {
			echo '<h1 class="maincont-ttl">'.esc_attr($wp_query->queried_object->post_title).'</h1>';
		}
	}
	?></span></h1>
	<span class="maincont-line1"></span>
	<span class="maincont-line2"></span>

	<!-- Blog Sections -->
	<ul class="cont-sections">
		<?php
		$blog_categories = wp_list_categories( array(
			'show_option_all'    => esc_html__('All', 'motor'),
			'show_count'         => 0,
			'hide_empty'         => false,
			'use_desc_for_title' => false,
			'hierarchical'       => true,
			'title_li'           => '',
			'echo'               => 1,
			'depth'              => 1,
		) );
		?>
		<li class="cont-sections-more"><span><?php esc_html_e('...', 'motor'); ?></span><ul class="cont-sections-sub"></ul></li>
	</ul>


	<div class="blog<?php
	if ($sidebar_pos == 'left')
		echo ' blog-left';
	elseif ($sidebar_pos == 'hide')
		echo ' blog-full';
	?>">


		<?php if ($sidebar_pos == 'left') : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>


		<?php
		if (have_posts()) :
		?>
		<!-- Blog Posts List - start -->
		<div class="blog-cont">
			<div id="blog-grid">
			<?php
			while (have_posts()) : the_post();
				$category = get_the_category();
				$blog_video = get_post_meta($post->ID, 'motor_post_text', true);
				$blog_slider = get_post_meta($post->ID, 'motor_post_repeatable_img', true);
				?>
				<?php include(locate_template('template-parts/loop-post.php')); ?>
			<?php
			endwhile;
			?>
			</div>


			<!-- Pagination -->
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
		<!-- Blog Posts List - end -->
		<?php
		else:
		?>
		<div class="blog-cont">
			<p><?php esc_html_e('No Posts', 'motor'); ?></p>
		</div>
		<?php
		endif; wp_reset_postdata();
		?>


		<?php if ($sidebar_pos == 'right') : ?>
			<?php get_sidebar(); ?>
		<?php endif; ?>


	</div>
</div>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_footer(); ?>