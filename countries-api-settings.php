<?php
/**
* Plugin Name: Countries API Connection
* Description: Display data from API url https://countries.trevorblades.com/graphql.
* Version: 1.0
* Author: Kosta Binov
**/

function register_top_level_menu_api() {
	add_menu_page(
		'API Settings',
		'API Settings',
		'manage_options',
		'api-settings',
		'api_dashboard_settings_view',
		'',
	);
}
add_action( 'admin_menu', 'register_top_level_menu_api' );

function api_dashboard_settings_view() {
	include( plugin_dir_path( __FILE__ ) . 'dashboard-settings.php');
}

function api_settings_active() {
	add_option('api_settings_activation_redirect', true);
}
register_activation_hook( __FILE__, 'api_settings_active' );
add_action('admin_init', 'api_settings_redirect');

function api_settings_redirect() {
    if (get_option('api_settings_activation_redirect', false)) {
        delete_option('api_settings_activation_redirect');
		wp_redirect( get_home_url().'/wp-admin/admin.php?page=api-settings', 301 ); exit;
    }
}

function countries_page_template( $template ) {
    $file_name = 'countries_view.php';

    if ( is_page( 'countries' ) ) {
    	$template = dirname( __FILE__ ) . '/templates/' . $file_name;
    }

    return $template;
}

add_filter( 'template_include', 'countries_page_template', 99 );

function api_settings_scripts() {
	wp_register_script('custom_js', plugins_url('/assets/js/script.js',__FILE__ ), array('jquery'), '', true);
	wp_enqueue_script('custom_js');
	wp_localize_script('custom_js', 'WPURLS', array( 'siteurl' => get_option('siteurl') ));

	wp_register_style( 'style', plugins_url( '/countries-api-settings/assets/css/style.css') );
	wp_enqueue_style( 'style' );
}
add_action('wp_enqueue_scripts', 'api_settings_scripts');

function api_settings_enqueue($hook) {
    wp_register_script('custom_js_admin', plugins_url('/assets/js/admin-scripts.js',__FILE__ ), array('jquery'), '', true);
	wp_enqueue_script('custom_js_admin');

	wp_register_style( 'style_admin', plugins_url( '/countries-api-settings/assets/css/admin-style.css') );
	wp_enqueue_style( 'style_admin' );
}

add_action('admin_enqueue_scripts', 'api_settings_enqueue');

function saveLimit(){
	if ( isset($_POST['timelimit']) ) {
		if (! get_option('timelimit')) {
			add_option('timelimit', $_POST['timelimit']);
		} else {
			update_option('timelimit', $_POST['timelimit']);
		}
	}
}
add_action( 'init', 'saveLimit' );


function countriesApiCall() {
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://countries.trevorblades.com/graphql',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>'{"query":"query {\\n  countries{\\n        name\\n        capital\\n        currency\\n        native\\n        emoji\\n        languages{\\n            name\\n        }\\n        continent{\\n            name\\n        }\\n    }\\n}\\n","variables":{}}',
	CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json'
	),
	));

	$response = curl_exec($curl);
	curl_close($curl);
	return $response;
}


function cacheData() {

	$timeLimit = 60;
	if (get_option('timelimit') >= 0) {
		$timeLimit = get_option('timelimit');
	}
	$cacheFile = plugin_dir_path( __FILE__ ).'/cache/cache.json';	
	if (file_exists($cacheFile)) {
		$timeFileCreated = strtotime(date("H:i:s", filemtime($cacheFile)));
		$currentTime = strtotime(date("H:i:s"));
		$interval = abs($currentTime - $timeFileCreated);
		$minutes = round($interval / 60);

		if ($minutes > $timeLimit) {
			$response = countriesApiCall();		
			unlink($cacheFile);
			$fh = fopen($cacheFile, 'w');
			fwrite($fh, $response);
			fclose($fh);	
		}
	} else {
		if (!is_dir(plugin_dir_path( __FILE__ ).'/cache/')) {
			mkdir(plugin_dir_path( __FILE__ ).'/cache/');
		}
		$response = countriesApiCall();
		$fh = fopen($cacheFile, 'w');
		fwrite($fh, $response);
		fclose($fh);	
	}
}
add_action( 'init', 'cacheData' );
