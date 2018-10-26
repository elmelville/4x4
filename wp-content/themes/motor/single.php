<?php
get_header();

global $motor_options;
$sidebar_pos = $motor_options['blog_sidebar_post'];
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main">

<!-- Breadcrumbs -->
<div class="b-crumbs-wrap b-crumbs-wrap2">
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
	<div class="cont b-crumbs">
		<?php woocommerce_breadcrumb(array('wrap_before'=>'<ul>', 'wrap_after'=>'</ul>', 'before'=>'<li>', 'after'=>'</li>', 'delimiter'=>'')); ?>
	</div>
	<?php endif; ?>
</div>

<div class="maincont">

<?php if (have_posts()) : the_post(); ?>
	 
	<?php $category = get_the_category(); ?>
	
	<?php
	$in_top_slider = false;
	$slider = get_post_meta($post->ID, 'motor_post_repeatable_img', true);

	if (!empty($slider) && !empty($slider[0])) :
	?>
		<div class="post-slider">
			<ul class="slides">
				<?php foreach ($slider as $slide) :
					$slide_img = wp_get_attachment_image_src($slide, 'motor_full');
					?>
					<?php if (!empty($slide_img[0])) : ?>
					<li>
						<img src="<?php echo esc_attr($slide_img[0]); ?>" alt="<?php the_title(); ?>">
					</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php $in_top_slider = true; ?>
	<?php
	elseif (has_post_thumbnail()) :
		echo '<p class="post-img">';
		the_post_thumbnail('motor_full');
		echo '</p>';
		?>
	<?php endif; ?>

	<div class="cont<?php
		if ($sidebar_pos == 'right')
			echo ' post-sidebar';
		elseif ($sidebar_pos == 'left')
			echo ' post-sidebar-left';
		?>">

		<?php if ($sidebar_pos == 'left') : ?>
			<!-- Sidebar -->
			<?php get_sidebar(); ?>
		<?php endif; ?>
		
		<!-- Post Content - start -->
		<article id="post-<?php the_ID(); ?>" <?php post_class('s-post page-styling'); ?>>


			<div class="post-info">
				<?php
				if (!empty($category)) {
					foreach ($category as $key=>$categ) {
						echo '<a href="'.esc_url(get_term_link($categ->term_id)).'">'.esc_attr($categ->name).'</a>';
						echo ($key+1<count($category)) ? ', ' : '';
					}
				}
				?>
				<h1><?php the_title(); ?></h1>
				<time datetime="<?php echo get_the_date('Y-m-d H:i'); ?>"><span><?php echo get_the_date('d'); ?></span> <?php echo get_the_date('M'); ?></time>
			</div>

			<?php
			$video = get_post_meta($post->ID, 'motor_post_text', true);
			if (!empty($video)) :
				?>
				<div class="post-video">
					<?php echo wp_oembed_get($video); ?>
				</div>
			<?php endif; ?>

			<?php
			if ($in_top_slider && has_post_thumbnail()) {
				echo '<p class="post-img">';
				the_post_thumbnail('motor_full');
				echo '</p>';
			}
			?>

			<?php
			if (!$in_top_slider && !empty($slider) && !empty($slider[0])) :
			?>
			<div class="post-slider">
				<ul class="slides">
					<?php foreach ($slider as $slide) :
						$slide_img = wp_get_attachment_image_src($slide, 'motor_full');
						?>
						<?php if (!empty($slide_img[0])) : ?>
						<li>
							<img src="<?php echo esc_attr($slide_img[0]); ?>" alt="<?php the_title(); ?>">
						</li>
					<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>

			<?php
			the_content();

			wp_link_pages( array(
				'before'           => '<p class="link-pages">',
				'after'            => '</p>',
				'link_before'      => '<span>',
				'link_after'       => '</span>',
				'nextpagelink'     => '<i class="fa fa-angle-right"></i>',
				'previouspagelink' => '<i class="fa fa-angle-left"></i>',
			) );

			// Social Share
			if (!empty($motor_options['blog_share'])) {
				include( trailingslashit( get_template_directory() ) . 'template-parts/share.php' );
			}

			$closed_cmts_class = '';
			if ( !comments_open() ) {
				$closed_cmts_class = ' comments-closed';
			}

			the_tags('<div class="post-tags'.$closed_cmts_class.'"><span>'.esc_html__('Tags', 'motor').'</span>', '', '</div>');

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>
			
		</article>
		<!-- Post Content - end -->
		
		<?php if ($sidebar_pos == 'right') : ?>
			<!-- Sidebar -->
			<?php get_sidebar(); ?>
		<?php endif; ?>

	</div>
<?php endif; ?>

</div>

	</main><!-- #main -->
</div><!-- #primary -->


<?php get_footer(); ?>