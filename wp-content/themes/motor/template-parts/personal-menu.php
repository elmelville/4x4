<?php global $motor_options; ?>

<ul class="cont-sections">
    <?php if (isset($motor_options['compare']['id'])) : ?>
    <li<?php if (get_the_id() == $motor_options['compare']['id']) echo ' class="active"'; ?>>
        <a href="<?php echo ($motor_options['compare']['id']) ? get_permalink($motor_options['compare']['id']) : ''; ?>"><?php echo esc_html__('Compare list', 'motor'); ?> <span><?php echo count($motor_options['compare']['list'])?></span></a>
    </li>
    <?php endif; ?>
    <?php if (isset($motor_options['wishlist']['id'])) : ?>
    <li<?php if (get_the_id() == $motor_options['wishlist']['id']) echo ' class="active"'; ?>>
        <a href="<?php echo ($motor_options['wishlist']['id']) ? get_permalink($motor_options['wishlist']['id']) : ''; ?>"><?php echo esc_html__('Wishlist', 'motor'); ?> <span><?php $count = YITH_WCWL()->count_products(); echo intval($count); ?></span></a>
    </li>
    <?php endif; ?>
    <?php if ( class_exists( 'WooCommerce' ) && $motor_options['cart']['url'] ) : ?>
    <li<?php if (get_the_id() == $motor_options['cart']['id']) echo ' class="active"'; ?>>
        <a href="<?php echo esc_url($motor_options['cart']['url']); ?>"><?php echo esc_html__('Shopping Cart', 'motor'); ?> <span><?php echo WC()->cart->get_cart_contents_count()?></span></a>
    </li>
    <?php endif; ?>
    <?php if ( class_exists( 'WooCommerce' ) && $motor_options['checkout']['url'] ) : ?>
    <li<?php if (get_the_id() == $motor_options['checkout']['id']) echo ' class="active"'; ?>>
        <a href="<?php echo esc_url($motor_options['checkout']['url']); ?>"><?php echo esc_html__('Checkout', 'motor'); ?></a>
    </li>
    <?php endif; ?>
    <li class="cont-sections-more"><span><?php esc_html_e('...', 'motor'); ?></span><ul class="cont-sections-sub"></ul></li>
</ul>