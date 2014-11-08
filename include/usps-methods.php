<?php
/**
 * Check if WooCommerce is active
 */
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/*** Check that USPS shipper information has been verified ***/
	$usps_options = get_option('wc_usps_shipping');
	if( is_array($usps_options) && ( array_key_exists('usps_Verified', $usps_options) ) ) {
		$usps_Verified = $usps_options['usps_Verified'];
	}
	if( isset($usps_Verified) && $usps_Verified ) {
 
		/*** Parent class for USPS methods ***/
		//Initialize WooCommerce shipping settings for each method
		//Create rate calculation function
		function usps_init() {
			if ( ! class_exists( 'WC_USPS' ) ) {
				class WC_USPS extends WC_Shipping_Method {
					/**
					 * General constructor for USPS methods
					 *
					 * @access public
					 * @return void
					 */

					public function __construct() {
						$this->init();
					}
	 
					/**
					 * Init method settings
					 *
					 * @access public
					 * @return void
					 */
					function init() {
						// Load the settings API

						$label = 'Enable ' . $this->method_title;
						$default = $this->method_title;
					
						$this->form_fields = array(
							'enabled' => array(
														'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
														'type' 			=> 'checkbox',
														'label' 		=> __( $label, 'woocommerce' ),
														'default' 		=> 'no',
												),
							'title' => array(
														'title' 		=> __( 'Method Title', 'woocommerce' ),
														'type' 			=> 'text',
														'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
														'default'		=> __( $default, 'woocommerce' ),
												),
						);  
							
						$this->init_settings();
							$this->enabled		  = $this->settings['enabled'];
							$this->title 		  = $this->settings['title'];
					
						// Save settings in admin
						add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

	
					}

					/**
					 * calculate_shipping function
					 * pass cart information and service code to usps-rate.php
					 *
					 * @access public
					 * @param mixed $package
					 * @return void
					 */
				
				
					public function calculate_shipping( $package ) { 
						$usps_options = get_option('wc_usps_shipping');
							if ($usps_options && ($usps_options != '') ) {
								$UserID 			 = $usps_options['usps_UserID'];
								$FromPostalCode		 = $usps_options['usps_FromPostalCode'];
							}
						include_once( plugin_dir_path( __FILE__ ) . 'usps-rate.php' );
						$service = $this->service;
						global $woocommerce;
						$weight = $woocommerce->cart->cart_contents_weight;
						$dest_zip =  $woocommerce->customer->get_postcode(); 
						$dest_country =  $woocommerce->customer->get_country(); 
						if ( isset($weight, $dest_zip, $dest_country) && ($dest_zip != '') ) {
							$uspsrate = (usps($dest_zip,$dest_country,$service,$weight,$UserID,$FromPostalCode));
							if($uspsrate > 0) {
								$rate = array(
								'id' 	   => $this->id,
								'label'	   => $this->title,
								'cost'	   => $uspsrate,
								'calc_tax' => 'per_item'
								);
								// Register the rate
								$this->add_rate( $rate );
							} else return;
						} else return;
					}
				}
			}
		}

		add_action( 'woocommerce_shipping_init', 'usps_init' );
	
		/*** USPS Priority class ***/
		function usps_priority_init() {
			if ( ! class_exists( 'WC_USPS_Priority' ) ) {
				class WC_USPS_Priority extends WC_USPS {
					/**
					 * Constructor for Priority
					 *
					 * @access public
					 * @return void
					 */
					public function __construct() {
						$this->id                 = ('usps_priority'); // Method ID
						$this->method_title       = __( 'USPS Priority Mail' );  // Title shown in admin
						$this->method_description = __( 'Ship USPS Priority Mail' ); // Description shown in admin
						$this->service            = 'PRIORITY'; //USPS service code
						$this->init();
					}
				}
			}
		}
		 
		add_action( 'woocommerce_shipping_init', 'usps_priority_init' );
	 
		function add_usps_priority( $methods ) {
			$methods[] = 'WC_USPS_Priority';
			return $methods;
		}
	 
		add_filter( 'woocommerce_shipping_methods', 'add_usps_priority' );
	
	
		/*** USPS Express class ***/
		function usps_express_init() {
			if ( ! class_exists( 'WC_USPS_Express' ) ) {
				class WC_USPS_Express extends WC_USPS {
					/**
					 * Constructor for Express
					 *
					 * @access public
					 * @return void
					 */
					public function __construct() {
						$this->id                 = ('usps_express'); // Method ID
						$this->method_title       = __( 'USPS Express Mail' );  // Title shown in admin
						$this->method_description = __( 'Ship USPS Express Mail' ); // Description shown in admin
						$this->service            = 'PRIORITY MAIL EXPRESS'; //USPS service code
						$this->init();
					}
				}
			}
		}
		 
		add_action( 'woocommerce_shipping_init', 'usps_express_init' );
	 
		function add_usps_express( $methods ) {
			$methods[] = 'WC_USPS_Express';
			return $methods;
		}
	 
		add_filter( 'woocommerce_shipping_methods', 'add_usps_express' );

	}
}