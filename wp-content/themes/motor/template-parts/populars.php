<?php

$num_posts = (isset($_POST['num_posts'])) ? $_POST['num_posts'] : 8;
$page_num = (isset($_POST['page_num'])) ? $_POST['page_num'] : 1;

$popular_products_query = new WP_Query( array(
	'post_type'   => 'product',
	'post_status' => 'publish',
	'order'               => 'DESC',
	'orderby'             => 'date',
	'posts_per_page'         => $num_posts,
	'meta_key'       => 'my_meta_box_check_1',
	'meta_value'     => 'on',
	'paged'          => $page_num,
) );

if ($popular_products_query->have_posts()) :
	if (!isset($int_key)) {
		$int_key = 1;
	}
	while ($popular_products_query->have_posts()) : $popular_products_query->the_post();

		include(locate_template('template-parts/loop-special.php'));

	endwhile;
endif;
wp_reset_postdata();
?>