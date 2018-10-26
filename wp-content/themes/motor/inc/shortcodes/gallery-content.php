<?php
$page_num = (isset($_POST['page_num'])) ? $_POST['page_num'] : 1;
$items_per_page = (isset($_POST['posts_per_page'])) ? $_POST['posts_per_page'] : $items_per_page;
$order = (isset($_POST['order'])) ? $_POST['order'] : $order;
$orderby = (isset($_POST['orderby'])) ? $_POST['orderby'] : $orderby;
if (empty($gallery_category)) {
	$gallery_category = '';
}
$gallery_category = (isset($_POST['category'])) ? $_POST['category'] : $gallery_category;


$gallery = new WP_Query(array(
	'post_type' => 'motor_gallery',
	'post_status' => 'publish',
	'posts_per_page' => $items_per_page,
	'orderby' => $orderby,
	'order' => $order,
	'paged' => $page_num,
	'gallery_category' => $gallery_category
));
if ($gallery->have_posts()) : ?>
	<ul class="motor-gallery<?php echo esc_attr( $css_class ); ?>">
		<?php while ($gallery->have_posts()) : $gallery->the_post();

			$img_src_full = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
			$img_src = wp_get_attachment_image_src(get_post_thumbnail_id(), 'motor_gallery');

			global $post;
			$post_class = '';
			$categories = get_the_terms($post, 'gallery_category');
			if( is_array($categories) && count($categories) > 0 ) {
				foreach ($categories as $categ) {
					$post_class .= ' gallery-'.$categ->slug;
				}
			}

			$gallery_video_link = get_post_meta($post->ID, 'motor_gallery_video', true);
			?>
			<?php if (has_post_thumbnail()) : ?>
				<li class="gallery-grid-i<?php echo esc_attr($post_class); ?>">
					<?php if (!empty($gallery_video_link)) : ?>
						<a class="fancy-img motor-gallery-video" data-fancybox-group="gallery" href="<?php echo esc_url($gallery_video_link); ?>&fs=1&autoplay=1">
					<?php else: ?>
						<a class="fancy-img" data-fancybox-group="gallery" href="<?php echo esc_url($img_src_full[0]); ?>">
					<?php endif; ?>
						<span><img src="<?php echo esc_attr($img_src[0]); ?>" alt="<?php the_title(); ?>"></span>
					</a>
				</li>
			<?php endif; ?>
		<?php endwhile; ?>
	</ul>
	<?php if ($gallery->max_num_pages > $page_num) : ?>
		<p class="motor-gallery-more">
			<a
				href="#"
				data-url="<?php echo admin_url('admin-ajax.php'); ?>"
				data-container=".motor-gallery"
				data-page-num="<?php echo (esc_attr($page_num)+1); ?>"
				data-max-num-pages="<?php echo esc_attr($gallery->max_num_pages); ?>"
				data-file="inc/shortcodes/gallery-content.php"
				data-order="<?php echo esc_attr($order); ?>"
				data-orderby="<?php echo esc_attr($orderby); ?>"
				data-posts_per_page="<?php echo esc_attr($items_per_page); ?>"
				data-item=".gallery-grid-i"
				data-category="<?php echo esc_attr($gallery_category); ?>"
			><?php esc_html_e('show more', 'motor'); ?></a>
		</p>
	<?php endif; ?>
<?php endif; ?>