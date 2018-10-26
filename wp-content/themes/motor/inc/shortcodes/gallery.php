<?php
add_action( 'vc_before_init', 'motor_gallery_integrate_vc' );
function motor_gallery_integrate_vc () {
	vc_map( array(
		'name' => esc_html__( 'Gallery', 'motor' ),
		'base' => 'motor_gallery',
		'icon' => get_template_directory_uri() . "/img/vc_motor.png",
		'category' => esc_html__( 'Motor', 'motor' ),
		'params' => array(
			array(
				'type' => 'autocomplete',
				'heading' => esc_html__( 'Products', 'motor' ),
				'param_name' => 'ids',
				'settings' => array(
					'multiple' => true,
					'sortable' => true,
					'unique_values' => true,
					// In UI show results except selected. NB! You should manually check values in backend
				),
				'save_always' => true,
				'description' => esc_html__( 'Enter List of Products', 'motor' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order by', 'motor' ),
				'param_name' => 'orderby',
				'value' => array(
					'',
					esc_html__( 'Date', 'motor' ) => 'date',
					esc_html__( 'ID', 'motor' ) => 'ID',
					esc_html__( 'Author', 'motor' ) => 'author',
					esc_html__( 'Title', 'motor' ) => 'title',
					esc_html__( 'Modified', 'motor' ) => 'modified',
					esc_html__( 'Random', 'motor' ) => 'rand',
					esc_html__( 'Comment count', 'motor' ) => 'comment_count',
					esc_html__( 'Menu order', 'motor' ) => 'menu_order',
				),
				'std' => 'title',
				'save_always' => true,
				'description' => sprintf( esc_html__( 'Select how to sort retrieved products. More at %s. Default by Title', 'motor' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Sort order', 'motor' ),
				'param_name' => 'order',
				'value' => array(
					'',
					esc_html__( 'Descending', 'motor' ) => 'DESC',
					esc_html__( 'Ascending', 'motor' ) => 'ASC',
				),
				'save_always' => true,
				'description' => sprintf( esc_html__( 'Designates the ascending or descending order. More at %s. Default by ASC', 'motor' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex page</a>' ),
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Items per page', 'motor' ),
				'param_name' => 'items_per_page',
				'description' => esc_html__( 'Number of items to show per page. Enter -1 to display all', 'motor' ),
				'value' => '12',
			),
			array(
				'type' => 'checkbox',
				'heading' => esc_html__( 'Show filter', 'motor' ),
				'param_name' => 'show_filter',
				'value' => array( esc_html__( 'Yes', 'motor' ) => 'yes' ),
				'description' => esc_html__( 'Append filter to grid.', 'motor' ),
			),
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'Css', 'motor' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design options', 'motor' ),
			),
		),
	) );
}




class WPBakeryShortCode_motor_gallery extends WPBakeryShortCode {
	protected function content( $atts, $content = null ) {

		$css = '';
		extract( shortcode_atts( array (
			'orderby' => 'date',
			'order' => 'DESC',
			'items_per_page' => 9,
			'show_filter' => 'yes',
			'css' => ''
		), $atts ) );

		$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

		ob_start();
		?>

		<?php
		$gallery_sections = get_categories( array(
			'parent'       => 0,
			'orderby'      => 'ID',
			'taxonomy'     => 'gallery_category',
			'hide_empty' => true
		) );

		if (!empty($gallery_sections) && $show_filter == 'yes') : ?>
			<ul class="cont-sections motor-gallery-sections">
				<li class="active">
					<a data-section="*" href="#"><?php esc_html_e('All', 'motor'); ?></a>
				</li>
				<?php foreach ($gallery_sections as $key=>$gallery_section) : ?>
					<li>
						<a data-section=".gallery-<?php echo esc_attr($gallery_section->slug); ?>" href="#"><?php echo esc_attr($gallery_section->name); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php include( trailingslashit( get_template_directory() ) . 'inc/shortcodes/gallery-content.php' ); ?>

		<?php
		$output = ob_get_clean();

		return $output;
	}
}
