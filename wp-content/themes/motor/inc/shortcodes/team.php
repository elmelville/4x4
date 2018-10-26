<?php
add_action( 'vc_before_init', 'motor_team_integrate_vc' );
function motor_team_integrate_vc () {
	vc_map( array(
		'name' => esc_html__('Team', 'motor'),
		'base' => 'motor_team',
		'class' => '',
		'icon' => get_template_directory_uri() . "/img/vc_motor.png",
		'category' => esc_html__( 'Motor', 'motor' ),
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Title', 'motor'),
				'param_name' => 'title',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Position', 'motor'),
				'param_name' => 'position',
			),
			array(
				'type' => 'attach_image',
				'heading' => esc_html__('Image', 'motor'),
				'param_name' => 'image',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__('Style', 'motor'),
				'param_name' => 'style',
				'value' => array(
					esc_html__('Style 1', 'motor') => 'style_1',
					esc_html__('Style 2', 'motor') => 'style_2',
					esc_html__('Style 3', 'motor') => 'style_3'
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

class WPBakeryShortCode_motor_team extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {

		$css = '';
		extract( shortcode_atts( array (
			'title' => '',
			'position' => '',
			'image' => '',
			'style' => '',
			'css' => ''
		), $atts ) );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

		ob_start();
		?>
		<div class="team-i<?php if (!empty($style)) echo ' '.esc_attr($style); ?><?php echo esc_attr( $css_class ); ?>">
			<?php
			if (!empty($image)) {
				$image_src = wp_get_attachment_image_src($image, 'motor_200x200');
				echo '<p class="team-i-img"><img src="'.esc_attr($image_src[0]).'" alt="'.esc_attr($title).'"></p>';
			}
			if (!empty($title)) {
				echo "<h3>".wp_kses_post($title)."</h3>";
			}
			if (!empty($position)) {
				echo '<p class="team-i-position">'.esc_attr($position).'</p>';
			}
			?>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}