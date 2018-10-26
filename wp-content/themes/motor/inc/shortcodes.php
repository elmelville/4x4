<?php
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/counter.php' );
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/promobox.php' );
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/pricing.php' );
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/team.php' );
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/infoblock.php' );
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/content_carousel.php' );
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/gallery.php' );
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/testimonials.php' );
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/iconbox.php' );
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/links_list.php' );
include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/filter.php' );
if ( class_exists( 'WooCommerce' ) ) {
	include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/product_categories.php' );
	include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/product_brands.php' );
	include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/products.php' );
}

// VC Row
vc_remove_param( "vc_row", "full_width" );

vc_add_param( "vc_row", array(
	'type' => 'dropdown',
	'heading' => esc_html__( 'Layout', 'motor' ),
	'param_name' => 'layout',
	'value' => array(
		esc_html__( 'Container', 'motor' )       => 'container',
		esc_html__( 'Full Width', 'motor' )      => 'full',
		esc_html__( 'Container Boxed', 'motor' ) => 'boxed',
	),
	'weight' => 10
) );

vc_add_param( "vc_row", array(
	'type' => 'textfield',
	'heading' => esc_html__( 'Row Title', 'motor' ),
	'param_name' => 'row_title',
	'weight' => 5
) );

vc_add_param( "vc_row", array(
	'type' => 'textfield',
	'heading' => esc_html__( 'Row Subtitle', 'motor' ),
	'param_name' => 'row_subtitle',
	'weight' => 5
) );


// VC Row Inner
vc_add_param( "vc_row_inner", array(
	'type' => 'dropdown',
	'heading' => esc_html__( 'Layout', 'motor' ),
	'param_name' => 'layout',
	'value' => array(
		esc_html__( 'Container', 'motor' )       => 'container',
		esc_html__( 'Full Width', 'motor' )      => 'full',
	),
	'weight' => 10
) );


// Custom Heading
vc_add_param( 'vc_custom_heading', array(
	'type' => 'checkbox',
	'heading' => esc_html__( 'Use theme default font family?', 'motor' ),
	'param_name' => 'use_theme_fonts',
	'value' => array( esc_html__( 'Yes', 'motor' ) => 'yes' ),
	'description' => esc_html__( 'Use font family from the theme.', 'motor' ),
	'std' => 'yes'
) );



// Separator
vc_add_param( 'vc_separator', array(
	'type' => 'dropdown',
	'heading' => esc_html__( 'Color', 'motor' ),
	'param_name' => 'color',
	'value' => array_merge( getVcShared( 'colors' ), array( esc_html__( 'Custom color', 'motor' ) => 'custom', esc_html__( 'Theme', 'motor' ) => 'theme' ) ),
	'std' => 'theme',
	'description' => esc_html__( 'Select color of separator.', 'motor' ),
	'param_holder_class' => 'vc_colored-dropdown'
) );
vc_add_param( 'vc_separator', array(
	'type' => 'dropdown',
	'heading' => esc_html__( 'Element width', 'motor' ),
	'param_name' => 'el_width',
	'value' => array(
		esc_html__( 'auto', 'motor' ) => 'auto',
		'100%' => '',
		'90%' => '90',
		'80%' => '80',
		'70%' => '70',
		'60%' => '60',
		'50%' => '50',
		'40%' => '40',
		'30%' => '30',
		'20%' => '20',
		'10%' => '10',
	),
	'description' => esc_html__( 'Select separator width (percentage).', 'motor' ),
) );

// Tour
vc_add_param( 'vc_tta_tour', array(
	'type' => 'textfield',
	'heading' => esc_html__( 'Extra class name', 'motor' ),
	'param_name' => 'el_class',
	'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'motor' ),
	'value' => 'motor-tour'
) );
// Tabs
vc_add_param( 'vc_tta_tabs', array(
	'type' => 'textfield',
	'heading' => esc_html__( 'Extra class name', 'motor' ),
	'param_name' => 'el_class',
	'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'motor' ),
	'value' => 'motor-tabs'
) );

// Tabs
vc_remove_param( "vc_tta_tabs", "color" );
vc_remove_param( "vc_tta_tour", "color" );


// WP Custom Menu
vc_add_param( 'vc_wp_custommenu', array(
	'type' => 'dropdown',
	'heading' => __( 'Style', 'motor' ),
	'param_name' => 'nav_style',
	'value' => array(
		esc_html__( 'Dark', 'motor' ) => 'dark',
		esc_html__( 'Light', 'motor' ) => 'light',
	),
	'std' => 'dark'
) );


