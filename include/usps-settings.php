<?php

$usps_options = array();

/*** Add options page to admin menu ***/
function usps_shipping_menu() {
	add_submenu_page(
		'woocommerce',
		'USPS Shipping for WooCommerce Settings',
		'USPS Shipping',
		'manage_options',
		'usps-shipping',
		'usps_shipping_options_page'
	);
}

add_action('admin_menu', 'usps_shipping_menu');

/*** Create options page ***/
function usps_shipping_options_page() {
	
	global $usps_options;
	global $uspstest;
	
	/*** Exit if current user is not admin ***/
	if( !current_user_can('manage_options')) {
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}
	
	/*** If USPS info has been entered, validate with USPS ***/
	if( isset($_POST['usps_shipping_options_submitted']) ) {
		$submit_check = esc_html($_POST['usps_shipping_options_submitted']);
		if ( $submit_check = 'y' ) {
			$usps_options['usps_UserID']				 = esc_html($_POST['usps_UserID']);
			$usps_options['usps_FromPostalCode']		 = esc_html($_POST['usps_FromPostalCode']);
			$usps_options['usps_LastUpdated']			 = time();
			include_once( plugin_dir_path( __FILE__ ) . 'usps-rate.php' );
			$uspstest = intval(usps(10001,'US','PRIORITY',1,$usps_options['usps_UserID'],$usps_options['usps_FromPostalCode']));
			/*** Pass/fail message for USPS shipper validation ***/
			if(!( $uspstest > 0 )) {
				$uspstest = false;
				$test_message = "There was a problem validating your USPS shipper information. Please verify and re-enter.";
			}
			else {
				$usps_options['usps_Verified'] = true;
				$test_message = "USPS shipper information successfully verified. You are ready to enable USPS shipping methods in WooCommerce Shipping settings.";
				update_option('wc_usps_shipping', $usps_options);
			}
			
		}
	}
	
	
	/*** Check if Update button has been pressed ***/
	if( isset($_POST['usps_shipping_options_update']) ) {
		$usps_shipping_options_update = esc_html($_POST['usps_shipping_options_update']);
	}
	
	else $usps_shipping_options_update = 'n';
	
	/*** Get USPS information from options table ***/
	$usps_options = get_option('wc_usps_shipping');
	
	if ($usps_options != '') {
		$usps_Verified			 = $usps_options['usps_Verified'];
		$usps_UserID 			 = $usps_options['usps_UserID'];
		$usps_FromPostalCode	 = $usps_options['usps_FromPostalCode'];
		$usps_LastUpdated		 = $usps_options['usps_LastUpdated'];
	}
	
	include('settings-page.php');
	
}