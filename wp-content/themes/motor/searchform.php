<?php global $motor_options; ?>
<?php if ($motor_options['header_search'] == 'simple') : ?>
<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="header-search">
	<input value="<?php echo get_search_query(); ?>" name="s" type="text" placeholder="<?php echo esc_html__('Search for products...', 'motor'); ?>">
	<button type="submit"><i class="fa fa-search"></i></button>
</form>
<?php elseif ($motor_options['header_search'] == 'ajax' && shortcode_exists('wcas-search-form')): ?>
	<div class="header-search">
		<?php echo do_shortcode('[wcas-search-form]'); ?>
	</div>
<?php endif; ?>