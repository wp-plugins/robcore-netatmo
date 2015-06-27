<?php
/*
 *	Plugin Name: Robcore Netatmo
 *	Plugin URI: http://robertnucci.it/robcore-netatmo-wordpress-plugin/
 *	Description: Display Netatmo weather data using shortcodes.
 *	Version: 1.6
 *	Author: Robert Nucci
 *	Author URI: http://robertnucci.it
 *	License: GPL2
*/

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'rnpfw_add_action_links' );

function rnpfw_add_action_links ( $links ) {
 $mylinks = array(
 '<a href="' . admin_url( 'options-general.php?page=robcore-netatmo' ) . '">Settings</a>',
 );
return array_merge( $links, $mylinks );
}

function rnpfw_attivazionePlugin() {
	global $wpdb;

	$table_name = $wpdb->prefix . "robcore_netatmo_data"; 
	$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			id int NOT NULL AUTO_INCREMENT,
			id_modulo varchar(55),
			id_device varchar(55),
			time_misura datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			valore_misurato varchar(55) NOT NULL,
			misurazione varchar(55) DEFAULT '' NOT NULL,
			UNIQUE KEY id (id)
		) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
register_activation_hook( __FILE__, 'rnpfw_attivazionePlugin' );


$plugin_url = WP_PLUGIN_URL . '/robcore-netatmo';

function rnpfw_robcore_netatmo_menu() {

	add_options_page(
		'Robcore Netatmo',
		'Robcore Netatmo Settings',
		'manage_options',
		'robcore-netatmo',
		'rnpfw_robcore_netatmo_options_page'
	);

}
add_action( 'admin_menu', 'rnpfw_robcore_netatmo_menu' );

// ########################################################
// PAGINA OPZIONI - INTEGRAZIONE SDK - CLASSE
// ########################################################

include('inc/class_robcore_netatmo.php');

function rnpfw_robcore_netatmo_options_page() {
	if( !current_user_can( 'manage_options' ) ) {

		wp_die( 'You do not have sufficient permissions to access this page.' );
	}

	global $plugin_url;

	echo "<h1>Robcore Netatmo for WordPress</h1><hr>";
		include('inc/sdk-setup.php');
		include('inc/ottengo_credenziali_user.php');
		$oggettoMeteo = new RobcoreNetAtmo();
		include('view/settings.tmpl.php');

}

// ########################################################
// SHORTCODE NETATMO
// ########################################################

function rnpfw_shortcodeDatiMeteo( $atts ) {
		include('inc/sdk-setup.php');
		include('inc/ottengo_credenziali_user.php');
		$oggettoMeteoPerShortcode = new RobcoreNetAtmo();
			if (!isset($oggettoMeteoPerShortcode)) {
			$oggettoMeteoPerShortcode = new RobcoreNetAtmo();
			}
    $a = shortcode_atts( array(
    	'data' => '',
    	'module_id' => '',
    	'station_id' => ''
    ), $atts );

    if ($a['data'] == "temp-ext-now") {
    	if (get_option('robcore_nap_options_press_temp')[0] == "fahrenheit") {
    		return $oggettoMeteoPerShortcode->rnpfw_ottieniFdaC($oggettoMeteoPerShortcode->rnpfw_tempExtNow($a['module_id'],$a['station_id'], $client)) . " °F";
    	} else {
    		return $oggettoMeteoPerShortcode->rnpfw_tempExtNow($a['module_id'],$a['station_id'], $client) . " °C";
    	}
    }
    elseif ($a['data'] == "hum-ext-now") {
    	return $oggettoMeteoPerShortcode->rnpfw_humExtNow($a['module_id'],$a['station_id'], $client) . " %";
    }
    elseif ($a['data'] == "press-now") {
    	if (get_option('robcore_nap_options_press_temp')[1] == "mmHg") {
    	return round($oggettoMeteoPerShortcode->rnpfw_ottieni_mmHg_da_hPa($oggettoMeteoPerShortcode->rnpfw_pressNow($a['station_id'], $client)),2) . " mmHg";
    	} else {
    	return $oggettoMeteoPerShortcode->rnpfw_pressNow($a['station_id'], $client) . " hPa";
    	}
    } else {
    	return "Shortcode written the wrong way.";
    }

}
add_shortcode( 'robcore-netatmo', 'rnpfw_shortcodeDatiMeteo' );
?>