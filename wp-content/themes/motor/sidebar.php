<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package motor
 */
?>
<aside id="secondary" class="blog-sb">
	<?php if ( is_active_sidebar( 'motor_sidebar' ) ) : ?>
		<div class="blog-sb-widgets">
		<?php dynamic_sidebar( 'motor_sidebar' ); ?>
		</div>
	<?php endif; ?>
</aside><!-- #secondary -->