<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\AggregationField;

/**
 * Interface SingleAggregationField
 *
 * Structural description of an ES aggregation
 *
 * @package ElasticFacets\Type
 */
interface SingleAggregationField {

	/**
	 * Unique ID to identify the aggregation in the results
	 *
	 * @return string
	 */
	public function id();

	/**
	 * Field name that is used in the query
	 *
	 * @return string
	 */
	public function field();
}