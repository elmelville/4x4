<?php
/*
Plugin Name: Convermax
Plugin URI: http://convermax.com
Description: Convermax search for WooCommerce
Version: 1.0
Author: CONVERMAX CORP
Author URI: http://convermax.com
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( 'class-convermax-API.php' );
require_once( 'admin-page.php' );

require_once( 'clients/default/client.php' );
require_once( 'clients/default/hooks.php' );

if($client = ConvermaxAPI::get_option('convermax_client')) {
    require_once( 'clients/'.$client.'/client.php' );
    require_once( 'clients/'.$client.'/hooks.php' );
}

register_activation_hook( __FILE__, 'cm_activate' );
register_deactivation_hook( __FILE__, 'cm_deactivate' );
add_action( 'init', 'cm_init' );

function cm_init() {
    $client = cm_get_client();
    $client->initHooks();
    if( !empty( $_GET['cm_reindex_key'] ) && $_GET['cm_reindex_key'] == ConvermaxAPI::get_option('convermax_key') ) {
        ignore_user_abort( true );
        set_time_limit( 0 );
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        ob_start();
        $start = !empty($_GET['start']) ? (int)$_GET['start'] : 0;
        if( $client->indexation( $start, $client->batch_size ) ) {
            $start += $client->batch_size;
            $url = get_site_url() . '/?cm_reindex_key=' . ConvermaxAPI::get_option('convermax_key') . '&start=' . $start;
            header('Location: '.$url);
            ob_end_flush();
            /*$ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $data = curl_exec($ch);*/
        }
        $client->afterIndexation();
        die();
    }
    if( !get_post_status( ConvermaxAPI::get_option( 'convermax_searchpage' ) ) ) {
        cm_create_page();
    }
}

function cm_get_template() {
    $client = ConvermaxAPI::get_option('convermax_client');
    if(file_exists(dirname(__FILE__) . '/clients/'.$client.'/templates/page-search.php')) {
        $cm_template_filename = dirname(__FILE__) . '/clients/'.$client.'/templates/page-search.php';
    } else {
        $cm_template_filename = dirname(__FILE__) . '/clients/default/templates/page-search.php';
    }
	return $cm_template_filename;
}

function cm_activate() {
	if(!get_option('convermax_url')) {
		update_option('convermax_url', 'http://api.convermax.com/v3/the4x4guys');
	}
	if(!get_option('convermax_surl')) {
		update_option('convermax_surl', 'https://admin.convermax.com/v3/the4x4guys');
	}
	if(!get_option('convermax_client')) {
		update_option('convermax_client', 'the4x4guys');
	}
    if(!get_option('convermax_js_file')) {
        update_option('convermax_js_file', '//client.convermax.com/static/the4x4guys/search.min.js');
    }
    if(!get_option('convermax_css_file')) {
        update_option('convermax_css_file', '//client.convermax.com/static/the4x4guys/search.css');
    }
	if(!get_option('convermax_key')) {
		update_option('convermax_key', substr(md5(rand()), 0, 8));
	}
	if(!get_option('convermax_searchpage')) {
		cm_create_page();
	}
}

function cm_deactivate() {
	wp_delete_post( ConvermaxAPI::get_option( 'convermax_searchpage' ), true );
	delete_option( 'convermax_searchpage' );
}

function cm_create_page() {
	$page['post_type']    = 'page';
	$page['post_content'] = '[convermax_search_page]';
	$page['post_parent']  = 0;
	$page['post_author']  = 1;
	$page['post_status']  = 'publish';
	$page['post_title']   = 'Search Results';
	$page['post_name']   = 'search';
	$pageid = wp_insert_post ( $page );

	update_option( 'convermax_searchpage', $pageid );
}

function cm_get_client() {
    $class = ucfirst(ConvermaxAPI::get_option('convermax_client'));
    return new $class;
}