<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width">
		<?php
		if ( function_exists( 'wp_no_robots' ) ) {
			wp_no_robots();
		}
		?>
		<title><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></title>
		<style type="text/css">
			html {
				background: #f1f1f1;
			}
			body {
				background: #fff;
				color: #444;
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
				margin: 2em auto;
				padding: 1em 2em;
				max-width: 700px;
				-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.13);
				box-shadow: 0 1px 3px rgba(0,0,0,0.13);
			}
			h1 {
				border-bottom: 1px solid #dadada;
				clear: both;
				color: #666;
				font-size: 24px;
				margin: 30px 0 0 0;
				padding: 0;
				padding-bottom: 7px;
			}
			#webmention-endpint-page {
				margin-top: 50px;
			}
			#webmention-endpint-page p {
				font-size: 14px;
				line-height: 1.5;
				margin: 25px 0 20px;
			}
			#webmention-endpint-page code {
				font-family: Consolas, Monaco, monospace;
			}
			ul li {
				margin-bottom: 10px;
				font-size: 14px ;
			}
			a {
				color: #0073aa;
			}
			a:hover,
			a:active {
				color: #00a0d2;
			}
			a:focus {
				color: #124964;
				-webkit-box-shadow:
					0 0 0 1px #5b9dd9,
					0 0 2px 1px rgba(30, 140, 190, .8);
				box-shadow:
					0 0 0 1px #5b9dd9,
					0 0 2px 1px rgba(30, 140, 190, .8);
				outline: none;
			}
			.button {
				background: #f7f7f7;
				border: 1px solid #ccc;
				color: #555;
				display: inline-block;
				text-decoration: none;
				font-size: 13px;
				line-height: 26px;
				height: 28px;
				margin: 0;
				padding: 0 10px 1px;
				cursor: pointer;
				-webkit-border-radius: 3px;
				-webkit-appearance: none;
				border-radius: 3px;
				white-space: nowrap;
				-webkit-box-sizing: border-box;
				-moz-box-sizing:    border-box;
				box-sizing:         border-box;
				-webkit-box-shadow: 0 1px 0 #ccc;
				box-shadow: 0 1px 0 #ccc;
				vertical-align: top;
			}
			.button.button-large {
				height: 30px;
				line-height: 28px;
				padding: 0 12px 2px;
			}
			.button:hover,
			.button:focus {
				background: #fafafa;
				border-color: #999;
				color: #23282d;
			}
			.button:focus  {
				border-color: #5b9dd9;
				-webkit-box-shadow: 0 0 3px rgba( 0, 115, 170, .8 );
				box-shadow: 0 0 3px rgba( 0, 115, 170, .8 );
				outline: none;
			}
			.button:active {
				background: #eee;
				border-color: #999;
				-webkit-box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
				box-shadow: inset 0 2px 5px -3px rgba( 0, 0, 0, 0.5 );
				-webkit-transform: translateY(1px);
				-ms-transform: translateY(1px);
				transform: translateY(1px);
			}
			<?php
			if ( 'rtl' == $text_direction ) {
				echo 'body { font-family: Tahoma, Arial; }';
			}
			?>
		</style>
	</head>
	<body>
		<div id="content">
			<h1><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></h1>

			<div id="content_inner" class="entity_profile vcard author">

				<h2>User profile</h2>

				<dl class="entity_uid">
					<dt><?php esc_html_e( 'Uid', 'diaspora' ); ?></dt>
					<dd>
						<span class="uid"><?php echo md5( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'nicename' ) ) ); ?></span>
					</dd>
				</dl>

				<dl class="entity_full_name">
					<dt>Full_name</dt>
					<dd>
						<span class="fn"><?php the_author_meta( 'display_name' ); ?></span>
					</dd>
				</dl>
				<dl class="entity_searchable">
					<dt>Searchable</dt>
					<dd>
						<span class="searchable">true</span>
					</dd>
				</dl>
				<?php if ( function_exists( 'salmon_get_public_key' ) ) : ?>
				<dl class="entity_key">
					<dt>Key</dt>
					<dd>
						<pre class="key"><?php echo salmon_get_public_key( get_the_author_meta( 'ID' ) ); ?></pre>
					</dd>
				</dl>
				<?php endif; ?>
				<?php if ( get_the_author_meta( 'first_name' ) ) : ?>
				<dl class="entity_first_name">
					<dt>First_name</dt>
					<dd>
						<span class="given_name"><?php the_author_meta( 'first_name' ); ?></span>
					</dd>
				</dl>
			<?php endif; ?>
				<?php if ( get_the_author_meta( 'last_name' ) ) : ?>
				<dl class="entity_family_name">
					<dt>Family_name</dt>
					<dd>
						<span class="family_name"><?php the_author_meta( 'last_name' ); ?></span>
					</dd>
				</dl>
			<?php endif; ?>
				<dl class="entity_photo">
					<dt>Photo</dt>
					<dd>
						<img class="photo avatar" width="300" height="300" src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => 300 ) ) ); ?>" />
					</dd>
				</dl>
				<dl class="entity_photo_medium">
					<dt>Photo_medium</dt>
					<dd>
						<img class="photo avatar" width="100" height="100" src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => 100 ) ) ); ?>" />
					</dd>
				</dl>
				<dl class="entity_photo_small">
					<dt>Photo_small</dt>
					<dd>
						<img class="photo avatar" width="50" height="50" src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'ID' ), array( 'size' => 50 ) ) ); ?>" />
					</dd>
				</dl>
			</div>
		</div>
	</body>
</html>
