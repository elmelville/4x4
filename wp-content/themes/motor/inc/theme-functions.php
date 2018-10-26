<?php
// Get Options
if (!function_exists('motor_option')) {
	function motor_option($option, $allow_get = false) {
		global $motor_options;
		if (!empty($_GET[$option]) && $allow_get) {
			$value = $_GET[$option];
		} elseif (!empty($motor_options[$option])) {
			$value = $motor_options[$option];
		} elseif (empty($motor_options)) {
			include( trailingslashit( get_template_directory() ) . 'inc/get-options.php');
			$value = $motor_options[$option];
		} else {
			$value = get_theme_mod($option, '');
		}

		return $value;
	}
}

// Print wp_nav_menu with menu title
function motor_print_nav_menu ($location = '') {
	$menu_loc_1 = $location;
	if (has_nav_menu($menu_loc_1)) {
		$locations = get_nav_menu_locations();
		$menu_id = $locations[$menu_loc_1];
		$menu_obj_1 = wp_get_nav_menu_object($menu_id);
		$menu_ttl = '';
		if (!empty($menu_obj_1->name)) {
			$menu_ttl = '<p>'.esc_html($menu_obj_1->name).'</p>';
		}
		wp_nav_menu( array(
			'theme_location' => $menu_loc_1,
			'container' => '',
			'container_class' => '',
			'menu_class' => '',
			'items_wrap' => $menu_ttl.'<ul>%3$s</ul>',
		) );
	}
}


// Excerpt More from "[...]" to "..."
function motor_excerpt_more ($more) {
	return "...";
}
add_filter("excerpt_more", "motor_excerpt_more");


// New Excerpt Length
function motor_new_excerpt_length($length) {
	global $post;
	if ($post->post_type == 'post')
		return 20;
	elseif ($post->post_type == 'product')
		return 20;
	return 55;
}
add_filter('excerpt_length', 'motor_new_excerpt_length');


// Comment Template
function motor_comment($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
	$author  = get_comment_author( $comment );
	if (!empty($comment->user_id)) {
		$user_author_meta = get_user_meta($comment->user_id);
	}
	?>
<li <?php comment_class('post-comment'); ?> id="li-comment-<?php comment_ID() ?>">
	<div id="comment-<?php comment_ID(); ?>">
		<p class="post-comment-img">
			<?php if (function_exists('get_wp_user_avatar')) : ?>
				<?php echo get_wp_user_avatar($comment->comment_author_email); ?>
			<?php else: ?>
				<?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			<?php endif; ?>
		</p>
		<h4 class="post-comment-ttl"><?php
			if (!empty($user_author_meta['first_name'][0]) || !empty($user_author_meta['last_name'][0])) {
				if (!empty($user_author_meta['first_name'][0])) {
					echo esc_attr($user_author_meta['first_name'][0]).' ';
				}
				if (!empty($user_author_meta['last_name'][0])) {
					echo esc_attr($user_author_meta['last_name'][0]);
				}
			} else {
				echo get_comment_author_link();
			}
			?> <?php edit_comment_link('(Edit)', '  ', '') ?></h4>
		<?php if ($comment->comment_approved == '0') : ?>
			<p><i><?php echo esc_html__( 'Your comment is awaiting moderation.', 'motor' ); ?></i></p>
		<?php endif; ?>
		<p class="comment-meta commentmetadata">
			<?php printf( '%1$s at %2$s', get_comment_date(),  get_comment_time()) ?>
			<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</p>
		<div class="post-comment-cont">
			<?php comment_text(); ?>
		</div>
	</div>
	<?php
}



// Get Attachment ID from src
function motor_get_attach_id_from_src($image_url) {
	global $wpdb, $table_prefix;

	$res = $wpdb->get_results('select post_id from ' . $table_prefix . 'postmeta where meta_value like "%' . basename($image_url). '%" and meta_key = "_wp_attached_file"');

	if(count($res) == 0)
	{
		$res = $wpdb->get_results('select ID as post_id from ' . $table_prefix . 'posts where guid="' . $image_url . '"');
	}

	return $res[0]->post_id;
}



// Include Header/Footer Custom Styles
if (!function_exists('motor_include_vc_custom_styles')) {
	function motor_include_vc_custom_styles($items) {
		if (!empty($items)) {
			$custom_vc_styles = '';
			foreach ($items as $item) {
				$item_meta = get_post_meta($item, '_wpb_shortcodes_custom_css', true);
				if (!empty($item_meta)) {
					$custom_vc_styles .= $item_meta;
				}
			}
			if (!empty($custom_vc_styles)) {
				$custom_vc_styles = preg_replace(
					array('/:\s*/', '/\s*{\s*/', '/(;)*\s*}\s*/', '/\s+/'),
					array(':', '{', '}', ' '),
					$custom_vc_styles
				);
				wp_add_inline_style('dashicons', $custom_vc_styles);
				wp_enqueue_style('dashicons');
			}
		}
	}
}


