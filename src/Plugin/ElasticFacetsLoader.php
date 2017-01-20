<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Plugin;

use ElasticFacets\ElasticFacets;
use ElasticFacets\ElasticFacetsApi;
use ElasticFacets\ElasticPress\ResultStorageParserCollection;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ElasticFacetsLoader
 *
 * @package ElasticFacets\Plugin
 */
final class ElasticFacetsLoader implements PluginLoader {

	/**
	 * @var ElasticFacetsApi
	 */
	private $elastic_facets;

	/**
	 * @param ElasticFacetsApi $elastic_facets
	 */
	public function __construct( ElasticFacetsApi $elastic_facets ) {

		$this->elastic_facets = $elastic_facets;
	}

	/**
	 * @wp-hook wp_loaded
	 *
	 * @return void
	 */
	public function register_callbacks() {

		add_filter(
			'ep_formatted_args',
			function( $ep_args, $query_args ) {

				if ( ! isset( $query_args[ 'elastic_facets' ] ) ) {
					return $ep_args;
				}

				$api = $query_args[ 'elastic_facets' ];
				$api instanceof ElasticFacetsApi and $ep_args = $api
					->append_to_query( $ep_args );

				return $ep_args;
			},
			10,
			2
		);
		add_action(
			'ep_retrieve_aggregations',
			function( $aggregations, $ep_args, $scope, $query_args ) {

				if ( ! isset( $query_args[ 'elastic_facets' ] ) ) {
					return;
				}

				$api = $query_args[ 'elastic_facets' ];
				$api instanceof ElasticFacetsApi and $api->parse_response( $aggregations );
			},
			10,
			4
		);

		/**
		 * Aggregations for the main query
		 */
		add_action(
			'pre_get_posts',
			function( \WP_Query $wp_query ) {

				if( ! $wp_query->is_main_query() ) {
					return;
				}

				$wp_query->set( 'elastic_facets', $this->elastic_facets );

				/**
				 * Use this hook to register aggregations to the main query
				 *
				 * @param Registry
				 * @param ServerRequestInterface
				 * @param \WP_Query
				 */
				do_action(
					'elastic_facets.register_aggregation',
					$this->elastic_facets,
					ElasticFacets::get_request(),
					$wp_query
				);
			},
			5
		);

		/**
		 * global getter for the main query registry object to receive the results
		 */
		add_filter(
			'elastic_facets.get_registry',
			function () {

				return $this->elastic_facets;
			}
		);

	}

	/**
	 * @param ServerRequestInterface $request
	 *
	 * @return ElasticFacetsLoader
	 */
	public static function build_from_request( ServerRequestInterface $request ) {

		ElasticFacets::set_request( $request );
		$expressions           = new \ElasticFacets\ElasticPress\AggregationExpressionCollection;
		$result_parser_storage = new ResultStorageParserCollection;
		$elastic_facets        = new ElasticFacets( $expressions, $result_parser_storage, $result_parser_storage );

		return new ElasticFacetsLoader( $elastic_facets );
	}
}