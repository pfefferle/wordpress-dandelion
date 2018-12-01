<?php
/**
 * Plugin Name: Diaspora*
 * Plugin URI: https://github.com/pfefferle/wordpress-dandelion
 * Description: An extension to the OStatus plugin, to also support parts of the Diaspora Federated Protocol.
 * Author: Matthias Pfefferle
 * Author URI: http://notiz.blog/
 * License: MIT
 * License URI: http://opensource.org/licenses/MIT
 * Version: 1.0.0
 * Text Domain: dandelion
 * Domain Path: /languages
 */

function dandelion_init() {
	require_once dirname( __FILE__ ) . '/includes/class-dandelion.php';
	add_filter( 'template_include', array( 'Dandelion', 'render_hcard_template' ) );
	add_filter( 'query_vars', array( 'Dandelion', 'add_query_vars' ) );
	add_action( 'parse_request', array( 'Dandelion', 'parse_request' ) );
	add_action( 'webfinger_user_data', array( 'Dandelion', 'add_webfinger_discovery' ), 10, 3 );
}
add_action( 'plugins_loaded', 'dandelion_init' );

/**
 * Add our rewrite endpoint on plugin activation.
 */
function dandelion_activate_plugin() {
	dandelion_add_rewrite_endpoint();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'dandelion_activate_plugin' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );


/**
 * Add our rewrite endpoint to permalinks and pages.
 */
function dandelion_add_rewrite_endpoint() {
	add_rewrite_endpoint( 'hcard', EP_AUTHORS );

	//add_rewrite_rule( '^fetch/([a-z_]*)/([A-Za-z0-9-_@.:]*)', 'index.php?diaspora=fetch&type=$matches[1]&guid=$matches[2]', 'top' );
	//add_rewrite_rule( '^receive/users/([A-Za-z0-9-_@.:]*)', 'index.php?diaspora=receive&type=users&guid=$matches[1]', 'top' );
	//add_rewrite_rule( '^receive/public', 'index.php?diaspora=receive&type=public', 'top' );
}
add_action( 'init', 'dandelion_add_rewrite_endpoint' );
