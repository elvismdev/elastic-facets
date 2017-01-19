<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Plugin;

use ElasticFacets\Aggregation\GenericRegistry;
use ElasticFacets\Aggregation\Registry;
use ElasticFacets\ElasticFacets;
use ElasticFacets\ElasticFacetsApi;
use ElasticFacets\ElasticPress\ResultStorageParserCollection;
use ElasticFacets\Query\AggregationExpressionCollection;
use ElasticFacets\Result\AggregationParserCollection;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ElasticFacetsLoader
 *
 * @package ElasticFacets\Plugin
 */
final class ElasticFacetsLoader implements PluginLoader {

	/**
	 * @var ServerRequestInterface
	 */
	private $request;

	/**
	 * @var AggregationExpressionCollection
	 */
	private $expressions;

	/**
	 * @var ResultStorageParserCollection
	 */
	private $parsers;

	/**
	 * @var GenericRegistry
	 */
	private $registry;

	/**
	 * @var ElasticFacetsApi
	 */
	private $elastic_facets;

	/**
	 * @param ServerRequestInterface          $request
	 * @param AggregationExpressionCollection $expressions
	 * @param AggregationParserCollection     $parsers
	 * @param Registry                        $registry
	 */
	public function __construct(
		ServerRequestInterface $request,
		AggregationExpressionCollection $expressions,
		AggregationParserCollection $parsers,
		Registry $registry
	) {

		$this->request     = $request;
		$this->expressions = $expressions;
		$this->parsers     = $parsers;
		$this->registry    = $registry;

		/* Todo: Refactor constructor signature to inject API directly */
		$this->elastic_facets = new ElasticFacets(
			$this->expressions,
			$this->parsers,
			$this->parsers
		);
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
				 * @param Registry               $this ->registry
				 * @param ServerRequestInterface $this ->request
				 */
				do_action( 'elastic_facets.register_aggregation', $this->registry, $this->request, $wp_query );
			},
			5
		);

		/**
		 * global getter for the main query registry object to receive the results
		 */
		add_filter(
			'elastic_facets.get_registry',
			function () {

				return $this->registry;
			}
		);

	}

	/**
	 * @param ServerRequestInterface $request
	 *
	 * @return ElasticFacetsLoader
	 */
	public static function build_from_request( ServerRequestInterface $request ) {

		$expressions           = new \ElasticFacets\ElasticPress\AggregationExpressionCollection;
		$result_parser_storage = new ResultStorageParserCollection;
		$registry = new GenericRegistry(
			$expressions,
			$result_parser_storage,
			$result_parser_storage
		);

		return new ElasticFacetsLoader( $request, $expressions, $result_parser_storage, $registry );
	}
}