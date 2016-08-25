<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\AggregationField;

use ElasticFacets\Type\NumericRange;

/**
 * Interface NumericRangeAggregationField
 *
 * Field for a range aggregation
 * 
 * @package ElasticFacets\Type
 */
interface NumericRangeAggregationField extends SingleAggregationField {

	/**
	 * Returns NULL if no value is specified
	 * 
	 * @return int|float|NULL
	 */
	public function min();

	/**
	 * Returns NULL if no value is specified
	 *
	 * @return int|float|NULL
	 */
	public function max();
}