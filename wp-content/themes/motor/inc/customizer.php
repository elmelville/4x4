<?php
/*
 * MultiShop Theme Customizer.
 */


/*
 * Add the theme configuration
 */
motor_options::add_config( 'motor', array(
	'option_type' => 'theme_mod',
	'capability'  => 'edit_theme_options',
) );


$pages = array();
if (current_user_can( 'manage_options' )) {
	$pages = Kirki_Helper::get_posts( array( 'posts_per_page' => -1, 'post_type' => 'page', 'post_status'=>'any' ));
	$pages[0] = '';
}


/*
 * Header
 */

motor_options::add_section( 'motor_header', array(
	'title'      => esc_attr__( 'Header', 'motor' ),
	'priority'   => 1,
	'capability' => 'edit_theme_options',
) );

motor_options::add_field( 'motor', array(
	'type'        => 'image',
	'settings'    => 'header_logo',
	'label'       => esc_html__( 'Logotype', 'motor' ),
	'default'     => '',
	'section'     => 'motor_header',
	'priority'    => 10,
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'header_search',
	'label'       => esc_html__( 'Search Type', 'motor' ),
	'section'     => 'motor_header',
	'default'     => 'simple',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'ajax' => esc_attr__( 'AJAX Search', 'motor' ),
		'simple' => esc_attr__( 'Simple Search', 'motor' ),
		'hide' => esc_attr__( 'Hide', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'select',
	'settings'    => 'header_before',
	'label'       => esc_attr__( 'Before Header Template', 'motor' ),
	'description'    => esc_html__( 'Editing in Pages', 'motor' ),
	'section'     => 'motor_header',
	'default'     => '0',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => $pages,
) );
motor_options::add_field( 'motor', array(
	'type'        => 'select',
	'settings'    => 'header_after',
	'label'       => esc_attr__( 'After Header Template', 'motor' ),
	'description'    => esc_html__( 'Editing in Pages', 'motor' ),
	'section'     => 'motor_header',
	'default'     => '0',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => $pages,
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'header_compare',
	'label'       => esc_html__( 'Compare Link', 'motor' ),
	'description' => esc_html__( 'To remove functionality just deactivate Compare plugin', 'motor' ),
	'section'     => 'motor_header',
	'default'     => 'show',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'show' => esc_attr__( 'Show', 'motor' ),
		'hide' => esc_attr__( 'Hide', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'header_wishlist',
	'label'       => esc_html__( 'Wishlist Link', 'motor' ),
	'description' => esc_html__( 'To remove functionality just deactivate Wishlist plugin', 'motor' ),
	'section'     => 'motor_header',
	'default'     => 'show',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'show' => esc_attr__( 'Show', 'motor' ),
		'hide' => esc_attr__( 'Hide', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'header_profile',
	'label'       => esc_html__( 'Profile Link', 'motor' ),
	'section'     => 'motor_header',
	'default'     => 'show',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'show' => esc_attr__( 'Show', 'motor' ),
		'hide' => esc_attr__( 'Hide', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'header_cart',
	'label'       => esc_html__( 'Cart Link', 'motor' ),
	'section'     => 'motor_header',
	'default'     => 'show',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'show' => esc_attr__( 'Show', 'motor' ),
		'hide' => esc_attr__( 'Hide', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'checkbox',
	'settings'    => 'header_sticky',
	'label'       => esc_html__( 'Header Sticky', 'motor' ),
	'section'     => 'motor_header',
	'default'     => '',
) );



/*
 * Footer
 */
motor_options::add_section( 'motor_footer', array(
	'title'      => esc_attr__( 'Footer', 'motor' ),
	'priority'   => 3,
	'capability' => 'edit_theme_options',
) );

motor_options::add_field( 'motor', array(
	'type'        => 'select',
	'settings'    => 'footer_template',
	'label'       => esc_attr__( 'Footer Template', 'motor' ),
	'description'    => esc_html__( 'Editing in Pages', 'motor' ),
	'section'     => 'motor_footer',
	'default'     => '',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => $pages,
) );




/*
 * Catalog
 */
motor_options::add_section( 'motor_catalog', array(
	'title'      => esc_attr__( 'Catalog', 'motor' ),
	'priority'   => 3,
	'capability' => 'edit_theme_options',
) );

motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'catalog_viewmode',
	'label'       => esc_html__( 'Default View Mode', 'motor' ),
	'section'     => 'motor_catalog',
	'default'     => 'gallery',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'gallery' => esc_attr__( 'Gallery', 'motor' ),
		'list' => esc_attr__( 'List', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'catalog_sidebar',
	'label'       => esc_html__( 'Sidebar', 'motor' ),
	'section'     => 'motor_catalog',
	'default'     => 'sticky',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'sticky' => esc_attr__( 'Sticky', 'motor' ),
		'show' => esc_attr__( 'Show', 'motor' ),
		'hide' => esc_attr__( 'Hide', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'     => 'text',
	'settings' => 'catalog_listprops',
	'label'    => esc_html__( 'Catalog - Short Additional Information Rows', 'motor' ),
	'description'    => esc_html__( 'Short additional information rows count in Catalog', 'motor' ),
	'section'  => 'motor_catalog',
	'default'     => '9',
	'priority' => 10,
) );
motor_options::add_field( 'motor', array(
	'type'     => 'text',
	'settings' => 'catalog_prodprops',
	'label'    => esc_html__( 'Product - Short Additional Information Rows', 'motor' ),
	'description'    => esc_html__( 'Short additional information rows count in Product Page', 'motor' ),
	'section'  => 'motor_catalog',
	'default'     => '7',
	'priority' => 10,
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'catalog_qviewtabs',
	'label'       => esc_html__( 'Tabs in Quick view', 'motor' ),
	'section'     => 'motor_catalog',
	'default'     => 'hide',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'show' => esc_attr__( 'Show', 'motor' ),
		'hide' => esc_attr__( 'Hide', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'catalog_prodtype',
	'label'       => esc_html__( 'Product Add to cart Position Type', 'motor' ),
	'section'     => 'motor_catalog',
	'default'     => 'type_1',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'type_1' => esc_attr__( 'Type 1 (Grid View)', 'motor' ),
		'type_2' => esc_attr__( 'Type 2 (Standart View with more Space)', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'catalog_listinfo',
	'label'       => esc_html__( 'List View Information Button', 'motor' ),
	'section'     => 'motor_catalog',
	'default'     => 'info',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'hide' => esc_attr__( 'Hide', 'motor' ),
		'info' => esc_attr__( 'Additional Information', 'motor' ),
		'desc' => esc_attr__( 'Short Description', 'motor' ),
		'both' => esc_attr__( 'Show Both', 'motor' ),
		'sku' => esc_attr__( 'Show SKU instead', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'catalog_cart',
	'label'       => esc_html__( 'Cart Template', 'motor' ),
	'section'     => 'motor_catalog',
	'default'     => 'modern',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'modern' => esc_attr__( 'Modern', 'motor' ),
		'classic' => esc_attr__( 'Classic', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'catalog_request',
	'label'       => esc_html__( 'Request', 'motor' ),
	'description'       => esc_html__( 'Request button instead Add to Cart', 'motor' ),
	'section'     => 'motor_catalog',
	'default'     => 'no',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'no' => esc_attr__( 'No', 'motor' ),
		'yes' => esc_attr__( 'Yes', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'     => 'text',
	'settings' => 'catalog_requestform',
	'label'    => esc_html__( 'Request Modal Form', 'motor' ),
	'description'    => esc_html__( 'Contact Form 7 shortcode', 'motor' ),
	'section'  => 'motor_catalog',
	'sanitize_callback' => 'wp_kses_post',
	'priority' => 10,
) );
motor_options::add_field( 'motor', array(
	'type'     => 'radio',
	'settings' => 'catalog_notroll',
	'label'    => esc_html__( 'Grid View Title Roll Over Effect', 'motor' ),
	'section'     => 'motor_catalog',
	'default'     => 'yes',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'yes' => esc_attr__( 'Yes', 'motor' ),
		'no' => esc_attr__( 'No', 'motor' ),
	),
) );




/*
 * BLOG
 */
motor_options::add_section( 'motor_blog', array(
	'title'      => esc_attr__( 'Blog', 'motor' ),
	'priority'   => 3,
	'capability' => 'edit_theme_options',
) );

motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'blog_sidebar',
	'label'       => esc_html__( 'Sidebar Blog', 'motor' ),
	'section'     => 'motor_blog',
	'default'     => 'right',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'hide' => esc_attr__( 'Hide', 'motor' ),
		'right' => esc_attr__( 'Right', 'motor' ),
		'left' => esc_attr__( 'Left', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'radio',
	'settings'    => 'blog_sidebar_post',
	'label'       => esc_html__( 'Sidebar Post', 'motor' ),
	'section'     => 'motor_blog',
	'default'     => 'hide',
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'hide' => esc_attr__( 'Hide', 'motor' ),
		'right' => esc_attr__( 'Right', 'motor' ),
		'left' => esc_attr__( 'Left', 'motor' ),
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'multicheck',
	'settings'    => 'blog_share',
	'label'       => esc_html__( 'Post Share', 'motor' ),
	'section'     => 'motor_blog',
	'default'     => array(
						'facebook',
						'twitter',
						'vk',
						'pinterest',
						'gplus',
						'linkedin',
						'tumbrl',
					),
	'priority'    => 10,
	'multiple'    => 1,
	'choices'     => array(
		'facebook' => esc_html__('Facebook', 'motor'),
		'twitter' => esc_html__('Twitter', 'motor'),
		'vk' => esc_html__('VK.com', 'motor'),
		'pinterest' => esc_html__('Pinterest', 'motor'),
		'gplus' => esc_html__('Google Plus', 'motor'),
		'linkedin' => esc_html__('Linkedin', 'motor'),
		'tumblr' => esc_html__('Tumblr', 'motor'),
	),
) );





/*
 * COLORS
 */
motor_options::add_section( 'motor_color', array(
	'title'      => esc_attr__( 'Colors', 'motor' ),
	'priority'   => 4,
	'capability' => 'edit_theme_options',
) );

motor_options::add_field( 'motor', array(
	'type'        => 'color',
	'settings'    => 'color_primary',
	'label'       => __( 'Main color', 'motor' ),
	'description' => __( 'Default color: #ff3100', 'motor' ),
	'section'     => 'motor_color',
	'default'     => '#ff3100',
	'priority'    => 10,
	'choices'     => array(
		'alpha' => true,
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'color',
	'settings'    => 'color_body',
	'label'       => __( 'Body Background', 'motor' ),
	'description' => __( 'Default color: #f4f5fb', 'motor' ),
	'section'     => 'motor_color',
	'default'     => '#f4f5fb',
	'priority'    => 10,
	'choices'     => array(
		'alpha' => true,
	),
) );
motor_options::add_field( 'motor', array(
	'type'        => 'color',
	'settings'    => 'color_header',
	'label'       => __( 'Header Background', 'motor' ),
	'description' => __( 'Default color: #18202e', 'motor' ),
	'section'     => 'motor_color',
	'default'     => '#18202e',
	'priority'    => 10,
	'choices'     => array(
		'alpha' => true,
	),
) );




/*
 * FONTS
 */
motor_options::add_section( 'motor_fonts', array(
	'title'      => esc_attr__( 'Fonts', 'motor' ),
	'priority'   => 4,
	'capability' => 'edit_theme_options',
) );

motor_options::add_field( 'motor', array(
	'type'        => 'typography',
	'settings'    => 'fonts_main',
	'label'       => esc_html__( 'Main Font', 'motor' ),
	'description'       => esc_html__( 'Default: Open Sans', 'motor' ),
	'section'     => 'motor_fonts',
	'default'     => array(
		'font-family'    => 'Open Sans',
		'variant'        => '400',
		'font-size'      => '15px',
		'line-height'    => '1.8',
		'letter-spacing' => '0px',
		'text-transform' => 'none',
		'text-align'     => 'left',
		'color'          => '#868ca7',
		'subsets'        => array(),
	),
	'priority'    => 10,
) );
motor_options::add_field( 'motor', array(
	'type'        => 'typography',
	'settings'    => 'fonts_main_ttl',
	'label'       => esc_html__( 'Main Title Font', 'motor' ),
	'description'       => esc_html__( 'Default: "Montserrat", 30px, Weight 900, #373d54, Line Height 1.1, Text-transform "Uppercase", Letter-spacing 0.05em', 'motor' ),
	'section'     => 'motor_fonts',
	'default'     => array(
		'font-family'    => 'Montserrat',
		'variant'        => '900',
		'font-size'      => '30px',
		'line-height'    => '1.1',
		'letter-spacing' => '0.05em',
		'subsets'        => array(),
		'text-transform' => 'uppercase',
		'color'          => '#283346',
		/*'text-align'     => 'left'*/
	),
	'priority'    => 10,
) );
motor_options::add_field( 'motor', array(
	'type'        => 'typography',
	'settings'    => 'fonts_normal_ttl',
	'label'       => esc_html__( 'Normal Title Font', 'motor' ),
	'description'       => esc_html__( 'Default: "Montserrat", 30px, Weight 700, #373d54, Line Height 1.1, Text-transform "Uppercase", Letter-spacing 0.05em', 'motor' ),
	'section'     => 'motor_fonts',
	'default'     => array(
		'font-family'    => 'Montserrat',
		'variant'        => '700',
		/*'font-size'      => '30px',*/
		/*'line-height'    => '1.1',*/
		'letter-spacing' => '0px',
		'subsets'        => array(),
		'text-transform' => 'none',
		'color'          => '#283346',
		/*'text-align'     => 'left'*/
	),
	'priority'    => 10,
) );



/*
 * OTHER
 */
motor_options::add_section( 'motor_other', array(
	'title'      => esc_attr__( 'Other', 'motor' ),
	'priority'   => 3,
	'capability' => 'edit_theme_options',
) );

motor_options::add_field( 'motor', array(
	'type'     => 'text',
	'settings' => 'other_modalform',
	'label'       => esc_html__( 'Modal Form', 'motor' ),
	'description'       => esc_html__( 'Contact Form 7 Shortcode', 'motor' ),
	'section'  => 'motor_other',
	'sanitize_callback' => 'wp_kses_post',
	'default'     => '',
	'priority' => 10,
) );
