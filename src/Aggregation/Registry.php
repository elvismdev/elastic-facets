<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;
use InvalidArgumentException;

/**
 * Interface Registry
 *
 * Registry for various aggregation types with interface to the results of each aggregation.
 *
 * Todo: Consider to deprecate this API in favor of ElasticFacetsApi
 *
 * @package ElasticFacets\Aggregation
 */
interface Registry {

	/**
	 * ID needs to be unique across all aggregation types
	 *
	 * @param string $id
	 * @param Terms  $aggregation
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return bool
	 */
	public function add_terms_aggregation( $id, Terms $aggregation );

	/**
	 * ID needs to be unique across all aggregation types
	 *
	 * @param string        $id
	 * @param NumericRanges $aggregation
	 *
	 * @return mixed
	 */
	public function add_numeric_ranges_aggregation( $id, NumericRanges $aggregation );

	/**
	 * @param string $id
	 *
	 * @throws InvalidArgumentException (If no aggregation is registered for the given ID)
	 *
	 * @return AggregatedTermsCollection|NULL
	 */
	public function get_terms_result( $id );

	/**
	 * @param string $id
	 *
	 * @throws InvalidArgumentException (If no aggregation is registered for the given ID)
	 *
	 * @return AggregatedNumericRangesCollection|NULL
	 */
	public function get_numeric_ranges_result( $id );
}