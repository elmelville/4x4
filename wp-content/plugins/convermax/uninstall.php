<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

wp_delete_post( ConvermaxAPI::get_option( 'convermax_searchpage' ), true );

delete_option( 'convermax_url' );
delete_option( 'convermax_surl' );
delete_option( 'convermax_key' );
delete_option( 'convermax_searchpage' );
delete_option( 'convermax_cert_name' );
delete_option( 'convermax_cert_data' );