<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Query;

/**
 * Class AggregationExpression
 *
 * Phrases (express) the aggregation definition of a ES query
 *
 *
 * @package ElasticFacets\Query
 */
interface AggregationExpression {

	/**
	 * @param array $query (Optional, a complete query for
	 *
	 * @return array Associative array that express one aggregation E.g.
	 *               [
	 *                  "aggregation_id" => [ aggregation definition ]
	 *               ]
	 */
	public function expression( array $query = [] );
}