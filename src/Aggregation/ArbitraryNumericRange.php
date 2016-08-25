<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\AggregationField\NumericRangeAggregationField;
use ElasticFacets\AggregationField\SingleAggregationField;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\GenericAggregatedNumericRangesCollection;
use ElasticFacets\Type\GenericNumericRange;

/**
 * Class ArbitraryNumericRange
 *
 * @package ElasticFacets\Aggregation
 */
final class ArbitraryNumericRange implements NumericRanges {

	/**
	 * @var SingleAggregationField
	 */
	private $field;

	/**
	 * @param NumericRangeAggregationField $field
	 */
	public function __construct( NumericRangeAggregationField $field ) {

		$this->field = $field;
	}

	/**
	 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-range-aggregation.html
	 *
	 * @param array $query (The complete so-far built query expression)
	 *
	 * @return array Associative array that express one aggregation E.g.
	 *               [
	 *                  "aggregation_id" => [ aggregation definition ]
	 *               ]
	 */
	public function expression( array $query = [] ) {


		if ( ! $this->range_requested() ) {
			return [ ];
		}

		return [
			$this->field->id() => [
				'range' => [
					'field' => $this->field->field(),
					'ranges' => [
						[
							'from' => $this->field->min(),
							'to'   => $this->field->max()
						]
					]
				]
			]
		];
	}

	/**
	 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-range-aggregation.html
	 *
	 * @param array $response
	 *
	 * @return AggregatedNumericRangesCollection
	 */
	public function parse_response( array $response ) {

		if ( ! isset( $response[ 'aggregations' ][ $this->field->id() ] ) ) {
			// Todo: Handle missing aggregation. Something went wrong with ES or no range was requested
			return new GenericAggregatedNumericRangesCollection( [], [] );
		}

		$aggregation = $response[ 'aggregations' ][ $this->field->id() ];
		if ( ! isset( $aggregation[ 'buckets' ] ) || ! is_array( $aggregation[ 'buckets' ] ) ) {
			// Todo: Handle missing aggregation. Something went wrong with ES
			return new GenericAggregatedNumericRangesCollection( [], [] );
		}

		$ranges = [];
		$counts = [];
		foreach ( $aggregation[ 'buckets' ] as $bucket ) {
			$ranges[] = $this->parse_bucket( $bucket );
			$counts[] = $bucket[ 'doc_count' ];
		}

		return new GenericAggregatedNumericRangesCollection( $ranges, $counts );
	}

	/**
	 * @param array $bucket
	 *
	 * @return GenericNumericRange
	 */
	private function parse_bucket( array $bucket ) {

		$min = isset( $bucket[ 'from' ] )
			? (int) $bucket[ 'from' ]
			: - INF;
		$max = isset( $bucket[ 'to' ] )
			? (int) $bucket[ 'to' ]
			: INF;

		return new GenericNumericRange( $min, $max );
	}

	/**
	 * @return bool
	 */
	private function range_requested() {

		return ! is_null( $this->field->min() ) && ! is_null( $this->field->max() );
	}

}