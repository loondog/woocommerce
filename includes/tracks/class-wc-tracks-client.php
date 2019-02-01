<?php
<<<<<<< HEAD
/**
 * Send Tracks events on behalf of a user.
 *
 * @package WooCommerce\Tracks
 */

defined( 'ABSPATH' ) || exit;

/**
 * WC_Tracks_Client class.
 */
class WC_Tracks_Client {

	/**
	 * Pixel URL.
	 */
	const PIXEL = 'https://pixel.wp.com/t.gif';

	/**
	 * Browser type.
	 */
	const BROWSER_TYPE = 'php-agent';

	/**
	 * User agent.
	 */
=======

/**
 * Send Tracks events on behalf of a user.
 *
 * @class   WC_Tracks_Client
 * @package WooCommerce/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WC_Tracks_Client.
 */
class WC_Tracks_Client {
	const PIXEL           = 'https://pixel.wp.com/t.gif';
	const BROWSER_TYPE    = 'php-agent';
>>>>>>> c1e3ce6a3... Tracks: Add a PHP client
	const USER_AGENT_SLUG = 'tracks-client';

	/**
	 * Record a Tracks event
	 *
<<<<<<< HEAD
	 * @param  array $event Array of event properties.
=======
	 * @param  array $event Array of event properties
>>>>>>> c1e3ce6a3... Tracks: Add a PHP client
	 * @return bool|WP_Error         True on success, WP_Error on failure.
	 */
	public static function record_event( $event ) {
		if ( ! $event instanceof WC_Tracks_Event ) {
			$event = new WC_Tracks_Event( $event );
		}
<<<<<<< HEAD

=======
>>>>>>> c1e3ce6a3... Tracks: Add a PHP client
		if ( is_wp_error( $event ) ) {
			return $event;
		}

		$pixel = $event->build_pixel_url( $event );

		if ( ! $pixel ) {
			return new WP_Error( 'invalid_pixel', 'cannot generate tracks pixel for given input', 400 );
		}

		return self::record_pixel( $pixel );
	}

	/**
	 * Synchronously request the pixel.
	 *
	 * @param string $pixel pixel url and query string.
	 * @return bool|WP_Error         True on success, WP_Error on failure.
	 */
	public static function record_pixel( $pixel ) {
		// Add the Request Timestamp and URL terminator just before the HTTP request.
		$pixel .= '&_rt=' . self::build_timestamp() . '&_=_';

<<<<<<< HEAD
		$response = wp_safe_remote_get(
			$pixel,
			array(
				'blocking'    => true, // The default, but being explicit here.
=======
		$response = wp_remote_get(
			$pixel,
			array(
				'blocking'    => true, // The default, but being explicit here.
				'timeout'     => 1,
>>>>>>> c1e3ce6a3... Tracks: Add a PHP client
				'redirection' => 2,
				'httpversion' => '1.1',
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

<<<<<<< HEAD
		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
=======
		$code = isset( $response['response']['code'] ) ? $response['response']['code'] : 0;

		if ( 200 !== $code ) {
>>>>>>> c1e3ce6a3... Tracks: Add a PHP client
			return new WP_Error( 'request_failed', 'Tracks pixel request failed', $code );
		}

		return true;
	}

	/**
	 * Create a timestap representing milliseconds since 1970-01-01
	 *
	 * @return string A string representing a timestamp.
	 */
	public static function build_timestamp() {
		$ts = round( microtime( true ) * 1000 );
<<<<<<< HEAD

=======
>>>>>>> c1e3ce6a3... Tracks: Add a PHP client
		return number_format( $ts, 0, '', '' );
	}

	/**
	 * Grabs the user's anon id from cookies, or generates and sets a new one
	 *
<<<<<<< HEAD
	 * @todo: Determine the best way to identify sites/users with/without Jetpack connection.
=======
	 * @TODO: Determine the best way to identify sites/users with/without Jetpack connection.
	 *
>>>>>>> c1e3ce6a3... Tracks: Add a PHP client
	 * @return string An anon id for the user
	 */
	public static function get_anon_id() {
		static $anon_id = null;

		if ( ! isset( $anon_id ) ) {

			// Did the browser send us a cookie?
<<<<<<< HEAD
			if ( isset( $_COOKIE['tk_ai'] ) && preg_match( '#^[A-Za-z0-9+/=]{24}$#', wp_unslash( $_COOKIE['tk_ai'] ) ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$anon_id = wp_unslash( $_COOKIE['tk_ai'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
=======
			if ( isset( $_COOKIE['tk_ai'] ) && preg_match( '#^[A-Za-z0-9+/=]{24}$#', $_COOKIE['tk_ai'] ) ) {
				$anon_id = $_COOKIE['tk_ai'];
>>>>>>> c1e3ce6a3... Tracks: Add a PHP client
			} else {

				$binary = '';

				// Generate a new anonId and try to save it in the browser's cookies
				// Note that base64-encoding an 18 character string generates a 24-character anon id.
				for ( $i = 0; $i < 18; ++$i ) {
					$binary .= chr( wp_rand( 0, 255 ) );
				}

				$anon_id = 'jetpack:' . base64_encode( $binary );

				if ( ! headers_sent()
					&& ! ( defined( 'REST_REQUEST' ) && REST_REQUEST )
					&& ! ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST )
				) {
					setcookie( 'tk_ai', $anon_id );
				}
			}
		}

		return $anon_id;
	}
}