// Get Custom Fonts
function motor_set_font_variables($args = array()) {

	if (empty($args['type'])) {
		return false;
	}

	$font = get_theme_mod($args['type'], array());

	// Font Family
	if (empty($font['font-family']) && !empty($args['font-family'])) {
		$font['font-family'] = $args['font-family'];
	}

	if (!empty($font['variant']) && in_array($font['variant'], array('italic', '100italic', '200italic', '300italic', '500italic', '600italic', '700italic', '800italic', '900italic'))) {
		$font['font-style'] = 'italic';
	} elseif (!empty($args['font-style'])) {
		$font['font-style'] = $args['font-style'];
	}

	// Font Weight
	$font['font-weight'] = '';
	if (!empty($font['variant'])) {
		$font['font-weight'] = $font['variant'];
	}
	if (empty($font['font-weight']) && !empty($args['font-weight'])) {
		$font['font-weight'] = $args['font-weight'];
	} elseif ($font['font-weight'] == 'regular') {
		$font['font-weight'] = '400';
	}  elseif ($font['font-weight'] == 'italic') {
		$font['font-weight'] = '400';
	} elseif ($font['font-weight'] == '100italic') {
		$font['font-weight'] = '100';
	} elseif ($font['font-weight'] == '200italic') {
		$font['font-weight'] = '200';
	} elseif ($font['font-weight'] == '300italic') {
		$font['font-weight'] = '300';
	} elseif ($font['font-weight'] == '500italic') {
		$font['font-weight'] = '500';
	} elseif ($font['font-weight'] == '600italic') {
		$font['font-weight'] = '600';
	} elseif ($font['font-weight'] == '700italic') {
		$font['font-weight'] = '700';
	} elseif ($font['font-weight'] == '800italic') {
		$font['font-weight'] = '800';
	} elseif ($font['font-weight'] == '900italic') {
		$font['font-weight'] = '900';
	}

	if (empty($font['font-size']) && !empty($args['font-size'])) {
		$font['font-size'] = $args['font-size'];
	}
	if (empty($font['line-height']) && !empty($args['line-height'])) {
		$font['line-height'] = $args['line-height'];
	}
	if (empty($font['letter-spacing']) && !empty($args['letter-spacing'])) {
		$font['letter-spacing'] = $args['letter-spacing'];
	}
	if (empty($font['text-align']) && !empty($args['text-align'])) {
		$font['text-align'] = $args['text-align'];
	}
	if (empty($font['color']) && !empty($args['color'])) {
		$font['color'] = $args['color'];
	}
	if (empty($font['text-transform']) && !empty($args['text-transform'])) {
		$font['text-transform'] = $args['text-transform'];
	}

	add_less_var( $args['type'], $font['font-family'] );
	add_less_var( $args['type'].'_fw', $font['font-weight'] );
	add_less_var( $args['type'].'_fs', $font['font-style'] );
	add_less_var( $args['type'].'_size', $font['font-size'] );
	add_less_var( $args['type'].'_lh', $font['line-height'] );
	add_less_var( $args['type'].'_ls', $font['letter-spacing'] );
	add_less_var( $args['type'].'_align', $font['text-align'] );
	add_less_var( $args['type'].'_color', $font['color'] );
	add_less_var( $args['type'].'_tt', $font['text-transform'] );

	return true;
}






