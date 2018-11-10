<?php
/**
 * Plugin Name: Diaspora*
 * Plugin URI: https://github.com/pfefferle/wordpress-diaspora
 * Description: An extension to the OStatus plugin, to also support parts of the Diaspora Federated Protocol.
 * Author: Matthias Pfefferle
 * Author URI: http://notiz.blog/
 * License: MIT
 * License URI: http://opensource.org/licenses/MIT
 * Version: 1.0.0
 * Text Domain: diaspora
 * Domain Path: /languages
 */

register_activation_hook( __FILE__, array( 'Diaspora_Plugin', 'activate_plugin' ) );
register_deactivation_hook( __FILE__, array( 'Diaspora_Plugin', 'deactivate_plugin' ) );

add_action( 'init', array( 'Diaspora_Plugin', 'add_rewrite_endpoint' ) );
add_action( 'plugins_loaded', array( 'Diaspora_Plugin', 'init' ) );

/**
 * Diaspora plugin class
 */
class Diaspora_Plugin {

	public static function init() {
		add_action( 'template_redirect', array( 'Diaspora_Plugin', 'template_redirect' ) );
		add_filter( 'query_vars', array( 'Diaspora_Plugin', 'add_query_vars' ) );

		add_filter( 'rewrite_rules_array', array( 'Diaspora_Plugin', 'insert_rewrite_rules' ) );

		add_action( 'parse_request', array( 'Diaspora_Plugin', 'parse_request' ) );

		add_action( 'webfinger_user_data', array( 'Diaspora_Plugin', 'add_webfinger_discovery' ), 10, 3 );
	}

	/**
	 * Add our rewrite endpoint on plugin activation.
	 */
	public static function activate_plugin() {
		Diaspora_Plugin::add_rewrite_endpoint();
		flush_rewrite_rules();
	}

	/**
	 * Flush rewrite rules on plugin deactivation.
	 */
	public static function deactivate_plugin() {
		flush_rewrite_rules();
	}

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
	 * Add our rewrite endpoint to permalinks and pages.
	 */
	public static function add_rewrite_endpoint() {
		add_rewrite_endpoint( 'hcard', EP_AUTHORS );
	}

	public static function insert_rewrite_rules( $rules ) {
		$rules['fetch/([a-z_])/([A-Za-z0-9-_@.:])$'] = 'index.php?diaspora=fetch&type=$matches[1]&guid=$matches[2]';
		$rules['receive/users/([A-Za-z0-9-_@.:])$'] = 'index.php?diaspora=receive&type=users&guid=$matches[1]';
		$rules['receive/public$'] = 'index.php?diaspora=receive&type=public';

		return $rules;
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
		// check if it is a diaspora request or not
		if ( ! array_key_exists( 'diaspora', $wp->query_vars ) ) {
			return;
		}

		status_header( 202 );

		error_log( print_r( $_POST, true ), 1, get_option( 'admin_email' ) );
		error_log( print_r( $_GET, true ), 1, get_option( 'admin_email' ) );
		error_log( print_r( file_get_contents('php://input'), true ), 1, get_option( 'admin_email' ) );

		exit;
	}

	/**
	 * Output the embeddable HTML.
	 *
	 * @todo Is there a better / faster way?
	 */
	public static function template_redirect() {
		global $wp_query;

		if ( isset( $wp_query->query_vars['hcard'] ) && is_author() ) {
			load_template( dirname( __FILE__ ) . '/templates/author-hcard.php' );
			exit;
		}
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
