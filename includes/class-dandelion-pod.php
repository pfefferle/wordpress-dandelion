<?php

class Dandelion_Pod {

	public static function register_routes() {
		register_rest_route(
			'diaspora/1.0', '/receive/users/<>'
		);

		register_rest_route(
			'diaspora/1.0', '/people/<>/stream'
		);
	}
}
