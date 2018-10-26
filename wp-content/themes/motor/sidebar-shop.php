<?php
$catalog_sidebar = motor_option('catalog_sidebar', true);
?>
<aside class="blog-sb-widgets section-sb"<?php if ($catalog_sidebar == 'sticky') echo ' id="section-sb"'; ?>>
	<div class="theiaStickySidebar">
		<?php if ( is_active_sidebar( 'motor_sidebar_shop' ) ) : ?>
		<p class="section-filter-toggle<?php if ((!empty($_COOKIE['filter_toggle']) && $_COOKIE['filter_toggle'] == 'filter_hidden') || !isset($_COOKIE['filter_toggle'])) echo ' filter_hidden'; ?>">
			<a href="#" id="section-filter-toggle-btn"><?php if ((!empty($_COOKIE['filter_toggle']) && $_COOKIE['filter_toggle'] == 'filter_hidden') || !isset($_COOKIE['filter_toggle'])) esc_html_e('Show Filter', 'motor'); else esc_html_e('Hide Filter', 'motor'); ?></a>
		</p>
		<div class="section-filter" id="section-filter">
			<?php
			dynamic_sidebar( 'motor_sidebar_shop' );
			?>
		</div>
		<?php endif; ?>
	</div>
</aside>