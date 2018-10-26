<?php
add_action( 'vc_before_init', 'motor_infoblock_integrate_vc' );

function motor_infoblock_integrate_vc () {

	$cf7 = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );

	$contact_forms = array();
	$contact_forms['none'] = 0;
	if ( $cf7 ) {
		foreach ( $cf7 as $cform ) {
			$contact_forms[ $cform->post_title ] = $cform->ID;
		}
	} else {
		$contact_forms[ esc_html__( 'No contact forms found', 'motor' ) ] = 0;
	}

	vc_map( array(
		'name' => esc_html__('Info Block', 'motor'),
		'base' => 'motor_infoblock',
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
				'heading' => esc_html__('Subtitle', 'motor'),
				'param_name' => 'subtitle',
			),
			array(
				'type' => 'attach_image',
				'heading' => esc_html__('Image', 'motor'),
				'param_name' => 'image',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__('Image Align', 'motor'),
				'param_name' => 'image_align',
				'value' => array(
					esc_html__('Left', 'motor') => 'left',
					esc_html__('Right', 'motor') => 'right',
				),
			),
			array(
				'type' => 'vc_link',
				'heading' => esc_html__('Link', 'motor'),
				'param_name' => 'link',
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Select contact form', 'motor' ),
				'param_name' => 'form_id',
				'value' => $contact_forms,
				'save_always' => true,
				'description' => esc_html__( 'Choose previously created contact form from the drop down list.', 'motor' ),
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

class WPBakeryShortCode_motor_infoblock extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {

		$css = '';
		extract( shortcode_atts( array (
			'title' => '',
			'subtitle' => '',
			'image' => '',
			'image_align' => 'left',
			'link' => '',
			'form_id' => '',
			'css' => ''
		), $atts ) );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

		ob_start();
		if (!empty($link)) {
			$link = vc_build_link($link);
		}
		$image_src = wp_get_attachment_image_src($image, 'full');
		?>
		<div class="getspec<?php if ($image_align == 'right') echo ' getspec-right'; if (empty($image_src[0])) echo ' getspec-noimg'; ?><?php echo esc_attr( $css_class ); ?>">
			<div class="getspec-cont">
				<?php
				if (!empty($title)) {
					echo "<h3>".esc_attr($title)."</h3>";
				}
				if (!empty($subtitle)) {
					echo "<p>".esc_attr($subtitle)."</p>";
				}
				?>
				<?php if (!empty($form_id) || $form_id !== '0') : ?>
					<?php echo do_shortcode('[contact-form-7 id="'.$form_id.'"]'); ?>
				<?php elseif (!empty($link) && !empty($link['title'])) : ?>
					<a class="getspec-more" href="<?php echo esc_url($link['url']); ?>"<?php if (!empty($link['target'])) echo ' target="'.esc_attr($link['target']).'"'; ?>><?php echo esc_attr($link['title']); ?></a>
				<?php endif; ?>
			</div>
			<?php
			if (!empty($image_src[0])) :
				if (!empty($link) && !empty($link['title'])) :
					?>
					<a href="<?php echo esc_url($link['url']); ?>"<?php if (!empty($link['target'])) echo ' target="'.esc_attr($link['target']).'"'; ?> class="getspec-img">
				<?php else : ?>
					<div class="getspec-img">
				<?php endif; ?>
				
					<img src="<?php echo esc_attr($image_src[0]); ?>" alt="<?php echo esc_attr($title); ?>">

				<?php if (!empty($link) && !empty($link['title'])) : ?>
					</a>
				<?php else: ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php
		$output = ob_get_clean();

		return $output;
	}
}