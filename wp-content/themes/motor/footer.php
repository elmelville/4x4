<?php
global $motor_options;
?>

</div><?php /* #content */ ?>

<?php
if (!empty($motor_options['footer_template'])) {
	$footer_template = $motor_options['footer_template'];
	if (function_exists('icl_object_id')) {
		$footer_template = icl_object_id($motor_options['footer_template'], 'page', false, ICL_LANGUAGE_CODE);
	}
	$content = get_post_field('post_content', $footer_template);
	if (!empty($content)) {
		echo '<footer class="blog-sb-widgets page-styling site-footer">'.do_shortcode( $content ).'</footer>';
	}
}
?>

<?php
// Modal Form
if (!empty($motor_options['other_modalform'])) : ?>
	<div id="modal-form" class="modal-form">
		<?php echo do_shortcode($motor_options['other_modalform']); ?>
	</div>
<?php endif; ?>

<?php
// Modal Form - Request Product
if ($motor_options['catalog_request'] == 'yes') : ?>
	<div id="request-form" class="modal-form">
		<?php
		if (!empty($motor_options['catalog_requestform'])) {
			echo do_shortcode($motor_options['catalog_requestform']);
		} else {
			esc_html_e('Please set a form', 'motor');
		}
		?>
	</div>
<?php endif; ?>

</div><?php /* #page */ ?>

<?php wp_footer(); ?>

</body>
</html>