<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Result;

use ElasticFacets\Type\AggregatedTermsCollection;

/**
 * Interface ParseTermsAggregation
 *
 * Parses term aggregation results from the ES response
 *
 * @package ElasticFacets\Result
 */
interface ParseTermsAggregation extends ParseAggregation {

	/**
	 * @param array $response Contains a key 'aggregations' with all aggregations:
	 *                        [ "aggregations => [ "aggregation_name" => [ ... ]   ] ]
	 *
	 * @return AggregatedTermsCollection
	 */
	public function parse_response( array $response );
}