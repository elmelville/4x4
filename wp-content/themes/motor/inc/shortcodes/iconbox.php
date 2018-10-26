<?php
add_action( 'vc_before_init', 'motor_iconbox_integrate_vc' );
function motor_iconbox_integrate_vc () {
	vc_map( array(
		'name' => esc_html__('Iconbox', 'motor'),
		'base' => 'motor_iconbox',
		'class' => '',
		'category' => esc_html__( 'Motor', 'motor' ),
		'icon' => get_template_directory_uri() . "/img/vc_motor.png",
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__('Styles', 'motor'),
				'param_name' => 'styles',
				'value' => array(
					esc_html__('Style 1', 'motor') => 'style_1',
					esc_html__('Style 2', 'motor') => 'style_2',
					esc_html__('Style 3', 'motor') => 'style_3',
					esc_html__('Style 4', 'motor') => 'style_4'
				),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Title', 'motor'),
				'param_name' => 'title',
			),
			array(
				'type' => 'textarea_html',
				'heading' => esc_html__('Content', 'motor'),
				'param_name' => 'content',
			),
			array(
				'type' => 'vc_link',
				'heading' => esc_html__('Link', 'motor'),
				'param_name' => 'link',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Icon library', 'motor' ),
				'value' => array(
					esc_html__( 'Font Awesome', 'motor' ) => 'fontawesome',
					esc_html__( 'Open Iconic', 'motor' ) => 'openiconic',
					esc_html__( 'Typicons', 'motor' ) => 'typicons',
					esc_html__( 'Entypo', 'motor' ) => 'entypo',
					esc_html__( 'Linecons', 'motor' ) => 'linecons',
					esc_html__( 'Mono Social', 'motor' ) => 'monosocial',
					esc_html__( 'Image', 'motor' ) => 'image',
				),
				'admin_label' => true,
				'param_name' => 'type',
				'description' => esc_html__( 'Select icon library.', 'motor' ),
			),
			array(
				'type' => 'attach_image',
				'heading' => esc_html__('Image', 'motor'),
				'param_name' => 'image',
				'dependency' => array(
					'element' => 'type',
					'value' => 'image',
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'motor' ),
				'param_name' => 'icon_fontawesome',
				'value' => 'fa fa-adjust', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false,
					// default true, display an "EMPTY" icon?
					'iconsPerPage' => 4000,
					// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'fontawesome',
				),
				'description' => esc_html__( 'Select icon from library.', 'motor' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'motor' ),
				'param_name' => 'icon_openiconic',
				'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'openiconic',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'openiconic',
				),
				'description' => esc_html__( 'Select icon from library.', 'motor' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'motor' ),
				'param_name' => 'icon_typicons',
				'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'typicons',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'typicons',
				),
				'description' => esc_html__( 'Select icon from library.', 'motor' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'motor' ),
				'param_name' => 'icon_entypo',
				'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'entypo',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'entypo',
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'motor' ),
				'param_name' => 'icon_linecons',
				'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'linecons',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'linecons',
				),
				'description' => esc_html__( 'Select icon from library.', 'motor' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'motor' ),
				'param_name' => 'icon_monosocial',
				'value' => 'vc-mono vc-mono-fivehundredpx', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'monosocial',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'type',
					'value' => 'monosocial',
				),
				'description' => esc_html__( 'Select icon from library.', 'motor' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Title Font Size', 'motor'),
				'description' => esc_html__('Number in px', 'motor'),
				'param_name' => 'title_font_size',
				'group' => esc_html__( 'Customize', 'motor' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Icon Font Size', 'motor'),
				'description' => esc_html__('Number in px', 'motor'),
				'param_name' => 'icon_font_size',
				'group' => esc_html__( 'Customize', 'motor' ),
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Title Font Color', 'motor'),
				'param_name' => 'title_color',
				'group' => esc_html__( 'Customize', 'motor' ),
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icon Font Color', 'motor'),
				'param_name' => 'icon_color',
				'group' => esc_html__( 'Customize', 'motor' ),
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Icon Background Color', 'motor'),
				'param_name' => 'icon_bg',
				'group' => esc_html__( 'Customize', 'motor' ),
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

class WPBakeryShortCode_motor_iconbox extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {

		$css = '';
		extract( shortcode_atts( array (
			'styles' => '',
			'title' => '',
			'link' => '',
			'type' => '',
			'image' => '',
			'icon_fontawesome' => '',
			'icon_openiconic' => '',
			'icon_typicons' => '',
			'icon_entypo' => '',
			'icon_linecons' => '',
			'icon_monosocial' => '',

			'title_font_size' => '',
			'icon_font_size' => '',
			'title_color' => '',
			'icon_color' => '',
			'icon_bg' => '',

			'css' => ''
		), $atts ) );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
		
		// Enqueue needed icon font.
		if (empty($image)) {
			vc_icon_element_fonts_enqueue($type);
		}

		$style_title = '';
		if (!empty($title_font_size) || !empty($title_color)) {
			$style_title .= ' style="';
			if (!empty($title_font_size)) {
				$style_title .= 'font-size: '.$title_font_size.'px;';
			}
			if (!empty($title_color)) {
				$style_title .= 'color: '.$title_color.';';
			}
			$style_title .= '"';
		}
		$style_icon = '';
		if (!empty($icon_font_size) || !empty($icon_color) || !empty($icon_bg)) {
			$style_icon .= ' style="';
			if (!empty($icon_font_size)) {
				$style_icon .= 'font-size: '.$icon_font_size.'px;';
			}
			if (!empty($icon_color)) {
				$style_icon .= 'color: '.$icon_color.';';
			}
			if (!empty($icon_bg)) {
				$style_icon .= 'background-color: '.$icon_bg.';';
			}
			$style_icon .= '"';
		}

		ob_start();
		if (!empty($link)) {
			$link = vc_build_link($link);
		}
		?>
		<div class="iconbox-i<?php if ($styles == 'style_2') echo '-2'; elseif ($styles == 'style_3') echo '-3';  elseif ($styles == 'style_4') echo '-4'; ?><?php echo esc_attr( $css_class ); ?>">
			<?php if (!empty($link)) { ?>
			<a<?php echo $style_icon; ?> class="iconbox-i-img" href="<?php echo esc_url($link['url']); ?>"<?php if (!empty($link['target'])) echo ' target="'.esc_attr($link['target']).'"'; ?>>
			<?php } else { ?>
			<p<?php echo $style_icon; ?> class="iconbox-i-img">
			<?php } ?>
				<?php
				if (!empty($image)) {
					$image_src = wp_get_attachment_image_src($image, 'multishop_blog_slider');
					echo '<img src="'.esc_attr($image_src[0]).'" alt="'.esc_attr($title).'">';
				} elseif (!empty($icon_fontawesome)) {
					echo '<i class="'.esc_attr($icon_fontawesome).'"></i>';
				} elseif (!empty($icon_openiconic)) {
					echo '<i class="'.esc_attr($icon_openiconic).'"></i>';
				} elseif (!empty($icon_typicons)) {
					echo '<i class="'.esc_attr($icon_typicons).'"></i>';
				} elseif (!empty($icon_entypo)) {
					echo '<i class="'.esc_attr($icon_entypo).'"></i>';
				} elseif (!empty($icon_linecons)) {
					echo '<i class="'.esc_attr($icon_linecons).'"></i>';
				} elseif (!empty($icon_monosocial)) {
					echo '<i class="'.esc_attr($icon_monosocial).'"></i>';
				}
				?>
			<?php if (!empty($link)) { ?>
			</a>
			<?php } else { ?>
			</p>
			<?php } ?>

			
			<?php if ($styles == 'style_4') echo '<div class="iconbox-i-cont">'; ?>
			
			
			<?php if (!empty($title)) : ?>
				<h3<?php echo $style_title; ?>><?php
					if (!empty($link)) {
						?><a href="<?php echo esc_url($link['url']); ?>"<?php if (!empty($link['target'])) echo ' target="'.esc_attr($link['target']).'"'; ?>><?php
					} 
					echo esc_attr($title);
					if (!empty($link)) {
						?></a><?php
					}
					?></h3>
			<?php endif; ?>
			<?php if (!empty($content)) : ?>
				<?php echo wpautop($content); ?>
			<?php endif; ?>


			<?php if ($styles == 'style_4') echo '</div>'; ?>
			
			
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}