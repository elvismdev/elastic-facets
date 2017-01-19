<?php # -*- coding: utf-8 -*-

namespace ElasticFacets;

use ElasticFacets\Query\AggregationExpression;
use ElasticFacets\Result\ResultStore;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ElasticFacetsApi
 *
 * @package ElasticFacets
 */
interface ElasticFacetsApi extends ResultStore {

	/**
	 * @param AggregationExpression $expression
	 *
	 * @return ElasticFacetsApi
	 */
	public function push_expression( AggregationExpression $expression );

	/**
	 * @param array $query
	 *
	 * @return array
	 */
	public function append_to_query( array $query );

	/**
	 * @param array $response
	 */
	public function parse_response( array $response );

	/**
	 * @return ServerRequestInterface
	 */
	public static function get_request();
}