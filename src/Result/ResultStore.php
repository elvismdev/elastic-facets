<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Result;

use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;
use InvalidArgumentException;

/**
 * Interface ResultStore
 *
 * @package ElasticFacets\Result
 */
interface ResultStore {

	/**
	 * @param string $id
	 *
	 * @throws InvalidArgumentException if there's no aggregation is registered for this ID
	 *
	 * @return AggregatedTermsCollection
	 */
	public function terms_result( $id );

	/**
	 * @param string $id
	 *
	 * @throws InvalidArgumentException if there's no aggregation is registered for this ID
	 *
	 * @return AggregatedNumericRangesCollection
	 */
	public function numeric_ranges_result( $id );
}