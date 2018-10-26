<article id="post-<?php the_ID(); ?>" <?php post_class('blog-grid-i'); ?>>
	<div class="blog-i">
		<?php if (!empty($blog_slider) && $blog_slider[0]) : ?>
			<div class="blog-slider">
				<ul class="slides">
					<?php foreach ($blog_slider as $blog_slide) :
						$img_src = wp_get_attachment_image_src($blog_slide, 'motor_blog_slider');
						?>
						<?php if (!empty($img_src[0])) : ?>
						<li>
							<a href="<?php the_permalink(); ?>">
								<?php if (!empty($img_src[0])) : ?>
									<img src="<?php echo esc_attr($img_src[0]); ?>" alt="<?php the_title(); ?>">
								<?php endif; ?>
							</a>
						</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php elseif (!empty($blog_video)) : ?>
			<div class="blog-img">
				<?php echo wp_oembed_get($blog_video); ?>
			</div>
		<?php elseif (has_post_thumbnail()) : ?>
			<a href="<?php the_permalink(); ?>" class="blog-img">
				<?php the_post_thumbnail('motor_blog'); ?>
			</a>
		<?php endif; ?>
		<p class="blog-info">
			<?php
			if (!empty($category)) {
				foreach ($category as $key=>$categ) {
					echo '<a href="'.esc_url(get_term_link($categ->term_id)).'">'.esc_attr($categ->name).'</a>';
					echo ($key+1<count($category)) ? ', ' : '';
				}
			}
			?>
			<time datetime="<?php echo get_the_date('Y-m-d H:i'); ?>"><?php echo get_the_date(); ?></time>
		</p>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p><?php echo get_the_excerpt(); ?> <a href="<?php the_permalink(); ?>"><?php echo esc_html__('read more', 'motor'); ?></a></p>
	</div>
</article>