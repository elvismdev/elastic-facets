<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

/**
 * Interface AggregatedNumericRangesCollection
 *
 * @package ElasticFacets\Result
 */
interface AggregatedNumericRangesCollection {

	/**
	 * Number of matching documents
	 *
	 * @param NumericRange $range (The range to count documents for)
	 *                            
	 * @return int (Number of documents for the given range)
	 */
	public function count( NumericRange $range );

	/**
	 * @return NumericRange[]
	 */
	public function ranges();

}