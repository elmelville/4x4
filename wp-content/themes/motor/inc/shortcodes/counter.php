<?php
add_action( 'vc_before_init', 'motor_counter_integrate_vc' );
function motor_counter_integrate_vc () {
	vc_map( array(
		'name' => esc_html__('Counter', 'motor'),
		'base' => 'motor_counter',
		'class' => '',
		'category' => esc_html__( 'Motor', 'motor' ),
		'icon' => get_template_directory_uri() . "/img/vc_motor.png",
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Title', 'motor'),
				'param_name' => 'title',
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__('Value', 'motor'),
				'param_name' => 'value',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Title Color', 'motor'),
				'param_name' => 'title_color',
			),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__('Value Color', 'motor'),
				'param_name' => 'value_color',
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

class WPBakeryShortCode_motor_counter extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {

		$css = '';
		extract( shortcode_atts( array (
			'title' => '',
			'value' => '',
			'title_color' => '',
			'value_color' => '',
			'css' => ''
		), $atts ) );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

		wp_enqueue_script( 'waypoints' );

		wp_enqueue_script( 'motor_counter', get_template_directory_uri( ).'/js/jquery.countTo.js' , array( 'jquery' ), false, true );

		ob_start();
		?>
		<div class="counter-i<?php echo esc_attr( $css_class ); ?>">
			<?php $value = intval($value); ?>
			<p data-value="<?php echo esc_attr($value); ?>" class="counter-i-val"<?php if (!empty($value_color)) echo ' style="color: '.esc_attr($value_color).'"'; ?>><span><?php echo esc_attr($value); ?></span></p>
			<?php if (!empty($title)) : ?>
				<p class="counter-i-ttl"<?php if (!empty($title_color)) echo ' style="color: '.esc_attr($title_color).'"'; ?>><?php echo esc_attr($title); ?></p>
			<?php endif; ?>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}