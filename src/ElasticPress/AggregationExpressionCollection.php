<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\ElasticPress;

use ElasticFacets\Query\AggregationExpression;
use ElasticFacets\Query\AggregationExpressionCollection as Collection;

/**
 * Class AggregationExpressionCollection
 *
 * @package ElasticFacets\ElasticPress
 */
final class AggregationExpressionCollection implements Collection {

	/**
	 * @var AggregationExpression[]
	 */
	private $expressions = [];

	/**
	 * @param AggregationExpression[] $expressions
	 */
	public function __construct( array $expressions = [] ) {

		foreach ( $expressions as $expression ) {
			$this->push_expression( $expression );
		}
	}

	/**
	 * @wp-hook ep_formatted_args
	 *
	 * @param array $query
	 *
	 * @return array Associative array with the complete aggregation expressions
	 *               merged into the original query
	 *               [
	 *                  "aggs" => [ [ aggregation expreession], ... ]
	 *               ]
	 */
	public function append_to_query( array $query ) {

		if ( empty( $this->expressions ) ) {
			return $query;
		}

		$aggregations = [];
		foreach ( $this->expressions as $expression ) {
			$aggregations += $expression->expression( $query );
		}

		if ( ! isset( $query[ 'aggs' ] ) ) {
			$query[ 'aggs' ] = [];
		}
		$query[ 'aggs' ] += $aggregations;

		return $query;
	}

	/**
	 * @param AggregationExpression $expression
	 *
	 * @return bool
	 */
	public function push_expression( AggregationExpression $expression ) {

		if ( in_array( $expression, $this->expressions, TRUE ) ) {
			return FALSE;
		}

		$this->expressions[] = $expression;

		return TRUE;
	}

}