<?php
/**
 * Wishlist page template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.12
 */

if ( ! defined( 'YITH_WCWL' ) ) {
    exit;
} // Exit if accessed directly

global $motor_options;

?>

<?php do_action( 'yith_wcwl_before_wishlist_form', $wishlist_meta ); ?>


    <p class="section-count"><?php echo count( $wishlist_items ); ?> <?php echo _n( 'ITEM', 'ITEMS', count( $wishlist_items ), 'motor' ); ?></p>

    <form id="yith-wcwl-form" action="<?php echo esc_url( YITH_WCWL()->get_wishlist_url( 'view' . ( $wishlist_meta['is_default'] != 1 ? '/' . $wishlist_meta['wishlist_token'] : '' ) ) ) ?>" method="post" class="woocommerce">

        <?php wp_nonce_field( 'yith-wcwl-form', 'yith_wcwl_form_nonce' ) ?>

        <!-- TITLE -->
        <?php
        do_action( 'yith_wcwl_before_wishlist_title' );

        do_action( 'yith_wcwl_before_wishlist' ); ?>



        <?php
        if( count( $wishlist_items ) > 0 ) :
            ?>
            <div data-pagination="<?php echo esc_attr( $pagination )?>" data-per-page="<?php echo esc_attr( $per_page )?>" data-page="<?php echo esc_attr( $current_page )?>" data-id="<?php echo ( is_user_logged_in() ) ? esc_attr( $wishlist_meta['ID'] ) : '' ?>" data-token="<?php echo ( ! empty( $wishlist_meta['wishlist_token'] ) && is_user_logged_in() ) ? esc_attr( $wishlist_meta['wishlist_token'] ) : '' ?>" class="prod-litems section-list">
                <?php
                foreach( $wishlist_items as $item ) :
                    global $product;
                    if( function_exists( 'wc_get_product' ) ) {
                        $product = wc_get_product( $item['prod_id'] );
                    }

                    if( $product !== false && $product->exists() ) :
                        $availability = $product->get_availability();
                        $stock_status = $availability['class'];


                        global $woocommerce_loop;

                        // Store loop count we're currently on
                        if ( empty( $woocommerce_loop['loop'] ) ) {
                            $woocommerce_loop['loop'] = 0;
                        }

                        // Store column count for displaying the grid
                        if ( empty( $woocommerce_loop['columns'] ) ) {
                            $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
                        }

                        // Ensure visibility
                        if ( ! $product || ! $product->is_visible() ) {
                            return;
                        }

                        // Increase loop count
                        $woocommerce_loop['loop']++;




                        // Extra post classes
                        $classes = array();
                        if ( 0 === ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 === $woocommerce_loop['columns'] ) {
                            $classes[] = 'first';
                        }
                        if ( 0 === $woocommerce_loop['loop'] % $woocommerce_loop['columns'] ) {
                            $classes[] = 'last';
                        }
                        $classes[] = 'prod-li sectls';

                        $sku = $product->get_sku();
                        ?>

                        <article <?php post_class( $classes ); ?> id="yith-wcwl-row-<?php echo intval($item['prod_id']); ?>" data-row-id="<?php echo intval($item['prod_id']); ?>">

                            <div class="prod-li-inner">

                            <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>" class="prod-li-img">
                                <?php echo wp_kses_post($product->get_image()); ?>
                            </a>
                            <div class="prod-li-cont">
                                <?php
                                /**
                                 * woocommerce_before_shop_loop_item hook.
                                 *
                                 * @hooked woocommerce_template_loop_product_link_open - 10
                                 */
                                do_action( 'woocommerce_before_shop_loop_item' );
                                ?>
                                <div class="prod-li-ttl-wrap">
                                    <p>
                                        <?php
                                        $product_categories = get_the_terms( $item['prod_id'], 'product_cat' );
                                        if (!empty($product_categories)) :
                                            foreach ($product_categories as $key=>$product_category) :
                                                ?>
                                                <a href="<?php echo get_term_link($product_category); ?>"><?php echo esc_attr($product_category->name); ?></a><?php if ($key+1 < count($product_categories)) echo ',&nbsp;'; ?>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </p>

                                    <?php if( $show_cb ) : ?>
                                        <input type="checkbox" value="<?php echo esc_attr( $item['prod_id'] ) ?>" name="add_to_cart[]" <?php echo ( !$product->is_type('simple') ) ? 'disabled="disabled"' : '' ?>/>
                                    <?php endif ?>

                                    <?php
                                    /**
                                     * woocommerce_before_shop_loop_item_title hook.
                                     *
                                     * @hooked woocommerce_show_product_loop_sale_flash - 10
                                     * @hooked woocommerce_template_loop_product_thumbnail - 10
                                     */
                                    do_action( 'woocommerce_before_shop_loop_item_title' );
                                    ?>
                                    <?php
                                    /**
                                     * woocommerce_shop_loop_item_title hook.
                                     *
                                     * @hooked woocommerce_template_loop_product_title - 10
                                     */
                                    do_action( 'woocommerce_shop_loop_item_title' );
                                    ?>
                                    <h3><a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?></a></h3>
                                    <?php
                                    /**
                                     * woocommerce_after_shop_loop_item_title hook.
                                     *
                                     * @hooked woocommerce_template_loop_rating - 5
                                     * @hooked woocommerce_template_loop_price - 10
                                     */
                                    do_action( 'woocommerce_after_shop_loop_item_title' );
                                    ?>

                                    <?php do_action( 'yith_wcwl_table_after_product_name', $item ); ?>

                                    <?php if( $show_stock_status ) : ?>
                                        <p>
                                            <?php
                                            if( $stock_status == 'out-of-stock' ) {
                                                $stock_status = "Out";
                                                echo '<span class="wishlist-out-of-stock">' . esc_html__( 'Out of Stock', 'motor' ) . '</span>';
                                            } else {
                                                $stock_status = "In";
                                                echo '<span class="wishlist-in-stock">' . esc_html__( 'In Stock', 'motor' ) . '</span>';
                                            }
                                            ?>
                                        </p>
                                    <?php endif ?>


                                    <?php if( $show_last_column ): ?>
                                        <!-- Date added -->
                                        <?php
                                        if( $show_dateadded && isset( $item['dateadded'] ) ):
                                            echo '<p><span class="dateadded">' . sprintf( esc_html__( 'Added on : %s', 'motor' ), date_i18n( get_option( 'date_format' ), strtotime( $item['dateadded'] ) ) ) . '</span></p>';
                                        endif;
                                        ?>

                                        <!-- Change wishlist -->
                                        <?php if( $available_multi_wishlist && is_user_logged_in() && count( $users_wishlists ) > 1 && $move_to_another_wishlist ): ?>
                                            <select class="change-wishlist selectBox">
                                                <option value=""><?php esc_html_e( 'Move', 'motor' ) ?></option>
                                                <?php
                                                foreach( $users_wishlists as $wl ):
                                                    if( $wl['wishlist_token'] == $wishlist_meta['wishlist_token'] ){
                                                        continue;
                                                    }

                                                    ?>
                                                    <option value="<?php echo esc_attr( $wl['wishlist_token'] ) ?>">
                                                        <?php
                                                        $wl_title = ! empty( $wl['wishlist_name'] ) ? esc_html( $wl['wishlist_name'] ) : esc_html( $default_wishlsit_title );
                                                        if( $wl['wishlist_privacy'] == 1 ){
                                                            $wl_privacy = esc_html__( 'Shared', 'motor' );
                                                        }
                                                        elseif( $wl['wishlist_privacy'] == 2 ){
                                                            $wl_privacy = esc_html__( 'Private', 'motor' );
                                                        }
                                                        else{
                                                            $wl_privacy = esc_html__( 'Public', 'motor' );
                                                        }

                                                        echo sprintf( '%s - %s', $wl_title, $wl_privacy );
                                                        ?>
                                                    </option>
                                                    <?php
                                                endforeach;
                                                ?>
                                            </select>
                                        <?php endif; ?>

                                        <!-- Remove from wishlist -->
                                        <?php if( $is_user_owner && $repeat_remove_button ): ?>
                                            <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove_from_wishlist button" title="<?php esc_html_e( 'Remove this product', 'motor' ) ?>"><?php esc_html_e( 'Remove', 'motor' ) ?></a>
                                        <?php endif; ?>
                                    <?php endif; ?>



                                </div>
                                <div class="prod-li-price-wrap">
                                    <p><?php echo esc_html__('Price', 'motor'); ?></p>
                                    <?php if ( $price_html = $product->get_price_html() ) : ?><p class="prod-li-price"><?php echo $price_html; ?></p><?php endif; ?>
                                </div>
                                <div class="prod-li-qnt-wrap">
                                    <p><?php echo esc_html__('Quantity', 'motor'); ?></p>
                                    <p class="qnt-wrap prod-li-qnt">
                                        <a href="#" class="qnt-minus prod-li-minus"><?php echo esc_html__('-', 'motor'); ?></a>
                                        <input 
                                            data-qnt-price="<?php echo wc_get_price_to_display($product); ?>"
                                            data-decimals="<?php echo wc_get_price_decimals(); ?>" 
                                            data-thousand_separator="<?php echo wc_get_price_thousand_separator(); ?>" 
                                            data-decimal_separator="<?php echo wc_get_price_decimal_separator(); ?>" 
                                            data-currency="<?php echo get_woocommerce_currency_symbol(); ?>" 
                                            data-price_format="<?php echo get_woocommerce_price_format(); ?>" 
                                            type="text" 
                                            value="1"
                                        >
                                        <a href="#" class="qnt-plus prod-li-plus"><?php echo esc_html__('+', 'motor'); ?></a>
                                    </p>
                                </div>
                                <div class="prod-li-total-wrap">
                                    <p><?php echo esc_html__('Total', 'motor'); ?></p>
                                    <?php if ( $price_html = $product->get_price_html() ) : ?><p class="prod-li-total"><?php echo $price_html; ?></p><?php endif; ?>
                                </div>
                                <?php
                                /**
                                 * woocommerce_after_shop_loop_item hook.
                                 *
                                 * @hooked woocommerce_template_loop_product_link_close - 5
                                 * @hooked woocommerce_template_loop_add_to_cart - 10
                                 */
                                do_action( 'woocommerce_after_shop_loop_item' );
                                ?>
                            </div>
                            <div class="prod-li-info<?php if (empty($sku)) echo ' no-sku'; ?><?php if (!comments_open()) echo ' no-rating'; ?>">
                                <?php if ( comments_open($product) ) { ?>
                                <div class="prod-li-rating-wrap">
                                    <p data-rating="<?php echo round($product->get_average_rating()); ?>" class="prod-li-rating">
                                        <i class="fa fa-star-o" title="5"></i><i class="fa fa-star-o" title="4"></i><i class="fa fa-star-o" title="3"></i><i class="fa fa-star-o" title="2"></i><i class="fa fa-star-o" title="1"></i>
                                    </p>
                                    <p><span class="prod-li-rating-count"><?php echo intval($product->get_review_count()); ?></span> <?php echo _n( 'review', 'reviews', $product->get_review_count(), 'motor' ); ?></p>
                                </div>
                                <?php } ?>

                                <?php motor_list_info_button(); ?>

                                <p class="prod-li-add">
                                    <?php woocommerce_template_loop_add_to_cart(); ?>
                                </p>

                                <p class="prod-li-quick-view">
                                    <a href="#" class="quick-view" data-url="<?php echo admin_url('admin-ajax.php'); ?>" data-file="woocommerce/quickview-single-product.php" data-id="<?php echo intval($item['prod_id']); ?>"></a>
                                    <i class="fa fa-spinner fa-pulse quick-view-loading"></i>
                                </p>

                                <?php if( $is_user_owner ): ?>
                                    <p class="prod-li-favorites">
                                        <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>" class="remove remove_from_wishlist" title="<?php esc_html_e( 'Remove from Wishlist', 'motor' ) ?>"></a>
                                    </p>
                                <?php endif; ?>

                                <?php motor_show_compare_btn($item['prod_id']); ?>

                            </div>

                            <?php motor_product_badge($item['prod_id'], 'prod-li-badge'); ?>

                            </div>

                            <?php motor_list_info(); ?>

                        </article>



                        <?php
                    endif;
                endforeach;
                ?>


                <?php if( $show_cb ) : ?>
                    <div class="custom-add-to-cart-button-cotaniner">
                        <a href="<?php echo esc_url( add_query_arg( array( 'wishlist_products_to_add_to_cart' => '', 'wishlist_token' => $wishlist_meta['wishlist_token'] ) ) ) ?>" class="button alt" id="custom_add_to_cart"><?php echo apply_filters( 'yith_wcwl_custom_add_to_cart_text', esc_html__( 'Add the selected products to the cart', 'motor' ) ) ?></a>
                    </div>
                <?php endif; ?>

                <?php if ( is_user_logged_in() && $is_user_owner && $show_ask_estimate_button && $count > 0 ): ?>
                    <div class="ask-an-estimate-button-container">
                        <a href="<?php echo ( $additional_info ) ? '#ask_an_estimate_popup' : esc_url($ask_estimate_url); ?>" class="btn button ask-an-estimate-button" <?php echo ( $additional_info ) ? 'data-rel="prettyPhoto[ask_an_estimate]"' : '' ?> >
                            <?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' )?>
                            <?php echo apply_filters( 'yith_wcwl_ask_an_estimate_text', esc_html__( 'Ask for an estimate', 'motor' ) ) ?>
                        </a>
                    </div>
                <?php endif; ?>

                <?php
                do_action( 'yith_wcwl_before_wishlist_share' );

                if ( is_user_logged_in() && $is_user_owner && $wishlist_meta['wishlist_privacy'] != 2 && $share_enabled ){
                    yith_wcwl_get_template( 'share.php', $share_atts );
                }

                do_action( 'yith_wcwl_after_wishlist_share' );
                ?>

            </div>
            <?php
        else: ?>
            <div class="page-cont">
                <p class="wishlist-empty"><?php esc_html_e( 'No products were added to the wishlist', 'motor' ) ?></p>
            </div>
            <?php
        endif;

        if( ! empty( $page_links ) ) : ?>
            <?php echo wp_kses_post($page_links); ?>
        <?php endif ?>

        <?php wp_nonce_field( 'yith_wcwl_edit_wishlist_action', 'yith_wcwl_edit_wishlist' ); ?>

        <?php if( $wishlist_meta['is_default'] != 1 ): ?>
            <input type="hidden" value="<?php echo esc_attr($wishlist_meta['wishlist_token']); ?>" name="wishlist_id" id="wishlist_id">
        <?php endif; ?>

        <?php do_action( 'yith_wcwl_after_wishlist' ); ?>

    </form>

<?php do_action( 'yith_wcwl_after_wishlist_form', $wishlist_meta ); ?>

<?php if( $additional_info ): ?>
    <div id="ask_an_estimate_popup">
        <form action="<?php echo esc_url($ask_estimate_url); ?>" method="post" class="wishlist-ask-an-estimate-popup">
            <?php if( ! empty( $additional_info_label ) ):?>
                <label for="additional_notes"><?php echo esc_html( $additional_info_label ); ?></label>
            <?php endif; ?>
            <textarea id="additional_notes" name="additional_notes"></textarea>

            <button class="btn button ask-an-estimate-button ask-an-estimate-button-popup" >
                <?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' )?>
                <?php esc_html_e( 'Ask for an estimate', 'motor' ) ?>
            </button>
        </form>
    </div>
<?php endif; ?>