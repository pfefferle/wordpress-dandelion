<?php
/**
 * Diaspora plugin class
 */
class Dandelion {
	/**
	 * Add query variables
	 */
	public static function add_query_vars( $vars ) {
		$vars[] = 'hcard';
		$vars[] = 'diaspora';
		$vars[] = 'type';
		$vars[] = 'guid';

		return $vars;
	}

	/**
	 * Parse the WebFinger request and render the document.
	 *
	 * @param WP $wp WordPress request context
	 *
	 * @uses apply_filters() Calls 'webfinger' on webfinger data array
	 * @uses do_action() Calls 'webfinger_render' to render webfinger data
	 */
	public static function parse_request( $wp ) {
		// check if it is a dandelion request or not
		if ( ! array_key_exists( 'diaspora', $wp->query_vars ) ) {
			return;
		}

		status_header( 202 );

		exit;
	}

	/**
	 * Output the embeddable HTML.
	 */
	public static function render_hcard_template( $template ) {
		if ( ! is_author() ) {
			return $template;
		}

		global $wp_query;

		if ( isset( $wp_query->query_vars['hcard'] ) ) {
			return dirname( __FILE__ ) . '/../templates/author-hcard.php';
		}

		return $template;
	}

	/**
	 * Add WebFinger discovery links
	 *
	 * @param array   $array    the jrd array
	 * @param string  $resource the WebFinger resource
	 * @param WP_User $user     the WordPress user
	 */
	public static function add_webfinger_discovery( $array, $resource, $user ) {
		if ( get_option( 'permalink_structure' ) ) {
			$hcard_url = trailingslashit( get_author_posts_url( $user->ID, $user->nicename ) ) . user_trailingslashit( 'hcard' );
		} else {
			$hcard_url = add_query_arg( array( 'hcard' => 'true' ), get_author_posts_url( $user->ID, $user->nicename ) );
		}

		$array['links'][] = array(
			'rel'  => 'http://microformats.org/profile/hcard',
			'type' => 'text/html',
			'href' => $hcard_url,
		);

		$array['links'][] = array(
			'rel'  => 'http://joindiaspora.com/seed_location',
			'type' => 'text/html',
			'href' => site_url( '/' ),
		);

		return $array;
	}
}
