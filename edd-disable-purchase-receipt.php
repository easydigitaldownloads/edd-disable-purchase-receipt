<?php
/*
Plugin Name: Easy Digital Downloads - Disable Purchase Receipt
Plugin URI: http://sumobi.com/shop/disable-purchase-receipt/
Description: Disables the standard purchase receipt sent to the customer
Version: 1.0
Author: Andrew Munro, Sumobi
Author URI: http://sumobi.com/
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'EDD_Disable_Purchase_Receipt' ) ) {

	class EDD_Disable_Purchase_Receipt {

		private static $instance;

		/**
		 * Main Instance
		 *
		 * Ensures that only one instance exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 *
		 */
		public static function instance() {
			if ( ! isset ( self::$instance ) ) {
				self::$instance = new self;
			}

			return self::$instance;
		}


		/**
		 * Start your engines.
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		public function __construct() {
			$this->setup_actions();
		}


		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function setup_actions() {
			add_action( 'edd_complete_purchase', array( $this, 'disable_purchase_receipt' ), -999, 2 );
		}


		/**
		 * Disable standard purchase receipt
		 * @since 1.0
		*/
		function disable_purchase_receipt( $payment_id, $admin_notice = true ) {
			
			$payment_data = edd_get_payment_meta( $payment_id );

			// prevents standard purchase receipt from firing
			remove_action( 'edd_complete_purchase', 'edd_trigger_purchase_receipt', 999, 1 );

			//the above remove_action disables the admin notification, so let's get it going again
			if ( $admin_notice && ! edd_admin_notices_disabled( $payment_id ) ) {
				do_action( 'edd_admin_sale_notice', $payment_id, $payment_data );
			}

		}
		

	}
	
}

function edd_disable_purchase_receipt() {
	return EDD_Disable_Purchase_Receipt::instance();
}

edd_disable_purchase_receipt();