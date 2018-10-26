<?php

// Page Width: Normal Width, Full Width
add_action( 'add_meta_boxes', 'motor_meta_box_page_add' );
function motor_meta_box_page_add()
{
    add_meta_box( 'motor-meta-box-page', 'Page Width', 'motor_meta_box_page_fields', 'page', 'side', 'default' );
}
function motor_meta_box_page_fields()
{
    global $post;
    $values = get_post_custom( $post->ID );
    $width = isset( $values['my_meta_box_page'] ) ? esc_attr( $values['my_meta_box_page'][0] ) : '';

    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>
    <p>
        <input type="radio" id="my_meta_box_page_normal" name="my_meta_box_page" value="normal"<?php if (!$width || $width == 'normal') echo ' checked'; ?>>
        <label for="my_meta_box_page_normal"><?php echo esc_html__('Normal', 'motor'); ?></label>
    </p>
    <p>
        <input type="radio" id="my_meta_box_page_full" name="my_meta_box_page" value="full"<?php if ($width == 'full') echo ' checked'; ?>>
        <label for="my_meta_box_page_full"><?php echo esc_html__('Full Width', 'motor'); ?></label>
    </p>
    <?php
}

add_action( 'save_post', 'motor_meta_box_page_save' );
function motor_meta_box_page_save( $post_id )
{
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    $width = isset( $_POST['my_meta_box_page'] ) ? $_POST['my_meta_box_page'] : 'normal';
    update_post_meta( $post_id, 'my_meta_box_page', $width );
}



// Show Page Title and Breadcrumbs
add_action( 'add_meta_boxes', 'motor_meta_box_page_add_2' );
function motor_meta_box_page_add_2()
{
    add_meta_box( 'motor-page-ttl-bcrumbs', 'Title and Breadcrumbs', 'motor_meta_box_page_fields_2', 'page', 'side', 'default' );
}
function motor_meta_box_page_fields_2()
{
    global $post;
    $values = get_post_custom( $post->ID );
    $page_ttl_bcrumbs = isset( $values['page_ttl_bcrumbs'] ) ? esc_attr( $values['page_ttl_bcrumbs'][0] ) : '';

    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>
    <p>
        <input type="radio" id="page_ttl_bcrumbs_show_both" name="page_ttl_bcrumbs" value="show_both"<?php if (!$page_ttl_bcrumbs || $page_ttl_bcrumbs == 'show_both') echo ' checked'; ?>>
        <label for="page_ttl_bcrumbs_show_both"><?php echo esc_html__('Show Both', 'motor'); ?></label>
    </p>
    <p>
        <input type="radio" id="page_ttl_bcrumbs_only_ttl" name="page_ttl_bcrumbs" value="only_ttl"<?php if ($page_ttl_bcrumbs == 'only_ttl') echo ' checked'; ?>>
        <label for="page_ttl_bcrumbs_only_ttl"><?php echo esc_html__('Show Only Title', 'motor'); ?></label>
    </p>
    <p>
        <input type="radio" id="page_ttl_bcrumbs_only_bcrumbs" name="page_ttl_bcrumbs" value="only_bcrumbs"<?php if ($page_ttl_bcrumbs == 'only_bcrumbs') echo ' checked'; ?>>
        <label for="page_ttl_bcrumbs_only_bcrumbs"><?php echo esc_html__('Show Only Breadcrumbs', 'motor'); ?></label>
    </p>
    <p>
        <input type="radio" id="page_ttl_bcrumbs_hide_both" name="page_ttl_bcrumbs" value="hide_both"<?php if ($page_ttl_bcrumbs == 'hide_both') echo ' checked'; ?>>
        <label for="page_ttl_bcrumbs_hide_both"><?php echo esc_html__('Hide Both', 'motor'); ?></label>
    </p>
    <?php
}

