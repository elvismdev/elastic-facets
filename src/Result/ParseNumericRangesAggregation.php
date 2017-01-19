<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Result;

use ElasticFacets\Type\AggregatedNumericRangesCollection;

/**
 * Interface ParseNumericRangesAggregation
 *
 * Parses range aggregation and any similar aggregation types that results in a numeric value pair.
 *
 * @package ElasticFacets\Result
 */
interface ParseNumericRangesAggregation {

	/**
	 * @param array $response Contains a key 'aggregations' with all aggregations:
	 *                        [ "aggregations => [ "aggregation_name" => [ ... ]   ] ]
	 *
	 * @return AggregatedNumericRangesCollection
	 */
	public function parse_response( array $response );
}