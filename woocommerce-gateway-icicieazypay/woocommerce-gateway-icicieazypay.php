<?php
/*
Plugin Name: WooCommerce ICICI Eazy Pay
Description: ICICI Eazy Pay.
Version: 1.0.0
Author: Lalit yadav
Author URI: http://lalityadavswd.blogspot.in/
*/

add_action('plugins_loaded', 'woocommerce_gateway_icicieazypay_init', 0);

function woocommerce_gateway_icicieazypay_init() {
if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

/**
  * Gateway class
  */
class WC_Gateway_Icicieazypay extends WC_Payment_Gateway {

    /**
         * Make __construct()
         **/
public function __construct(){

$this->id = 'icicieazypay'; // ID for WC to associate the gateway values
$this->method_title = 'Icici Eazypay'; // Gateway Title as seen in Admin Dashboad
$this->method_description = 'Icici Eazypay - Redefining Payments, Simplifying Lives'; // Gateway Description as seen in Admin Dashboad
$this->has_fields = false; // Inform WC if any fileds have to be displayed to the visitor in Frontend

$this->init_form_fields(); // defines your settings to WC
$this->init_settings(); // loads the Gateway settings into variables for WC

// Special settigns if gateway is on Test Mode
$test_title = '';
$test_description = '';

$this->title = $this->settings['title'].$test_title; // Title as displayed on Frontend
$this->description = $this->settings['description'].$test_description; // Description as displayed on Frontend

            $this->key_id = $this->settings['key_id'];
            $this->key_secret = $this->settings['key_secret'];
$this->liveurl = 'https://eazypay.icicibank.com/EazyPG';
$this->redirect_page = $this->settings['redirect_page']; // Define the Redirect Page.
//$this->service_provider = $this->settings['service_provider']; // The Service options for Icici Eazypay.

            $this->msg['message'] = '';
            $this->msg['class'] = '';

add_action('init', array(&$this, 'check_icicieazypay_response'));
            add_action('woocommerce_api_' . strtolower(get_class($this)), array($this, 'check_icicieazypay_response')); //update for woocommerce >2.0

            if ( version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
                    add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) ); //update for woocommerce >2.0
                 } else {
                    add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) ); // WC-1.6.6
                }
            add_action('woocommerce_receipt_icicieazypay', array(&$this, 'receipt_page'));
} //END-__construct

        /**
         * Initiate Form Fields in the Admin Backend
         **/
function init_form_fields(){

$this->form_fields = array(
// Activate the Gateway
'enabled' => array(
'title' => __('Enable/Disable:', 'woo_icicieazypay'),
'type' => 'checkbox',
'label' => __('Enable Icici Eazypay', 'woo_icicieazypay'),
'default' => 'no',
'description' => 'Show in the Payment List as a payment option'
),
// Title as displayed on Frontend
      'title' => array(
'title' => __('Title:', 'woo_icicieazypay'),
'type' => 'text',
'default' => __('Online Payments', 'woo_icicieazypay'),
'description' => __('This controls the title which the user sees during checkout.', 'woo_icicieazypay'),
'desc_tip' => true
),
// Description as displayed on Frontend
      'description' => array(
'title' => __('Description:', 'woo_icicieazypay'),
'type' => 'textarea',
'default' => __('Pay securely by Credit or Debit card or internet banking through IciciEazypay.', 'woo_icicieazypay'),
'description' => __('This controls the description which the user sees during checkout.', 'woo_icicieazypay'),
'desc_tip' => true
),

// LIVE Key-ID
      'key_id' => array(
'title' => __('Merchant KEY:', 'woo_icicieazypay'),
'type' => 'text',
'description' => __('Given to Merchant by Icici Eazypay team'),
'desc_tip' => true
),
  // LIVE Key-Secret
    'key_secret' => array(
'title' => __('Merchant SALT:', 'woo_icicieazypay'),
'type' => 'text',
'description' => __('Given to Merchant by IciciEazyPay Money'),
'desc_tip' => true
                ),
  // Mode of Transaction
     
  // Page for Redirecting after Transaction
      'redirect_page' => array(
'title' => __('Return Page'),
'type' => 'text',
'description' => __('Given to return url '),
'desc_tip' => true
                )
  // Show Logo on Frontend
     
);

}
}}
?>
