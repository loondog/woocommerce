<?php
/**
 * WooCommerce Import Tracking
 *
 * @package WooCommerce\Tracks
 */

defined( 'ABSPATH' ) || exit;

/**
 * This class adds actions to track usage of WooCommerce Orders.
 */
class WC_Orders_Tracking {
	/**
	 * Init tracking.
	 */
	public static function init() {
		add_action( 'woocommerce_order_edit_status', array( __CLASS__, 'track_orders_edit_status_change' ), 10, 2 );
	}

	/**
	 * Send a Tracks event when an order status is changed.
	 *
	 * @param int    $id Order id.
	 * @param string $new_status WooCommerce order status.
	 */
	public static function track_orders_edit_status_change( $id, $new_status ) {
		$properties = array(
			'order_id'   => $id,
			'new_status' => $new_status,
		);

		WC_Tracks::record_event( 'orders_edit_status_change', $properties );
	}
}
