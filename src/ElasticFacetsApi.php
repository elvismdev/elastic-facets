<?php # -*- coding: utf-8 -*-

namespace ElasticFacets;

use ElasticFacets\Aggregation\NumericRanges;
use ElasticFacets\Aggregation\Terms;
use ElasticFacets\Type\AggregatesCollection;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface ElasticFacetsApi
 *
 * @package ElasticFacets
 */
interface ElasticFacetsApi {

	/**
	 * @param string $id
	 * @param Terms|NumericRanges $expression
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return ElasticFacetsApi
	 */
	public function add_aggregation( $id, $expression );

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
	 * @param $id
	 *
	 * @return AggregatesCollection|null
	 */
	public function get_aggregates( $id );

	/**
	 * @return ServerRequestInterface
	 */
	public static function get_request();
}