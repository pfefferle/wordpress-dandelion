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
	 * Add the 'photos' query variable so Wordpress
	 * won't mangle it.
	 */
	public static function add_query_vars( $vars ) {
		$vars[] = 'hcard';
		return $vars;
	}

	/**
	 * Add our rewrite endpoint to permalinks and pages.
	 */
	public static function add_rewrite_endpoint() {
		add_rewrite_endpoint( 'hcard', EP_AUTHORS );
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
			'rel' => 'http://microformats.org/profile/hcard',
			'type' => 'text/html',
			'href' => $hcard_url,
		);

		$array['links'][] = array(
			'rel' => 'http://joindiaspora.com/seed_location',
			'type' => 'text/html',
			'href' => site_url( '/' ),
		);

		$array['links'][] = array(
			'rel' => 'http://joindiaspora.com/guid',
			'type' => 'text/html',
			'href' => get_author_posts_url( $user->ID, $user->nicename ),
		);

		return $array;
	}
}
