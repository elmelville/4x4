<?php
/**
 * motor functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package motor
 */


// Include the class (unless you are using the script as a plugin)
require_once( trailingslashit( get_template_directory() ) . 'inc/less/wp-less.php' );



if ( ! function_exists( 'motor_setup' ) ) {
	function motor_setup() {

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Loads the theme's translated strings.
		 */
		load_theme_textdomain( 'motor', get_template_directory() . '/languages' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'rw-top-menu' => esc_html__( 'Top Menu', 'motor' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Editor custom stylesheet - for user
		add_editor_style('css/editor-style.css');

		// Declare WooCommerce support
		add_theme_support( 'woocommerce' );

		// Add Image Sizes
		add_image_size( 'motor_gallery', '390', '234', array('center', 'center') );
		add_image_size( 'motor_blog', '370', '210', array('center', 'center') );
		add_image_size( 'motor_blog_slider', '370', '210', false );
		add_image_size( 'motor_full', '1920', '1000', false );
		add_image_size( 'motor_200x200', '200', '200', array('center', 'center') );

	}
}
add_action( 'after_setup_theme', 'motor_setup' );



/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function motor_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'motor_content_width', 1200 );
}
add_action( 'after_setup_theme', 'motor_content_width', 0 );


// Register widget area
add_action( 'widgets_init', 'motor_widgets_init' );
function motor_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Blog Sidebar', 'motor' ),
		'id'            => 'motor_sidebar',
		'before_widget' => '<div class="blog-sb-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>'
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'motor' ),
		'id'            => 'motor_sidebar_shop',
		'before_widget' => '<div class="blog-sb-widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>'
	) );
}


/**
 * Enqueue scripts and styles.
 */
