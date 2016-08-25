<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;

interface Registry {

	/**
	 * @param string $id
	 * @param object $aggregation (Any aggregation type that implements AggregationExpression AND one of the AggregationParser)
	 *
	 * @throws \InvalidArgumentException                           
	 *                            
	 * @return bool
	 */
	public function add_aggregation( $id, $aggregation );

	/**
	 * @param string $id
	 * @param array  $config
	 * 
	 * @throws \InvalidArgumentException
	 *
	 * @return bool
	 */
	public function build_aggregation( $id, array $config );
	
	/**
	 * @param string $id
	 *
	 * @throws \InvalidArgumentException (If no aggregation is registered for the given ID)
	 * 
	 * @return AggregatedNumericRangesCollection|AggregatedTermsCollection
	 */
	public function get_aggregation_result( $id );
}