<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\ElasticPress\AggregationExpressionCollection;
use ElasticFacets\Result\ParserCollection;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;

final class GenericRegistry implements Registry {

	/**
	 * @var ParserCollection
	 */
	private $parser_collection;

	/**
	 * @var AggregationExpressionCollection
	 */
	private $expression_collection;
	
	
	
	/**
	 * @param string $id
	 * @param object $aggregation (Any aggregation type that implements AggregationExpression AND one of the AggregationParser)
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return bool
	 */
	public function add_aggregation( $id, $aggregation ) {
		// TODO: Implement add_aggregation() method.
	}

	/**
	 * @param string $id
	 * @param array  $config
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return bool
	 */
	public function build_aggregation( $id, array $config ) {
		// TODO: Implement build_aggregation() method.
	}

	/**
	 * @param string $id
	 *
	 * @throws \InvalidArgumentException (If no aggregation is registered for the given ID)
	 *
	 * @return AggregatedNumericRangesCollection|AggregatedTermsCollection
	 */
	public function get_aggregation_result( $id ) {
		// TODO: Implement get_aggregation_result() method.
	}

}