<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Result;

use InvalidArgumentException;

/**
 * Interface ParserCollection
 *
 * @package ElasticFacets\Result
 */
interface ParserCollection {

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