add_action( 'save_post', 'motor_meta_box_page_save_2' );
function motor_meta_box_page_save_2( $post_id )
{
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

	$page_ttl_bcrumbs = isset( $_POST['page_ttl_bcrumbs'] ) ? $_POST['page_ttl_bcrumbs'] : 'show_both';
    update_post_meta( $post_id, 'page_ttl_bcrumbs', $page_ttl_bcrumbs );
}













/*
 * POST FIELDS - start
 */

// Image & Repeater Scripts
add_action( 'admin_enqueue_scripts', 'motor_scripts_method' );
function motor_scripts_method(){
    wp_enqueue_style( 'motor-admin-custom', get_template_directory_uri(). '/css/admin-custom.css' );
    wp_enqueue_script( 'jquery-ui-sortable');
    wp_enqueue_script( 'jquery-ui-datepicker');
    wp_enqueue_script('motor-admin-custom-fields', get_template_directory_uri().'/js/admin-js.js');
}

// Post Fields
$prefix_post = 'motor_post_';
$motor_post_meta_fields = array(
    array(
        'label'=> esc_html__('Video Link', 'motor'),
        'desc'  => esc_html__('Youtube / Vimeo video link', 'motor'),
        'id'    => $prefix_post.'text',
        'type'  => 'text'
    ),
    array(
        'label' => esc_html__('Slider Images', 'motor'),
        'id'    => $prefix_post.'repeatable_img',
        'type'  => 'repeatable_img'
    ),
);

// Add Meta Box
function motor_post_add_custom_meta_box() {
    add_meta_box(
        'motor_post_fields', // ID
        esc_html__('Post Fields', 'motor'), // title
        'motor_show_post_fields', // callback
        'post', // post type
        'normal', // context
        'default' // priority
    );
}
add_action('add_meta_boxes', 'motor_post_add_custom_meta_box');

