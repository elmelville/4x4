<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', function() {add_options_page( 'Convermax Settings', 'Convermax', 'manage_options', 'convermax_settings', 'page_output'  );} );
add_action('admin_init', 'convermax_settings' );

function page_output() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'Access denied' ) );
    }
    /*if( ConvermaxAPI::get_option( 'convermax_cert_data' ) ) {
        settings_block();
    } else {
        registration_block();
    }*/
    settings_block();
}

function settings_block() {
    $cm = new ConvermaxAPI();
    $count = $cm->getIndexedProducts();
    $cron_url = get_site_url().'?cm_reindex_key='.ConvermaxAPI::get_option( 'convermax_key' );
    $api_url = ConvermaxAPI::get_option('convermax_url');
    if (stristr(substr($api_url, -1), '/')) {
        $api_url = substr($api_url, 0, -1);
    }
    ?>
    <div class="wrap">
        <h2>Convermax Settings</h2>
        <p>Indexed products: <span id="cm_products"><?php echo $count ?></span></p>
        <!--p><input type="button" data-url="<?php echo $cron_url ?>" data-api_url="<?php echo $api_url ?>" id="reindex" value="Reindex"></p-->
        <p>Cron URL: <?php echo $cron_url; ?></p>
        <?php /*<div style="display: none;">
        <form action="options.php" method="POST" enctype="multipart/form-data">
            <?php
            settings_fields( 'convermax' );
            do_settings_fields( 'convermax_settings', 'default' );
            submit_button();
            ?>
        </form>
        </div>
        */ ?>
    </div>
    <?php
}

function registration_block() {

}

function convermax_settings() {
    if( !empty( $_GET['cm_token'] ) && !ConvermaxAPI::get_option( 'convermax_cert_data' ) ) {
        activate();
    }

    $deps = array( 'jquery');
    wp_enqueue_script( 'cm_admin', plugins_url( 'convermax' ) . '/js/admin.js', $deps );
    wp_enqueue_style( 'cm_admin', plugins_url( 'convermax' ) . '/css/admin.css');

    register_setting( 'convermax', 'convermax_url' );
    register_setting( 'convermax', 'convermax_surl' );
    register_setting( 'convermax', 'convermax_cert_name', array( __CLASS__, 'handle_cert_upload' ) );

    add_settings_field('cm_url', 'URL', array( __CLASS__, 'url_field' ), 'convermax_settings' );
    add_settings_field('cm_surl', 'Secure URL', array( __CLASS__, 'surl_field' ), 'convermax_settings' );
    add_settings_field('cm_cert', 'Certificate file', array( __CLASS__, 'cert_field' ), 'convermax_settings' );
}

function url_field() {
    $url = ConvermaxAPI::get_option('convermax_url');
    echo '<div><input type="text" name="convermax_url" id="cm_url" value="'.$url.'"></div>';
}

function surl_field() {
    $surl = ConvermaxAPI::get_option('convermax_surl');
    echo '<div><input type="text" name="convermax_surl" id="cm_surl" value="'.$surl.'"></div>';
}

function cert_field() {
    echo '<div><input type="file" name="convermax_cert_name" id="cm_cert"></div>'.ConvermaxAPI::get_option('convermax_cert_name');
}

function handle_cert_upload($option) {
    if(!empty($_FILES['convermax_cert_name']['tmp_name'])) {
        if (stristr(substr($_FILES['convermax_cert_name']['name'], -4), '.pem')) {
            update_option( 'convermax_cert_data', file_get_contents( $_FILES['convermax_cert_name']['tmp_name'] ) );
            return $_FILES['convermax_cert_name']['name'];
        } else {
            add_settings_error('convermax_cert_error', 'convermax_cert_error', 'Invalid certificate file.');
        }
    }
    return ConvermaxAPI::get_option('convermax_cert_name');
}

function activate() {
    $cm = new ConvermaxAPI();
    if ( $cert = $cm->getCertificate( $_GET['cm_token'] ) ) {
        update_option( 'convermax_cert_name', 'certificate.pem' );
        update_option( 'convermax_cert_data', $cert );
        if ( $hash = $cm->getHash( get_bloginfo( 'name' ) ) ) {
            if ( $cm->createIndexFields() ) {
                add_settings_error('convermax_activated', 'convermax_activated', 'Configuration updated.', 'updated');
            } else {
                add_settings_error('convermax_fields_error', 'convermax_fields_error', 'An error occurred while attempting to create fields.');
            }
        } else {
            add_settings_error('convermax_hash_error', 'convermax_hash_error', 'An error occurred while attempting to get hash.');
        }
    } else {
        add_settings_error('convermax_getcert_error', 'convermax_getcert_error', 'An error occurred while attempting to get certificate.');
    }
}
