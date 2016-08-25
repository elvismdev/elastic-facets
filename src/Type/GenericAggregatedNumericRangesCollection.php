<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

/**
 * Class GenericAggregatedNumericRangesCollection
 *
 * @package ElasticFacets\Type
 */
final class GenericAggregatedNumericRangesCollection implements AggregatedNumericRangesCollection {

	/**
	 * @var NumericRange[]
	 */
	private $ranges = [];

	/**
	 * @var array
	 */
	private $counts = [];

	/**
	 * @param NumericRange[] $ranges
	 * @param int[] $counts
	 */
	public function __construct( array $ranges, array $counts ) {

		$this->ranges = $this->sanitize_ranges( $ranges );
		$this->counts = $this->sanitize_counts( $counts, $this->ranges );
	}

	/**
	 * Number of matching documents
	 *
	 * @param NumericRange $range (The range to count documents for)
	 *
	 * @return int (Number of documents for the given range)
	 */
	public function count( NumericRange $range ) {

		$id = $this->range_id( $range );

		return isset( $this->counts[ $id ] )
			? $this->counts[ $id ]
			: 0;
	}

	/**
	 * @return NumericRange
	 */
	public function ranges() {

		return $this->ranges;
	}

	/**
	 * @param array $ranges
	 *
	 * @return NumericRange[]
	 */
	private function sanitize_ranges( array $ranges ) {

		$ranges = array_filter(
			$ranges,
			function( $el ) {
				return $el instanceof NumericRange;
			}
		);

		return array_values( $ranges );
	}

	/**
	 * @param array $counts
	 * @param NumericRange[] $ranges
	 *
	 * @return array
	 */
	private function sanitize_counts( array $counts, array $ranges ) {

		$counts = array_values( $counts );
		$sanitize_counts = [];
		foreach ( $ranges as $key => $range ) {
			$sanitize_counts[ $this->range_id( $range ) ] = isset( $counts[ $key ] )
				? (int) $counts[ $key ]
				: 0;
		}

		return $sanitize_counts;
	}

	/**
	 * @param NumericRange $range
	 *
	 * @return string
	 */
	private function range_id( NumericRange $range ) {

		return spl_object_hash( $range );
	}
}