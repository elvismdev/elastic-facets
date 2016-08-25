<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Result;

use InvalidArgumentException;

/**
 * Interface AggregationParserCollection
 *
 * @package ElasticFacets\Result
 */
interface AggregationParserCollection {

	/**
	 * @param ParseNumericRangesAggregation|ParseTermsAggregation $parser
	 * @param string                                              $id
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return bool
	 */
	public function push_parser( $parser, $id );

	/**
	 * @param array $response Contains a key 'aggregations' with all aggregations:
	 *                        [ "aggregations => [ "aggregation_name" => [ ... ]   ] ]
	 *
	 * @return void 
	 */
	public function parse_response( array $response );
}