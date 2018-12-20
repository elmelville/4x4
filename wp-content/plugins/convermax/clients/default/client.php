<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

abstract class Client {

    public $batch_size = 300;

    public function initHooks() {
        add_filter( 'template_include', 'default_template_include' );
        add_filter( 'get_pages', 'default_exclude_page' );
        add_filter( 'body_class', 'default_body_class' );
        add_action( 'wp_enqueue_scripts', 'default_wp_enqueue_scripts' );
    }

    public function indexation($start, $batch_size) {
        $cm_api = new ConvermaxAPI();
        if ($cm_api->inProgress() && $start == 0 ) {
            return false;
        }
        if ( ! $cm_api->inProgress() ) {
            if ( ! $cm_api->batchStart() ) {
                return false;
            }
        }
        $products = $this->getProductsToIndex( $start, $batch_size );
        if ( ! $products ) {
            $cm_api->batchEnd();
            return false;
        }
        if ( ! $cm_api->batchAdd( $products ) ) {
            return false;
        }
        return true;
    }

    protected function getProductsToIndex($start, $limit) {
        global $wpdb;

        $query = "SELECT * FROM " . $wpdb->posts .
            " WHERE post_password = ''
		          AND post_status = 'publish'
		          AND post_type = 'product'
		          ORDER BY ID
		          LIMIT " . $start . ", " . $limit ;

        $posts = $wpdb->get_results($query);
        if ( ! $posts) {
            return false;
        }

        for ($i = 0; $i < count($posts); $i++) {
            $c = get_the_terms($posts[$i]->ID, 'product_cat');
            $cats = array();
            if (!empty($c) && is_array($c)) {
                foreach ( $c as $cat ) {
                    if ( $cat->parent ) {
                        $parents = array_reverse( get_ancestors( $cat->term_id, 'product_cat' ) );
                        $cat_full = array();
                        foreach ( $parents as $parent ) {
                            $term = get_term( $parent, 'product_cat' );
                            $cat_full[] = $term->name;
                        }
                        $cat_full[] = $cat->name;
                        $cats[] = implode('>', $cat_full);
                    } else {
                        $cats[] = $cat->name;
                    }
                }
            }
            $posts[$i]->categories = $cats;

            $t = get_the_terms($posts[$i]->ID, 'product_tag');
            $tags = array();
            if (!empty($t) && is_array($t)) {
                foreach ( $t as $tag ) {
                    $tags[] = $tag->name;
                }
            }
            $posts[$i]->tags = $tags;
            $product = wc_get_product( $posts[$i]->ID );
            $thumb_id = get_post_thumbnail_id( $posts[$i]->ID );
            $image = wp_get_attachment_image_src( $thumb_id, 'woocommerce_thumbnail' );
            if(!empty($image)) {
                $posts[$i]->image = $image[0];
            } elseif($attachment_ids = $product->get_gallery_image_ids()) {
                $image = wp_get_attachment_image_src( $attachment_ids[0], 'woocommerce_thumbnail' );
                $posts[$i]->image = $image[0];
                if(!empty($image)) {
                    $posts[$i]->image = $image[0];
                } else {
                    $posts[$i]->image = '';
                }
            } else {
                $posts[$i]->image = '';
            }

            $posts[$i]->display_price = array();
            $posts[$i]->regular_price = array();
            /*if ($product->product_type == 'variable') {
                $variables = $product->get_available_variations();
                foreach ($variables as $var) {
                    $posts[$i]->display_price[] = $var['display_price'];
                    $posts[$i]->regular_price[] = $var['display_regular_price'];
                }
            } else {*/
            $posts[$i]->display_price[] = (float)$product->get_display_price();
            $posts[$i]->regular_price[] = (float)$product->get_regular_price();
            //}
            $posts[$i]->url = $product->get_permalink();
            $post_meta = get_post_meta($posts[$i]->ID);

            foreach ( $product->get_attributes() as $attribute ) {
                $posts[$i]->{'att_' . $attribute['name']} = $product->get_attribute($attribute['name']);
            }

            foreach ($post_meta as $key => $value) {
                $m = array();
                if ($key == '_product_attributes') {
                    foreach ($value as $v) {
                        $m[] = unserialize($v);
                        foreach ($m as $att) {
                            foreach ($att as $att_key => $att_val) {
                                if ($att_key == 'product_images') {
                                    $posts[$i]->{'post_meta__product_attributes_' . $att_key} = $product->get_attribute($att_val['name']);
                                } else {
                                    $posts[ $i ]->{'post_meta__product_attributes_' . $att_key} = wc_get_product_terms( $posts[ $i ]->ID, $att_val['name'], array( 'fields' => 'names' ) );
                                }
                            }
                        }
                    }
                } else {
                    foreach ($value as $v) {
                        $m[] = $v;
                    }
                    $posts[$i]->{'post_meta_'.$key} = $m;
                }
            }
        }
        return $posts;
    }

    public function afterIndexation() {

    }
}