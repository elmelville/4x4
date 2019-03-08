<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function the4x4guys_search_form_modify( $form ) {
    $html = '<div id="garage-btn"></div>';
    return $form . $html;
}

function the4x4guys_cm_product_query ($q) {
    if (!is_product_category() && !is_product_tag() && !is_shop()) {
        return;
    }
    foreach ($_GET as $k => $v) {
        if(substr($k, 0, 3) == 'cm_') {
            $f_name = substr($k, 3);
            $facets[$f_name][] = $v;
        }
    }
    $obj = get_queried_object();
    if (is_product_category()) {
        if ( $obj->parent ) {
            $parents = array_reverse( get_ancestors( $obj->term_id, 'product_cat' ) );
            $cat_full = array();
            foreach ( $parents as $parent ) {
                $term = get_term( $parent, 'product_cat' );
                $cat_full[] = htmlspecialchars_decode($term->name);
            }
            $cat_full[] = htmlspecialchars_decode($obj->name);
            $cat = implode('>', $cat_full);
            $facets['categories'][] = $cat;
        } else {
            $facets['categories'][] = htmlspecialchars_decode($obj->name);
        }
    } elseif(is_product_tag()) {
        $facets['tags'][] = htmlspecialchars_decode($obj->name);
    }
    $pagesize = $q->get( 'posts_per_page' );
    $page = ($q->get( 'paged' ) > 1) ? $q->get( 'paged' ) - 1 : 0 ;
    $orderby = 'post_date';
    $descending = false;
    if(!empty($_GET['orderby'])) {
        switch ($_GET['orderby']) {
            case 'cmdate':
                $orderby = 'post_date';
                break;
            case 'cmrating':
                $orderby = 'rating';
                break;
            case 'cmprice':
                $orderby = 'display_price';
                break;
            case 'cmprice-desc':
                $orderby = 'display_price';
                $descending = true;
                break;
            default:
                $orderby = 'post_date';
                break;
        }
    }
    $cm = new ConvermaxAPI();
    $data = $cm->search('', $page, $pagesize, $facets, $orderby, $descending, 'default');
    if ($data->Items) {
        foreach ($data->Items as $item) {
            $ids[] = (int)$item->ID;
        }
        $q->set( 'post__in', $ids );
        $q->set( 'orderby', 'post__in' );
        $q->set( 'order', 'ASC' );
        $q->set( 'offset', 0 );
        //$q->set( 'orderby', $ids );
    }
    $api_facets = array();
    if ($data->Facets) {
        $api_facets = $data->Facets;
    }
    add_filter( 'template_include', 'the4x4guys_category_template_include' );
    $total = $data->TotalHits;
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    add_action('woocommerce_sidebar', function () use ($api_facets) {
        cm_get_sidebar($api_facets);
    });
    add_filter( 'found_posts', function() use ( $total ) {
        return $total;
    });
    add_filter( 'woocommerce_catalog_orderby', 'cm_woocommerce_catalog_orderby' );
}

function the4x4guys_category_template_include( $template ) {
    return dirname(__FILE__) . '/templates/page-category.php';
}

function cm_get_sidebar($facets) {
    include_once(dirname(__FILE__) . '/templates/sidebar.php');
}

function cm_woocommerce_catalog_orderby( $options ) {
    unset($options['popularity']);
    return $options;
}

function cm_wp_nav_menu_items ($html) {
    $home = '<li class="cmm-item-depth-0 cmm-layout-full"><a href="/" class="cmm-nav-link menu-item menu-item-type-post_type menu-item-object-page"><span class="cmm-item-label">Home</span></a></li>';
    $branding = '<li class="menu-logo"><p class="header-logo"><a href="https://www.thejeepstop.com/"><img src="https://cdn.the4x4guys.com/wp-content/uploads/2018/10/26170242/4x4Guys.com_logo_4C.jpeg" alt="Staging Store"></a></p></li>';
    $close_icon = '<li class="close-menu"><div><svg viewPort="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg"><line x1="1" y1="11" x2="11" y2="1" stroke="black" stroke-width="2"/><line x1="1" y1="1" x2="11" y2="11" stroke="black" stroke-width="2"/></svg></div></li>';
    $categories = '<li class="menu-item-has-children cmm-item-depth-0 cmm-layout-full" data-settings="{&quot;width&quot;:&quot;&quot;,&quot;layout&quot;:&quot;full&quot;}"><a class="cmm-nav-link menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-67837"><span class="cmm-item-label">Categories</span></a><div class="cmm-sub-container"><ul class="sub-menu cmm-sub-wrapper">';
    $full_menu = $home.$categories.$branding.$close_icon;
    $full_menu .= hierarchical_category_tree(0);
    $full_menu .= '</div></li>';
    return $full_menu.$html;
}

function cm_wp_get_nav_menu_items ($items) {
    foreach ($items as $item) {
        if(strpos($item->url, '/search/') === false && strpos($item->url, '#') === false) {
            $newitems[] = $item;
        }
    }
    return $newitems;
}

function hierarchical_category_tree( $cat ) {
    $html = '';
    $next = get_categories('hide_empty=1&taxonomy=product_cat&parent=' . $cat);
    if( $next ) {
        foreach ($next as $cat) {
            if( stripos($cat->name, 'ministore-') === 0 ||
                stripos($cat->name, 'shopbyvehicle-') === 0 ||
                stripos($cat->name, 'test-store') === 0 ||
                stripos($cat->name, 'gift card') === 0) {
                continue;
            }
            $childs = get_categories('hide_empty=1&taxonomy=product_cat&parent=' . $cat->term_id);
            $html .= '<li class="' . (!empty($childs) ? ' menu-item-has-children"' : '') . ' cmm-layout-full" data-settings="{&quot;width&quot;:&quot;&quot;,&quot;layout&quot;:&quot;full&quot;}"><a href="' . get_term_link($cat->term_id) . '" class="cmm-nav-link menu-item menu-item-type-custom menu-item-object-custom ' . (!empty($childs) ? ' menu-item-has-children"' : '') . '"><span class="cmm-item-label">' . esc_attr($cat->name) . '</span></a>'.
                (!empty($childs) ? '<div class="cmm-sub-container"><ul class="sub-menu cmm-sub-wrapper">' : '');
            $html .= hierarchical_category_tree($cat->term_id);
            $html .= (!empty($childs) ? '</ul></div>' : '');
            $html .= '</li>';
        }
    }
    return $html;
}