if (class_exists('Clever_Mega_Menu_Walker')) {

	function motor_megamenu_add_menu_item_class($item_output, $item, $depth, $args, $settings ) {

		$link_classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$link_classes[] = 'menu-item-' . $item->ID;
		$link_classes_str = join(' ', array_filter($link_classes));



		$args = (array)$args;

		$is_mobile = wp_is_mobile();
		$content   = get_item_content($item->ID);
		$settings  = get_item_settings($item->ID);

		if (!is_viewable($settings)) {
			return;
		}

		if ($settings['hide_on_mobile'] && $is_mobile) {
			return;
		}

		if ($settings['hide_on_desktop'] && !$is_mobile) {
			return;
		}

		$indent  = $depth ? str_repeat("\t", $depth) : '';
		$classes = array();
		$class_hide_sub = '';

		if ($args['walker']->has_children) {
			$classes[] = 'menu-item-has-children';
			if ($settings['hide_sub_item_on_mobile']) {
				$classes[] = 'cmm-hide-sub-items';
			}
		}

		$classes[] = 'cmm-item-depth-'.$depth;
		$classes[] = join(' ', get_item_html_classes($settings));

		$html_classes = join(' ', array_filter($classes));
		$html_classes = ( $content && $settings['enable'] ) ? $html_classes.' menu-item-has-children cmm-item-has-content'.$class_hide_sub : $html_classes;
		$html_classes = $html_classes ? ' class="'.esc_attr($html_classes).'"' : '';

		$id = !empty($id) ? ' id="cmm-item-'.$item->ID.'"' : '';

		$item_config = array();
		$item_config['width'] = $settings['width'];
		$item_config['layout'] = $settings['layout'];

		$output = '';
		$li_attr = " data-settings='".esc_attr(json_encode($item_config))."'";
		$output .= $indent.'<li'.$id.$html_classes.$li_attr.'>';

		$atts = array();
		$atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
		$atts['target'] = !empty($item->target)     ? $item->target     : '';
		$atts['rel']    = !empty($item->xfn)        ? $item->xfn        : '';
		$atts['href']   = !empty($item->url)        ? $item->url        : '';

		if ($settings['disable_link']) {
			unset($atts['href']);
		}

		$attributes = '';

		if (!isset($atts['class'])) {
			$atts['class'] = '';
		}

		foreach ($atts as $attr => $value) {
			if ($attr === 'class') {
				if (!empty($value)) {
					$value .= ' cmm-nav-link';
				} else {
					$value .= 'cmm-nav-link';
				}


				$value .= ' '.$link_classes_str;


			}
			if (!empty($value)) {
				$value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
				$attributes .= ' '.$attr.'="'.$value.'"';
			}
		}

		$item_output = isset($args['before']) ? $args['before'] : '';
		$item_output .= $settings['disable_link'] ? '<span'. $attributes .'>' : '<a'. $attributes . '>';

		$item_output .= get_font_icon($settings);

		if (!$settings['hide_title']) {
			$item_output .='<span class="cmm-item-label">';
			$item_output .= $item->title;
			$item_output .='</span>';
		}

		$item_output .= $settings['disable_link'] ? '</span>' : '</a>';
		$item_output .= isset($args['after']) ? $args['after'] : '';

		if ($content && $settings['enable']) {
			$depth = 1;
			$item_output .= '<div class="cmm-content-container">';
			$item_output .= '<div class="cmm-content-wrapper">';
			$item_output .= do_shortcode($content);
			$item_output .= '</div>';
			$item_output .= '</div>';
		}

		return $item_output;
	}
	add_filter( 'clever_walker_nav_menu_start_el', 'motor_megamenu_add_menu_item_class', 9999, 5 );



	function get_font_icon(array $settings)
	{
		$icon = empty($settings['icon']) ? '' : trim($settings['icon']);

		if ($icon) {
			$icon = '<span class="cmm-icon"><i class="'.esc_attr($icon).'"></i></span>';
		}

		return $icon;
	}
	function get_item_html_classes(array $settings, array $classes = array())
	{
		if ($settings['enable']) {
			$classes[] = 'cmm-mega';
		}

		if ($settings['icon']) {
			$classes[] = 'cmm-has-icon';
		}

		if ($settings['hide_title']) {
			$classes[] = 'cmm-hide-title';
		}

		if ($settings['layout']) {
			$classes[] = 'cmm-layout-'.$settings['layout'];
		}

		return $classes;
	}
	function get_item_settings($item_id)
	{
		$settings    = (array)get_post_meta($item_id, Clever_Mega_Menu_Item_Meta::SETTINGS_META_KEY, true);
		$item_roles  = array('role_anyone' => 1);
		$valid_roles = wp_roles()->roles;

		foreach ($valid_roles as $role => $data) {
			$fake_role = 'role_'.$role;
			$item_roles[$fake_role] = '0';
		}

		$default_settings = Clever_Mega_Menu_Item_Meta::$fields + $item_roles;

		$settings = array_merge($default_settings, $settings);

		return $settings;
	}
	function get_item_content($item_id)
	{
		$content = get_post_meta($item_id, Clever_Mega_Menu_Item_Meta::CONTENT_META_KEY, true);

		return trim($content);
	}
	function is_viewable($item_settings)
	{
		$valid_roles = array();
		$roles = wp_roles()->roles;

		foreach ($roles as $role => $data) {
			$fake_role = 'role_'.$role;
			if (isset($item_settings[$fake_role]) && $item_settings[$fake_role]) {
				$valid_roles[] = $role;
			}
		}

		$user = wp_get_current_user();

		if ($user->exists()) {
			$user_minimal_role = $user->roles[0];
		} else {
			$user_minimal_role = 'role_anyone';
		}

		if (in_array($user_minimal_role, $valid_roles) || $item_settings['role_anyone']) {
			return true;
		}

		return false;
	}

}