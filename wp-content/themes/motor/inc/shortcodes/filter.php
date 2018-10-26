<?php
add_action( 'vc_before_init', 'motor_filter_integrate_vc' );
function motor_filter_integrate_vc () {
    vc_map( array(
        'name' => esc_html__('Filter', 'motor'),
        'base' => 'motor_filter',
        'class' => '',
        'icon' => get_template_directory_uri() . "/img/vc_motor.png",
        'category' => esc_html__( 'Motor', 'motor' ),
        'params' => array(
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Price Filter', 'motor'),
                'param_name' => 'price_filter',
                'value' => array(
                    esc_html__('Yes', 'motor') => 'yes',
                    esc_html__('No', 'motor') => 'no',
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Autosubmit', 'motor'),
                'description' => esc_html__('Set Yes if you want to show in filter native woocommerce price filter', 'motor'),
                'param_name' => 'autosubmit',
                'value' => array(
                    esc_html__('No', 'motor') => 'no',
                    esc_html__('Yes', 'motor') => 'yes',
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Autohide', 'motor'),
                'param_name' => 'autohide',
                'value' => array(
                    esc_html__('No', 'motor') => 'no',
                    esc_html__('Yes', 'motor') => 'yes',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Taxonomies', 'motor'),
                'description' => esc_html__('Uses to display relevant filter-items in generated search form if activated: show count+dynamic recount+hide empty options in the plugin settings. Example: [woof is_ajax=1 taxonomies=product_cat:9,12+locations:30,31]', 'motor'),
                'param_name' => 'taxonomies',
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Taxonomies Only', 'motor'),
                'description' => esc_html__('Power attribute to assemble any search form with any taxonomies using  comma. Example: [woof tax_only=pa_color,pa_size by_only=by_sku,by_author]. Use none to not output all taxonomies. Order of taxonomies in the attribute tax_only has the sense!', 'motor'),
                'param_name' => 'tax_only',
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('By Only', 'motor'),
                'description' => esc_html__('Power attribute to assemble any search form with any taxonomies using  comma. Write any by-filter-elements there using comma: by_text,by_price,by_sku,by_author,by_onsales,by_instock. Of course firstly user should enable extensions for that by-filter-elements. Example: [woof tax_only=pa_color,pa_size by_only=by_sku,by_author]. Use none to not output all non-taxonomies elements', 'motor'),
                'param_name' => 'by_only',
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Tax Exclude', 'motor'),
                'description' => esc_html__('Excludes taxonomies from the search form. Example: [woof tax_exclude=pa_size,pa_test]', 'motor'),
                'param_name' => 'tax_exclude',
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Is AJAX', 'motor'),
                'param_name' => 'is_ajax',
                'value' => array(
                    esc_html__('No', 'motor') => 'no',
                    esc_html__('Yes', 'motor') => 'yes',
                ),
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Ajax Redraw', 'motor'),
                'description' => esc_html__('Redraws search form without submiting of the search data. Doesn work in AJAX mode. Example: [woof redirect=http://www.my_site.com/test-all/ autosubmit=1 ajax_redraw=1 is_ajax=1 tax_only=locations by_only=none].', 'motor'),
                'param_name' => 'ajax_redraw',
                'value' => array(
                    esc_html__('No', 'motor') => 'no',
                    esc_html__('Yes', 'motor') => 'yes',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => esc_html__('Redirect', 'motor'),
                'description' => esc_html__('Allows to show results on any another page of the site. Example: [woof redirect=http://www.my_site.com/search_page_results_77]. Does Not work in ajax mode - because redirect has no sense in ajax mode!', 'motor'),
                'param_name' => 'redirect',
            ),
            array(
                'type' => 'dropdown',
                'heading' => esc_html__('Columns', 'motor'),
                'param_name' => 'columns',
                'value' => array(
                    esc_html__('1', 'motor') => 'col_1',
                    esc_html__('2', 'motor') => 'col_2',
                    esc_html__('3', 'motor') => 'col_3',
                    esc_html__('4', 'motor') => 'col_4',
                ),
            ),
            array(
                'type' => 'css_editor',
                'heading' => esc_html__( 'Css', 'motor' ),
                'param_name' => 'css',
                'group' => esc_html__( 'Design options', 'motor' ),
            ),
        )
    ) );
}

class WPBakeryShortCode_motor_filter extends WPBakeryShortCode {
    protected function content( $atts, $content = null ) {

        $css = '';
        extract( shortcode_atts( array (
            'price_filter' => 'yes',
            'autosubmit' => 'no',
            'autohide' => 'no',
            'is_ajax' => 'no',
            'ajax_redraw' => 'no',
            'taxonomies' => '',
            'tax_only' => '',
            'by_only' => '',
            'tax_exclude' => '',
            'redirect' => '',
            'columns' => 'col_1',
            'css' => ''
        ), $atts ) );

        // start_filtering_btn - is generated searching form should be hidden (1) or not (0). In the case of the hidden form you will see simple button where after clicking on search form will be appeared. The best choice to avoid delay with the page loading when the search form is quite big. Text can be changed by hook woof_start_filtering_btn_txt

        $css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );


        $extra_class = '';
        if ($price_filter == 'no') {
            $extra_class .= ' section-filter-price-no';
        }

        if (in_array($columns, array('col_2', 'col_3', 'col_4'))) {
            $extra_class .= ' section-filter-columns section-filter-columns-'.$columns;
        }
        
        $shortcode = '[woof';
        if ($autosubmit == 'yes') {
            $shortcode .= ' autosubmit=1';
        } else {
            $shortcode .= ' autosubmit=0';
        }
        if ($autohide == 'yes') {
            $shortcode .= ' autohide=1';
        } else {
            $shortcode .= ' autohide=0';
        }
        if ($is_ajax == 'yes') {
            $shortcode .= ' is_ajax=1';
        } else {
            $shortcode .= ' is_ajax=0';
        }
        if ($ajax_redraw == 'yes') {
            $shortcode .= ' ajax_redraw=1';
        } else {
            $shortcode .= ' ajax_redraw=0';
        }
        if (!empty($tax_only)) {
            $shortcode .= " tax_only='".$tax_only."'";
        }
        if (!empty($taxonomies)) {
            $shortcode .= " taxonomies='".$taxonomies."'";
        }
        if (!empty($by_only)) {
            $shortcode .= " by_only='".$by_only."'";
        }
        if (!empty($tax_exclude)) {
            $shortcode .= " tax_exclude='".$tax_exclude."'";
        }
        if (!empty($redirect)) {
            $shortcode .= " redirect='".$redirect."'";
        }
        //$shortcode .= " ='product_cat'";
        $shortcode .= ']';

        if (shortcode_exists('woof')) :
        ?>
        <div class="section-filter section-filter-shortcode<?php echo $extra_class; ?><?php echo esc_attr( $css_class ); ?>">
            <?php echo do_shortcode($shortcode); ?>
        </div>
        <?php
        endif;

    }
}