function motor_scripts_styles() {

	// enqueue a .less stylesheet
	wp_enqueue_style( 'motor-less', get_template_directory_uri(). '/css/styles.less' );
	// Enqueue a styles
	wp_enqueue_style( 'motor-style', get_stylesheet_uri() );

	// Enqueue scripts
	wp_enqueue_script( 'jquery_plugins', get_template_directory_uri().'/js/jquery_plugins.js', array( 'jquery' ), null, true);
	wp_enqueue_script( 'motor-main', get_template_directory_uri().'/js/main.js', array( 'jquery' ), null, true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	/*if (is_singular('post') || is_page_template('page-contacts.php') || is_home() || is_post_type_archive('post') || is_search()) {
		global $motor_options;
		$gmaps_api = '';
		if (!empty($motor_options['motor_gmaps_api'])) {
			$gmaps_api = '?key='.esc_js($motor_options['motor_gmaps_api']);
		}
		wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js'.$gmaps_api, array(), null, true);
		wp_enqueue_script( 'motor-gmaps', get_template_directory_uri().'/js/gmaps.js', array( 'jquery' ), null, true);
	}*/

}
add_action( 'wp_enqueue_scripts', 'motor_scripts_styles' );



// Load More Ajax
add_action('wp_ajax_nopriv_motor_load_more', 'motor_load_more');
add_action('wp_ajax_motor_load_more', 'motor_load_more');
function motor_load_more () {
	if (isset($_POST['file'])) {
		include( trailingslashit( get_template_directory() ) . $_POST['file'] );
	}
	die();
}

// Gallery Load More Ajax
add_action('wp_ajax_nopriv_motor_gallery_load_more', 'motor_gallery_load_more');
add_action('wp_ajax_motor_gallery_load_more', 'motor_gallery_load_more');
function motor_gallery_load_more () {
	if (isset($_POST['file'])) {
		include( trailingslashit( get_template_directory() ) . $_POST['file'] );
	}
	die();
}

// Quick View Ajax
add_action('wp_ajax_nopriv_motor_quick_view', 'motor_quick_view');
add_action('wp_ajax_motor_quick_view', 'motor_quick_view');
function motor_quick_view () {

	if ( ! isset( $_REQUEST['product_id'] ) ) {
		die();
	}

	$product_id = intval( $_REQUEST['product_id'] );

	wp( 'p=' . $product_id . '&post_type=product' );

	if (isset($_POST['file'])) {
		include( trailingslashit( get_template_directory() ) . $_POST['file'] );
	}
	die();
}




/**
 * Set list of post types where VC editor is enabled.
 */
function detect_plugin_activation(  $plugin, $network_activation ) {
    if ($plugin == 'js_composer/js_composer.php') {
		if (function_exists( 'vc_is_as_theme' )) {
			$vc_editor_post_types = vc_editor_post_types();
			if (!in_array('product', $vc_editor_post_types)) {
				$vc_editor_post_types[] = 'product';
				//vc_set_default_editor_post_types( $vc_editor_post_types );
				vc_editor_set_post_types( $vc_editor_post_types );
			}
		}
    }
    if ($plugin == 'woocommerce/woocommerce.php') {
		if (function_exists( 'vc_is_as_theme' )) {
			$vc_editor_post_types = vc_editor_post_types();
			if (!in_array('product', $vc_editor_post_types)) {
				$vc_editor_post_types[] = 'product';
				//vc_set_default_editor_post_types( $vc_editor_post_types );
				vc_editor_set_post_types( $vc_editor_post_types );
			}
		}
    }
}
add_action( 'activated_plugin', 'detect_plugin_activation', 10, 2 );


// Kirki
if ( class_exists( 'Kirki' ) ) {
	// Load the Kirki Fallback class
	require get_parent_theme_file_path( '/inc/kirki-fallback.php' );
	// Customizer additions.
	require get_parent_theme_file_path( '/inc/customizer.php' );
}


// Theme Custom Fields
require_once( trailingslashit( get_template_directory() ) . 'inc/theme-fields.php' );

// Theme Functions
require_once( trailingslashit( get_template_directory() ) . 'inc/theme-functions.php' );

// WooCommerce Functions
require_once( trailingslashit( get_template_directory() ) . 'inc/woocommerce.php' );

// TGM Plugins
require_once( trailingslashit( get_template_directory() ) . 'inc/tgm.php' );

// Demo Import
require_once( trailingslashit( get_template_directory() ) . 'framework-customizations/theme/hooks.php' );

// VC Shortcodes
if ( function_exists( 'vc_is_as_theme' ) ) {
	require_once( trailingslashit( get_template_directory() ) . 'inc/shortcodes.php' );
}



function dequeue_yith_font_awesome_css() {
  wp_dequeue_style('yith-wcwl-font-awesome');
  wp_deregister_style('yith-wcwl-font-awesome');
}
add_action('wp_enqueue_scripts','dequeue_yith_font_awesome_css', 100);

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');



function _remove_query_strings_1( $src ){
	$rqs = explode( '?ver', $src );
	return $rqs[0];
}
if ( is_admin() ) {
// Remove query strings from static resources disabled in admin
}
else {
	add_filter( 'script_loader_src', '_remove_query_strings_1', 15, 1 );
	add_filter( 'style_loader_src', '_remove_query_strings_1', 15, 1 );
}




function motor_megamenu_scripts_styles() {
	$screen = get_current_screen();
	if ($screen->base == 'nav-menus') {
		return;
	}

	wp_deregister_script('wp-color-picker-alpha');
	wp_deregister_script('clever-mega-menu-admin');
	wp_register_script('clever-mega-menu-admin', get_template_directory_uri() . '/js/clever-mega-menu-admin.min.js', array(), false, true );
}
add_action( 'admin_enqueue_scripts', 'motor_megamenu_scripts_styles', 200 );



/**
 * To add WooCommerce registration form custom fields.
 */

function text_domain_woo_reg_form_fields() {
    ?>
    <p class="form-row form-row-first">
        <label for="billing_first_name"><?php _e('First name', 'text_domain'); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_first_name" id="billing_first_name" value="<?php if (!empty($_POST['billing_first_name'])) esc_attr_e($_POST['billing_first_name']); ?>" />
    </p>
    <p class="form-row form-row-last">
        <label for="billing_last_name"><?php _e('Last name', 'text_domain'); ?><span class="required">*</span></label>
        <input type="text" class="input-text" name="billing_last_name" id="billing_last_name" value="<?php if (!empty($_POST['billing_last_name'])) esc_attr_e($_POST['billing_last_name']); ?>" />
    </p>
    <div class="clear"></div>
    <?php
}

add_action('woocommerce_register_form_start', 'text_domain_woo_reg_form_fields');

/**
 * To save WooCommerce registration form custom fields.
 */
function text_domain_woo_save_reg_form_fields($customer_id) {
    //First name field
    if (isset($_POST['billing_first_name'])) {
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']));
        update_user_meta($customer_id, 'billing_first_name', sanitize_text_field($_POST['billing_first_name']));
    }
    //Last name field
    if (isset($_POST['billing_last_name'])) {
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']));
        update_user_meta($customer_id, 'billing_last_name', sanitize_text_field($_POST['billing_last_name']));
    }
}

//add recaptcha file to head
function my_custom_js() {
    echo "<script src='https://www.google.com/recaptcha/api.js'></script>";
}

add_action( 'wp_head', 'my_custom_js' );

//recaptcha send on submit to form
add_action('woocommerce_created_customer', 'text_domain_woo_save_reg_form_fields');

function wooc_validate_re_captcha_field( $username, $email, $wpErrors )
{
    $remoteIP = $_SERVER['REMOTE_ADDR'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    $response = wp_remote_post( 'https://www.google.com/recaptcha/api/siteverify', [
        'body' => [
            'secret'   => '6LfiEHcUAAAAAOajIrLRaZ7euoHvMaUCg6isHYx0',
            'response' => $recaptchaResponse,
            'remoteip' => $remoteIP
        ]
    ] );

    $response_code = wp_remote_retrieve_response_code( $response );
    $response_body = wp_remote_retrieve_body( $response );

    if ( $response_code == 200 )
    {
        $result = json_decode( $response_body, true );

        if ( ! $result['success'] )
        {
            switch ( $result['error-codes'] )
            {
                case 'missing-input-secret':
                case 'invalid-input-secret':
                    $wpErrors->add( 'recaptcha', __( '<strong>ERROR</strong>: Invalid reCAPTCHA secret key.', 'woocommerce' ) );
                    break;

                case 'missing-input-response' :
                case 'invalid-input-response' :
                    $wpErrors->add( 'recaptcha', __( '<strong>ERROR</strong>: Please check the box to prove that you are not a robot.', 'woocommerce' ) );
                    break;

                default:
                    $wpErrors->add( 'recaptcha', __( '<strong>ERROR</strong>: Something went wront validating the reCAPTCHA.' . $result['error-codes'], 'woocommerce' ) );
                    break;
            }
        }
    }
    else
    {
        $wpErrors->add( 'recaptcha_error', __( '<strong>Error</strong>: Unable to reach the reCAPTCHA server.', 'woocommerce' ) );
    }
}
add_action( 'woocommerce_register_post', 'wooc_validate_re_captcha_field', 10, 3 );