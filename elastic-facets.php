<?php # -*- coding: utf-8 -*-

/**
 * Plugin Name: Elastic Facets
 * Description: Provide an API to Elasticsearch aggregations to build faceted search forms
 * Plugin URI:  https://github.com/inpsyde/elastic-facets/
 * Author:      Inpsyde GmbH
 * Author URI:  http://inpsyde.com/
 * Version:     1.0.0-alpha1
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
	is_readable( $autoload ) and require_once $autoload;


	$request = ServerRequest::fromGlobals();
	add_action(
		'parse_query',
		function( WP_Query $query ) use ( $request ) {
			static $loaded;
			if ( ! $query->is_main_query() || $loaded ) {
				return;
			}
			ElasticFacetsLoader::build_from_request( $request )->register_callbacks();
			$loaded = TRUE;
		},
		20
	);
}