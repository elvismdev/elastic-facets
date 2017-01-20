<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

/**
 * Interface AggregatedTermsCollection
 *
 * A collection of terms
 *
 * @package ElasticFacets\Result
 */
interface AggregatedTermsCollection extends AggregatesCollection {

	/**
	 * Number of matching documents for the given term
	 *
	 * @param Term $term
	 *
	 * @return int
	 */
	public function count( Term $term );

	/**
	 * List of matched terms
	 *
	 * @return Term[]
	 */
	public function terms();
}