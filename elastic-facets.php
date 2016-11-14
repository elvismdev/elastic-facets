<?php # -*- coding: utf-8 -*-

/**
 * Plugin Name: Elastic Facets
 * Description: Provide an API to Elasticsearch aggregations to build faceted search forms
 * Plugin URI:  https://github.com/inpsyde/elastic-facets/
 * Author:      Inpsyde GmbH
 * Author URI:  http://inpsyde.com/
 * Version:     dev-master
 * License:     MIT
 * Text Domain: elastic-facets
 */

namespace ElasticFacets;

use ElasticFacets\Plugin\ElasticFacetsLoader;
use GuzzleHttp\Psr7\ServerRequest;
use WP_Query;

add_action( 'muplugins_loaded', __NAMESPACE__ . '\init', 1 );

/**
 * Fetch request globals early
 *
 * @wp-hook muplugins_loaded
 */
function init() {

	$autoload = __DIR__ . '/vendor/autoload.php';
	if ( file_exists( $autoload ) && is_readable( $autoload ) ) {
		require_once $autoload;
	}

	$request = ServerRequest::fromGlobals();
	add_action(
		'parse_query',
		function( WP_Query $query ) use ( $request ) {
			static $loaded;
			if ( ! $query->is_main_query() || $loaded ) {
				return;
			}
			ElasticFacetsLoader::build_with_optional_dependencies( $request )->register_callbacks();
			$loaded = TRUE;
		},
		20
	);
}