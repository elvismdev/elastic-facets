<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Query;

/**
 * Interface AggregationExpressionCollection
 *
 * Collects a set of AggregationExpressions to append it on a given query array.
 *
 * @package ElasticFacets\Query
 */
interface AggregationExpressionCollection {

	/**
	 * @param AggregationExpression $expression
	 *
	 * @return bool
	 */
	public function push_expression( AggregationExpression $expression );

	/**
	 * @param array $query
	 *
	 * @return array Associative array with the complete aggregation expressions
	 *               merged into the original query
	 *               [
	 *                  "aggs" => [ [ aggregation expreession], ... ]
	 *               ]
	 */
	public function append_to_query( array $query );
}