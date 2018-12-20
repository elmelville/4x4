<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class The4x4guys extends Client {

    public $batch_size = 200;

    public function initHooks()
    {
        parent::initHooks();
        add_filter( 'get_search_form', 'the4x4guys_search_form_modify' );
        add_action( 'woocommerce_product_query', 'the4x4guys_cm_product_query' );
        add_filter( 'wp_nav_menu_items', 'cm_wp_nav_menu_items' );
        add_filter( 'wp_get_nav_menu_items', 'cm_wp_get_nav_menu_items' );
    }

    public function indexation($start, $batch_size)
    {
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
        if ( ! $cm_api->batchAdd( $products['posts'] ) ) {
            return false;
        }
        if ( ! $cm_api->batchAdd( $products['fits'], 'fits' ) ) {
            return false;
        }
        return true;
    }

    protected function getProductsToIndex($start, $limit)
    {
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
        $fits_items = array();

        $postscount = count($posts);
        for ($i = 0; $i < $postscount; $i++) {
            $product = wc_get_product( $posts[$i]->ID );
            if (!$product->is_visible() || $product->get_stock_status() == 'outofstock') {
                unset( $posts[$i] );
                continue;
            }
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
                        if ($cat_full[0] != 'Brand' &&
                            stripos($cat->name, 'ministore-') === false &&
                            stripos($cat->name, 'shopbyvehicle-') === false &&
                            stripos($cat->name, 'shopybyvehicle-') === false &&
                            stripos($cat->name, 'test-store') === false &&
                            stripos($cat->name, 'gift card') === false) {
                            $cats[] = implode('>', $cat_full);
                        }
                    } else {
                        if ($cat->name != 'Brand' &&
                            stripos($cat->name, 'ministore-') === false &&
                            stripos($cat->name, 'shopbyvehicle-') === false &&
                            stripos($cat->name, 'shopybyvehicle-') === false &&
                            stripos($cat->name, 'test-store') === false &&
                            stripos($cat->name, 'gift card') === false) {
                            $cats[] = $cat->name;
                        }
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
                    $posts[$i]->image = wc_placeholder_img_src();
                }
            } else {
                $posts[$i]->image = wc_placeholder_img_src();
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
                if ($attribute['name'] == 'fits') {
                    $fits = $this->extractFits($product->get_attribute($attribute['name']), $posts[$i]->ID);
                    if (!empty($fits)) {
                        $posts[$i]->Vehicle = $fits['vehicle'];
                        foreach ($fits['item'] as $fit) {
                            $fits_items[] = $fit;
                        }
                    }
                } else {
                    $posts[$i]->{'att_' . $attribute['name']} = $product->get_attribute($attribute['name']);
                }
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
            if (preg_match('|^[a-z]{4}_|i', $product->get_sku())) {
                $posts[$i]->cropped_sku = substr($product->get_sku(), 5);
            } else {
                $posts[$i]->cropped_sku = isset($posts[$i]->post_meta__sku) ? $posts[$i]->post_meta__sku : '';
            }
        }
        $data['posts'] = array_values($posts);
        $data['fits'] = $fits_items;
        return $data;
    }

    private function extractFits($string, $id) {
        $d = explode('~', $string);
        for ($i = 0; $i < count($d); $i++) {
            $data = explode('`', $d[$i]);
            if (count($data) != 6 && count($data) != 5) {
                echo $id . ' - ' . $string . "\r\n";
                return '';
            }
            $years_arr = explode(';', $data[0]);
            $y = array();
            $submodel =  empty($data[3]) ? '' : ('/' . $data[3]);
            foreach ($years_arr as $years_range) {
                if (strpos($years_range, '-')) {
                    $years = explode('-', $years_range);
                    foreach (range($years[0], $years[1]) as $year) {
                        $fits[] = $year . '/' . $data[1] . '/' . $data[2] . $submodel;
                        $y[] = $year;
                    }
                } else {
                    $fits[] = $years_range . '/' . $data[1] . '/' . $data[2] . $submodel;
                    $y[] = (int)$years_range;
                }
            }
            $item['Year'] = $y;
            $item['Make'] = $data[1];
            $item['Model'] = $data[2];
            $item['Submodel'] = $data[3];
            $item['BodyType'] = $data[4];
            if (isset($data[5])) {
                $item['Bed'] = $data[5];
            }

            $ret['vehicle'] = $fits;
            $ret['item'][] = $item;
        }

        return $ret;
    }

    public function getFacetsPanel($facets) {
        include dirname(__FILE__) . '/templates/facetpanel.php';
    }

    private function getFieldsFromUrl() {
        $fields = array();
        foreach ($_GET as $k => $v) {
            if(substr($k, 0, 3) == 'cm_') {
                $fields[substr($k, 3)] = $v;
            }
        }
        return $fields;
    }

    private function getUrl($fields = NULL) {
        if (is_shop()) {
            $url = get_permalink(wc_get_page_id('shop'));
        } else {
            $obj = get_queried_object();
            $url = get_term_link($obj->term_id);
        }
        if (!empty($fields)) {
            $url .= strpos($url, '?') === false ? '?' : '&';
            $count = count($fields);
            $i = 0;
            foreach ($fields as $k => $v) {
                $url .= 'cm_' . $k . '=' . $v . (++$i == $count ? '' : '&');
            }
        }
        return $url;
    }

    private function getRemoveUrl($fields, $exluded_field) {
        unset($fields[$exluded_field]);
        return $this->getUrl($fields);
    }

    private function getFacetUrl($fieldname, $value) {
        if ($fieldname == 'categories') {
            $obj = get_term_by('name',  $value->Value, 'product_cat');
            if(is_product_category()) {
                $slug = $obj->slug;
                $url = $this->getUrl().$slug;
            } else {
                $url = get_term_link($obj->term_id);
            }
        } elseif ($fieldname == 'tags') {
            $obj = get_term_by('name',  $value->Value, 'product_tag');
            if (is_product_tag() || is_shop()) {
                $url = get_term_link($obj->term_id);
            }
        } else {
            $fields = $this->getFieldsFromUrl();
            $current_url = $this->getUrl($fields);
            $symbol = strpos($current_url, '?') ? '&' : '?';
            $url = $current_url.$symbol.urlencode('cm_'.$fieldname) .'='.urlencode($value->Term);
        }
        return $url;
    }

    private function displayFacet($facet) {
        if(!$facet->Values) {
            return false;
        }
        if ($facet->FieldName == 'Vehicle') {
            return false;
        }
        if (is_product_category()) {
            if (($facet->FieldName == 'categories' && (count($facet->Values) <= 1))) {
                return false;
            }
        }
        if (is_product_tag()) {
            if (($facet->FieldName == 'tags' && (count($facet->Values) <= 1))) {
                return false;
            }
        }
        return true;
    }

    public function afterIndexation() {
        try {
            if (is_plugin_active('cache-enabler/cache-enabler.php')) {
                Cache_Enabler::clear_total_cache();
            }
        }
        catch (\Exception $e) {}
    }
}