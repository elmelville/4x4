<?php
add_action( 'vc_before_init', 'motor_promobox_integrate_vc' );
function motor_promobox_integrate_vc () {
	vc_map( array(
		'name' => esc_html__('Promo Box', 'motor'),
		'base' => 'motor_promobox',
		'category' => esc_html__( 'Motor', 'motor' ),
		'icon' => get_template_directory_uri() . "/img/vc_motor.png",
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Title', 'motor'),
				'param_name' => 'title',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Image or Icon', 'motor' ),
				'value' => array(
					esc_html__( 'Image', 'motor' ) => 'image',
					esc_html__( 'Icon', 'motor' ) => 'icon',
				),
				'param_name' => 'image_or_icon',
			),
			array(
				'type' => 'attach_image',
				'heading' => esc_html__('Image', 'motor'),
				'param_name' => 'image',
				'dependency' => array(
					'element' => 'image_or_icon',
					'value' => 'image',
				),
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
				),
				'admin_label' => true,
				'param_name' => 'type',
				'description' => esc_html__( 'Select icon library.', 'motor' ),
				'dependency' => array(
					'element' => 'image_or_icon',
					'value' => 'icon',
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
				'type' => 'vc_link',
				'heading' => esc_html__('Link', 'motor'),
				'param_name' => 'link',
			),
			array(
				'type' => 'textarea_html',
				'heading' => esc_html__('Content', 'motor'),
				'param_name' => 'content',
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

class WPBakeryShortCode_motor_promobox extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {

		$css = '';
		extract( shortcode_atts( array (
			'title' => '',
			'image_or_icon' => 'image',
			'image' => '',
			'icon_fontawesome' => '',
			'icon_openiconic' => '',
			'icon_typicons' => '',
			'icon_entypo' => '',
			'icon_linecons' => '',
			'icon_monosocial' => '',
			'link' => '',
			'css' => ''
		), $atts ) );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

		ob_start();
		if (!empty($link)) {
			$link = vc_build_link($link);
		}
		?>
		<div class="promobox-i<?php echo esc_attr( $css_class ); if ((!empty($image) && $image_or_icon == 'image') || ($image_or_icon == 'icon')) echo ' promobox-i-hasimg'; if (!empty($link) && !empty($link['title']))  echo ' promobox-i-hasbtn'; ?>">
			<?php
			if ($image_or_icon == 'icon') {
				echo '<p class="promobox-i-icon">';
				if (!empty($icon_fontawesome)) {
					echo '<i class="' . esc_attr($icon_fontawesome) . '"></i>';
				} elseif (!empty($icon_openiconic)) {
					echo '<i class="' . esc_attr($icon_openiconic) . '"></i>';
				} elseif (!empty($icon_typicons)) {
					echo '<i class="' . esc_attr($icon_typicons) . '"></i>';
				} elseif (!empty($icon_entypo)) {
					echo '<i class="' . esc_attr($icon_entypo) . '"></i>';
				} elseif (!empty($icon_linecons)) {
					echo '<i class="' . esc_attr($icon_linecons) . '"></i>';
				} elseif (!empty($icon_monosocial)) {
					echo '<i class="' . esc_attr($icon_monosocial) . '"></i>';
				}
				echo '</p>';
			} else {
				if (!empty($image)) {
					$image_src = wp_get_attachment_image_src($image);
					echo '<p class="promobox-i-img"><img src="'.esc_attr($image_src[0]).'" alt="'.esc_attr($title).'"></p>';
				}
			}
			if (!empty($title)) {
				echo "<h3>".esc_attr($title)."</h3>";
			}
			if (!empty($content)) {
				echo wpautop($content);
			}
			if (!empty($link) && !empty($link['title'])) {
				?>
				<a class="promobox-i-link" href="<?php echo esc_url($link['url']); ?>"<?php if (!empty($link['target'])) echo ' target="'.esc_attr($link['target']).'"'; ?>><?php echo esc_attr($link['title']); ?></a>
				<?php
			}
			?>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}