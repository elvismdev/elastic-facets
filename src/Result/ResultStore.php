<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Result;

use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;
use ElasticFacets\Type\AggregatesCollection;
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
	 * Todo: Consider to deprecate this method and use result( $id ) instead
	 *
	 * @param string $id
	 *
	 * @return AggregatedTermsCollection|null (Returns null if no aggregation was found, which probably means ES service is down)
	 */
	public function terms_result( $id );

	/**
	 * Todo: Consider to deprecate this method and use result( $id ) instead
	 *
	 * @param string $id
	 *
	 * @return AggregatedNumericRangesCollection|null (Returns null if no aggregation was found, which probably means ES service is down)
	 */
	public function numeric_ranges_result( $id );

	/**
	 * @param $id
	 *
	 * @return AggregatesCollection|null
	 */
	public function result( $id );
}