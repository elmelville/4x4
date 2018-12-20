<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function default_template_include( $template ) {
    if ( is_page( ConvermaxAPI::get_option( 'convermax_searchpage' ) ) ) {
        return cm_get_template();
    }
    return $template;
}

function default_woocommerce_add_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
    $event_params = array(
        'ProductId' => $product_id
    );
    $cm = new ConvermaxAPI( false );
    $cm->track( 'AddToCart', $event_params );
}

function default_woocommerce_thankyou ( $order_id ) {
    $order = new WC_Order( $order_id );
    $items = $order->get_items();
    $ids = array();
    foreach ( $items as $item ) {
        $ids[] = $item['product_id'];
    }

    $event_params = array(
        'ProductId' => json_encode( $ids )
    );
    $cm = new ConvermaxAPI( false );
    $cm->track( 'ConfirmOrder', $event_params );
}

function default_exclude_page( $pages ) {
    $length = count($pages);
    $id = ConvermaxAPI::get_option( 'convermax_searchpage' );
    for ( $i = 0; $i < $length; $i++) {
        if ( $pages[$i]->ID == $id) {
            unset( $pages[$i] );
        }
    }
    return $pages;
}

function default_body_class($classes) {
    if ( is_page( ConvermaxAPI::get_option( 'convermax_searchpage' ) ) ) {
        $classes[] = 'woocommerce';
        $classes[] = 'woocommerce-page';
        $classes[] = 'convermax-page';
    }
    return $classes;
}

function default_wp_enqueue_scripts() {
    wp_enqueue_script( 'convermax', ConvermaxAPI::get_option('convermax_js_file'), array(), false, true );
    wp_enqueue_style( 'convermax', ConvermaxAPI::get_option('convermax_css_file') );
}