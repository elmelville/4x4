<?php
$page_num = (isset($_POST['page_num'])) ? $_POST['page_num'] : 1;
$items_per_page = (isset($_POST['posts_per_page'])) ? $_POST['posts_per_page'] : $items_per_page;
if (isset($large_first_item) && $large_first_item == 'yes') {
	$items_per_page--;
}
$order = (isset($_POST['order'])) ? $_POST['order'] : $order;
$orderby = (isset($_POST['orderby'])) ? $_POST['orderby'] : $orderby;

$view_mode = (isset($_POST['view_mode'])) ? $_POST['view_mode'] : $view_mode;

$ids = (isset($_POST['ids'])) ? $_POST['ids'] : $ids;
if (!empty($ids)) {
	$ids_arr = explode(', ', $ids);
} else {
	$ids_arr = array();
}

$catalog_notroll = motor_option('catalog_notroll');

$popular_products_query = new WP_Query( array(
	'post__in' => $ids_arr,
	'post_type'   => 'product',
	'post_status' => 'publish',
	'order'          => $order,
	'orderby'        => $orderby,
	'posts_per_page' => $items_per_page,
	'paged'          => $page_num,
) );

if ($popular_products_query->have_posts()) :
	?>
	<div class="<?php 
	if ($view_mode == 'list') { 
		echo 'prod-litems'; 
	} else { 
		echo 'prod-items'; 
		if ($catalog_notroll == 'no') { 
			echo ' prod-items-notroll'; 
		} 
	} ?> specials-list<?php echo esc_attr( $css_class ); ?>">

		<?php
		if ($large_first_item == 'yes' && $view_mode !== 'list') : ?>
			<div class="prod-i special special-pseudo">
				<a href="#" class="prod-i-link"></a>
			</div>
		<?php endif; ?>

		<?php
		if (!isset($int_key)) {
			$int_key = 1;
		}
		while ($popular_products_query->have_posts()) : $popular_products_query->the_post();

			if ($view_mode == 'list') {
				include(locate_template('inc/shortcodes/loop-products-list.php'));
			} else {
				include(locate_template('inc/shortcodes/loop-products.php'));
			}

		endwhile;
		?>

	</div>
	<?php
	if ($popular_products_query->max_num_pages > $page_num) :
		if (isset($large_first_item) && $large_first_item == 'yes') {
			$items_per_page++;
		}
		$page_num++;
	?>
	<p class="special-more">
		<a
			class="special-more-btn" 
			href="#"
			data-container=".specials-list"
			data-url="<?php echo admin_url('admin-ajax.php'); ?>"
			data-page-num="<?php echo esc_attr($page_num); ?>"
			data-max-num-pages="<?php echo esc_attr($popular_products_query->max_num_pages); ?>"
			data-file="inc/shortcodes/products-content.php"
			data-order="<?php echo esc_attr($order); ?>"
			data-orderby="<?php echo esc_attr($orderby); ?>"
			data-posts_per_page="<?php echo esc_attr($items_per_page); ?>"
			data-item="<?php if ($view_mode == 'list') echo '.prod-li.sectls'; else echo '.prod-i.special'; ?>"
			data-view-mode="<?php if ($view_mode == 'list') echo 'list'; else echo 'gallery'; ?>"
			data-ids="<?php echo esc_attr($ids); ?>"
		><?php echo esc_html__('show more', 'motor'); ?></a>
	</p>
	<?php endif; ?>
<?php endif; wp_reset_postdata(); ?>