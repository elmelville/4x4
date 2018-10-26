<?php

// Less Css Variables
require_once( trailingslashit( get_template_directory() ) . 'inc/less/less-vars.php' );

$motor_options['header_logo'] = get_theme_mod('header_logo', '');
$motor_options['header_search'] = get_theme_mod('header_search', 'simple');
$motor_options['header_before'] = get_theme_mod('header_before', '');
$motor_options['header_after'] = get_theme_mod('header_after', '');
$motor_options['header_compare'] = get_theme_mod('header_compare', 'show');
$motor_options['header_wishlist'] = get_theme_mod('header_wishlist', 'show');
$motor_options['header_profile'] = get_theme_mod('header_profile', 'show');
$motor_options['header_cart'] = get_theme_mod('header_cart', 'show');
$motor_options['header_sticky'] = get_theme_mod('header_sticky', '');
$motor_options['catalog_sidebar'] = get_theme_mod('catalog_sidebar', 'sticky');
$motor_options['catalog_viewmode'] = get_theme_mod('catalog_viewmode', 'gallery');
$motor_options['catalog_listprops'] = get_theme_mod('catalog_listprops', '9');
$motor_options['catalog_prodprops'] = get_theme_mod('catalog_prodprops', '7');
$motor_options['catalog_listinfo'] = get_theme_mod('catalog_listinfo', 'info');
$motor_options['catalog_cart'] = get_theme_mod('catalog_cart', 'modern');
$motor_options['catalog_prodtype'] = get_theme_mod('catalog_prodtype', 'type_1');
$motor_options['catalog_qviewtabs'] = get_theme_mod('catalog_qviewtabs', 'hide');
$motor_options['catalog_request'] = get_theme_mod('catalog_request', 'no');
$motor_options['catalog_requestform'] = get_theme_mod('catalog_requestform', '');
$motor_options['catalog_notroll'] = get_theme_mod('catalog_notroll', 'yes');
$motor_options['blog_sidebar'] = get_theme_mod('blog_sidebar', 'right');
$motor_options['blog_sidebar_post'] = get_theme_mod('blog_sidebar_post', 'hide');
$motor_options['blog_share'] = get_theme_mod('blog_share', array('facebook', 'twitter', 'vk', 'pinterest', 'gplus', 'linkedin', 'tumblr'));
$motor_options['footer_template'] = get_theme_mod('footer_template', '');
$motor_options['other_modalform'] = get_theme_mod('other_modalform', '');



$custom_vc_styles = array();
if (!empty($motor_options['header_before'])) {
    $custom_vc_styles[] = $motor_options['header_before'];
}
if (!empty($motor_options['header_after'])) {
    $custom_vc_styles[] = $motor_options['header_after'];
}
if (!empty($motor_options['footer_template'])) {
    $custom_vc_styles[] = $motor_options['footer_template'];
}
motor_include_vc_custom_styles($custom_vc_styles);




if (defined( 'WCCM_VERISON' )) {
    $compare_list = wccm_get_compare_list();
    $compare_page_id = get_option('wccm_compare_page');

    $motor_options['compare']['id'] = $compare_page_id;
    $motor_options['compare']['list'] = $compare_list;
    $motor_options['compare']['count'] = count($compare_list);
    if (!empty($compare_page_id)) {
        $motor_options['compare']['url'] = get_permalink($compare_page_id);
    }
}

if ( class_exists( 'YITH_WCWL' ) ) {
    $wishlist_page_id = yith_wcwl_object_id( get_option( 'yith_wcwl_wishlist_page_id' ) );

    $motor_options['wishlist']['id'] = $wishlist_page_id;
    if (!empty($wishlist_page_id)) {
        $motor_options['wishlist']['url'] = get_permalink($wishlist_page_id);
    }

}

if ( class_exists( 'WooCommerce' ) ) {

    $motor_options['cart']['id'] = get_option('woocommerce_cart_page_id');
    $motor_options['cart']['url'] = wc_get_cart_url();

    $motor_options['checkout']['id'] = get_option('woocommerce_checkout_page_id');
    $motor_options['checkout']['url'] = wc_get_checkout_url();

    /*$account_page_id = get_option('woocommerce_myaccount_page_id');
    $motor_options['account']['id'] = $account_page_id;
    if (!empty($account_page_id)) {
        $motor_options['account']['url'] = get_permalink($account_page_id);
    }*/
}


