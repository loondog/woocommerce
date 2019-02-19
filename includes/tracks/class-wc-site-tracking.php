<?php
/**
 * Nosara Tracks for WooCommerce
 *
 * @package WooCommerce\Tracks
 */

defined( 'ABSPATH' ) || exit;

/**
 * This class adds actions to track usage of WooCommerce.
 */
class WC_Site_Tracking {

	/**
	 * Send a Tracks event when a product is updated.
	 *
	 * @param int   $product_id Product id.
	 * @param array $post WordPress post.
	 */
	public static function tracks_product_updated( $product_id, $post ) {
		if ( 'product' !== $post->post_type ) {
			return;
		}

		$properties = array(
			'product_id' => $product_id,
		);

		WC_Tracks::record_event( 'update_product', $properties );
	}

	/**
	 * Check if tracking is enabled.
	 *
	 * @return bool
	 */
	public static function is_tracking_enabled() {
		/**
		 * Don't track users who haven't opted-in to tracking or if a filter
		 * has been applied to turn it off.
		 */
		if ( 'yes' !== get_option( 'woocommerce_allow_tracking' ) ||
			! apply_filters( 'woocommerce_apply_user_tracking', true ) ) {
			return false;
		}

		if ( ! class_exists( 'WC_Tracks' ) ) {
			return false;
		}

		return true;
	}

	public static function enqueue_scripts( $allow_tracking ) {
		$early_return = $allow_tracking ? 'false' : 'true';

		wc_enqueue_js( "
			window.wcSettings = window.wcSettings || {};
			window.wcSettings.recordEvent = function( event, eventProperties ) {
				if ( " . $early_return . " ) {
					return;
				}
				window._tkq = window._tkq || [];
				window._tkq.push( [ 'recordEvent', event, eventProperties ] );
			}
		" );
	}

	/**
	 * Init tracking.
	 */
	public static function init() {
		$allow_tracking = self::is_tracking_enabled();

		self::enqueue_scripts( $allow_tracking );

		if ( ! $allow_tracking ) {
			return;
		}

		wp_enqueue_script( 'woo-tracks', '//stats.wp.com/w.js', array(), gmdate( 'YW' ), true );

		add_action( 'edit_post', array( 'WC_Site_Tracking', 'tracks_product_updated' ), 10, 2 );
	}
}