// Show Fields
function motor_show_post_fields() {
    global $motor_post_meta_fields, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'">';

    // Begin the field table and loop
    echo '<table class="form-table motor-form-table">';
    foreach ($motor_post_meta_fields as $field) {

        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);

        // begin a table row with
        echo '<tr>';
        echo '<th><label for="'.esc_html($field['id']).'">'.esc_html($field['label']).'</label></th>';
        echo '<td>';
        switch($field['type']) {

            // text
            case 'text':
                echo '<input type="text" name="'.esc_html($field['id']).'" id="'.esc_html($field['id']).'" value="'.esc_html($meta).'" size="30" /><span class="description">'.esc_html($field['desc']).'</span>';
                break;

            // Repeatable Text Image
            case 'repeatable_img':
                echo '<a class="repeatable-add button" href="#">Add Slide</a>';
                echo '<ul id="'.esc_html($field['id']).'-repeatable" class="custom_repeatable">';
                $i = 0;
                if ($meta) {
                    foreach($meta as $row) {
                        echo '<li>';
                        $image = '';
                        echo '<span class="custom_default_image" style="display:none">'.esc_html($image).'</span>';
                        if ($row) { $image = wp_get_attachment_image_src($row, 'medium'); $image = $image[0]; }
                        echo '<input name="'.esc_html($field['id']).'['.esc_html($i).']" type="hidden" class="custom_field_r custom_upload_image" value="'.esc_html($row).'">
                        <img src="'.esc_url($image).'" class="custom_preview_image" alt="" /><br>
                        <span class="sort hndle">|||</span>
                        <input class="custom_upload_image_button button" type="button" value="Choose Image" />
                        <a href="#" class="custom_clear_image_button">Remove Image</a>
                        <a class="repeatable-remove button" href="#">Remove Slide</a>
                        <br>';
                        echo '</li>';
                        $i++;
                    }
                } else {
                    echo '<li>';
                    $image = '';
                    echo '<span class="custom_default_image" style="display:none">'.esc_html($image).'</span>';
                    echo '<input name="'.esc_html($field['id']).'['.esc_html($i).']" type="hidden" class="custom_field_r custom_upload_image" value="" />
                    <img src="'.esc_url($image).'" class="custom_preview_image" alt="" /><br>
                    <span class="sort hndle">|||</span>
                    <input class="custom_upload_image_button button" type="button" value="Choose Image" />
                    <a href="#" class="custom_clear_image_button">Remove Image</a>
                    <a class="repeatable-remove button" href="#">Remove Slide</a>
                    <br>';

                    echo '</li>';
                }
                echo '</ul>';
                break;

        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// Save the Data
function motor_save_custom_meta($post_id) {
    global $motor_post_meta_fields;

    // verify nonce
    if (empty($_POST['custom_meta_box_nonce']) || !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // loop through fields and save the data
    foreach ($motor_post_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
add_action('save_post', 'motor_save_custom_meta');

/*
 * POST FIELDS - end
 */


/*
 * PRODUCT FIELDS - start
 */

// Product Fields
$prefix_product = 'motor_product_';
$motor_product_meta_fields = array(
    array (
        'label' => 'Default Editor in Bottom',
        'desc'  => 'Showing Visual Composer Elements after Tabs and using Custom Field as the Text Description',
        'id'    => $prefix_product.'editor',
        'type'  => 'radio',
        'options' => array (
            'default' => array (
                'label' => esc_html__('No', 'motor'),
                'value' => 'default'
            ),
            'custom' => array (
                'label' => esc_html__('Yes', 'motor'),
                'value' => 'custom'
            ),
        )
    ),
    array(
        'label' => esc_html__('Product Description', 'motor'),
        'desc'  => esc_html__('Please use this text editor as product description tab. Default editor you can use for adding Visual Composer Elements', 'motor'),
        'id'    => $prefix_product.'description',
        'type'  => 'wp_editor'
    ),
);

// Add Meta Box
function motor_product_add_custom_meta_box() {
    add_meta_box(
        'motor_product_fields', // ID
        esc_html__('Product Fields', 'motor'), // title
        'motor_show_product_fields', // callback
        'product', // post type
        'normal', // context
        'default' // priority
    );
}
add_action('add_meta_boxes', 'motor_product_add_custom_meta_box');

// Show Fields
function motor_show_product_fields() {
    global $motor_product_meta_fields, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'">';

    // Begin the field table and loop
    echo '<table class="form-table motor-form-table">';
    foreach ($motor_product_meta_fields as $field) {

        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);

        if ($field['type'] == 'radio') {
            $editor = $meta;
        }
        if ($field['type'] == 'wp_editor') {
            $editor_display = ((!empty($editor) && $editor == 'custom') ? '' : ' style="display: none;"');
        }

        // begin a table row with
        echo '<tr'.$editor_display.' class="motor-product-'.$field['id'].'">';
        echo '<th><label for="'.esc_html($field['id']).'">'.esc_html($field['label']).'</label><br><span class="description">'.esc_html($field['desc']).'</span></th>';
        echo '<td>';
        switch($field['type']) {
            case 'wp_editor':
                //echo '<div class="motor-product-editor">';
                wp_editor( $meta, $field['id'], array('textarea_name' => $field['id']) );
                //echo '</div>';
                break;

            // radio
            case 'radio':

                $int_key = 0;
                foreach ( $field['options'] as $option ) {

                    if (!empty($meta)) {
                        if ($meta == $option['value']) {
                            $checked = ' checked="checked"';
                        } else {
                            $checked = '';
                        }
                    } elseif ($int_key == 0) {
                        $checked = ' checked="checked"';
                    } else {
                        $checked = '';
                    }

                    echo '<input type="radio" name="'.$field['id'].'" id="'.$option['value'].'" value="'.$option['value'].'"'.$checked.'><label for="'.$option['value'].'">'.$option['label'].'</label><br />';

                    $int_key++;
                }
                break;
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// Save the Data
function motor_save_product_meta($post_id) {
    global $motor_product_meta_fields;

    // verify nonce
    if (empty($_POST['custom_meta_box_nonce']) || !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // loop through fields and save the data
    foreach ($motor_product_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
add_action('save_post', 'motor_save_product_meta');

/*
 * PRODUCT FIELDS - end
 */


/*
 * GALLERY FIELDS - start
 */

// Gallery Fields
$prefix_gallery = 'motor_gallery_';
$motor_gallery_meta_fields = array(
    array(
        'label'=> esc_html__('Video Link', 'motor'),
        'desc'  => esc_html__('If you need a modal video, you can paste a video URL from Youtube', 'motor'),
        'id'    => $prefix_gallery.'video',
        'type'  => 'text'
    ),
);

// Add Meta Box
function motor_gallery_add_custom_meta_box() {
    add_meta_box(
        'motor_gallery_fields', // ID
        esc_html__('Gallery Fields', 'motor'), // title
        'motor_show_gallery_fields', // callback
        'motor_gallery', // post type
        'normal', // context
        'default' // priority
    );
}
add_action('add_meta_boxes', 'motor_gallery_add_custom_meta_box');

// Show Fields
function motor_show_gallery_fields() {
    global $motor_gallery_meta_fields, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'">';

    // Begin the field table and loop
    echo '<table class="form-table motor-form-table">';
    foreach ($motor_gallery_meta_fields as $field) {

        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);

        // begin a table row with
        echo '<tr>';
        echo '<th><label for="'.esc_html($field['id']).'">'.esc_html($field['label']).'</label></th>';
        echo '<td>';
        switch($field['type']) {
            case 'text':
                echo '<input type="text" name="'.esc_html($field['id']).'" id="'.esc_html($field['id']).'" value="'.esc_html($meta).'" size="30" /><span class="description">'.esc_html($field['desc']).'</span>';
                break;
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// Save the Data
function motor_save_gallery_meta($post_id) {
    global $motor_gallery_meta_fields;

    // verify nonce
    if (empty($_POST['custom_meta_box_nonce']) || !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // loop through fields and save the data
    foreach ($motor_gallery_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
add_action('save_post', 'motor_save_gallery_meta');
/*
 * GALLERY FIELDS - end
 */


/*
 * EVENTS FIELDS - start
 */

// Events Fields
$prefix_events = 'motor_events_';
$motor_events_meta_fields = array(
    array(
        'label'=> esc_html__('Date', 'motor'),
        'id'    => $prefix_events.'date',
        'type'  => 'text'
    ),
);

// Add Meta Box
function motor_events_add_custom_meta_box() {
    add_meta_box(
        'motor_events_fields', // ID
        esc_html__('Date', 'motor'), // title
        'motor_show_events_fields', // callback
        'events', // post type
        'normal', // context
        'default' // priority
    );
}
add_action('add_meta_boxes', 'motor_events_add_custom_meta_box');

// Show Fields
function motor_show_events_fields() {
    global $motor_events_meta_fields, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'">';

    // Begin the field table and loop
    echo '<table class="form-table motor-form-table">';
    foreach ($motor_events_meta_fields as $field) {

        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);

        // begin a table row with
        echo '<tr>';
        echo '<th><label for="'.esc_html($field['id']).'">'.esc_html($field['label']).'</label></th>';
        echo '<td>';
        switch($field['type']) {
            case 'text':
                echo '<input class="motor-datepicker" type="text" name="'.esc_html($field['id']).'" id="'.esc_html($field['id']).'" value="'.esc_html($meta).'" size="30" /><span class="description">'.esc_html($field['desc']).'</span>';
                break;
        }
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}

// Save the Data
function motor_save_events_meta($post_id) {
    global $motor_events_meta_fields;

    // verify nonce
    if (empty($_POST['custom_meta_box_nonce']) || !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // loop through fields and save the data
    foreach ($motor_events_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
add_action('save_post', 'motor_save_events_meta');
/*
 * EVENTS FIELDS - end
 */


