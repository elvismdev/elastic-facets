<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Result;

use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;
use InvalidArgumentException;

/**
 * Interface ResultStore
 *
 * Interface to the results of various aggregations by their IDs
 *
 * @package ElasticFacets\Result
 */
interface ResultStore {

	/**
	 * @param string $id
	 *
	 * @return AggregatedTermsCollection|null (Returns null if no aggregation was found, which probably means ES service is down)
	 */
	public function terms_result( $id );

	/**
	 * @param string $id
	 *
	 * @return AggregatedNumericRangesCollection|null (Returns null if no aggregation was found, which probably means ES service is down)
	 */
	public function numeric_ranges_result( $id );
}