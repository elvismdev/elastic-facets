<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\AggregationField\SingleAggregationField;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\GenericAggregatedNumericRangesCollection;
use ElasticFacets\Type\GenericNumericRange;

/**
 * Class AbsoluteNumericRange
 *
 * Combines min and max aggregation
 *
 * @package ElasticFacets\Aggregation
 */
final class AbsoluteNumericRange implements NumericRange {

	/**
	 * @var SingleAggregationField
	 */
	private $field;

	/**
	 * @param SingleAggregationField $field
	 */
	public function __construct( SingleAggregationField $field ) {

		$this->field = $field;
	}

	/**
	 * @param array $query (Optional, a complete query for
	 *
	 * @return array Associative array that express one aggregation E.g.
	 *               [
	 *                  "aggregation_id" => [ aggregation definition ]
	 *               ]
	 */
	public function expression( array $query = [ ] ) {

		return [
			"{$this->field->id()}_min" => [
				'min' => [
					'field' => $this->field->field()
				]
			],
			"{$this->field->id()}_max" => [
				'max' => [
					'field' => $this->field->field()
				]
			]
		];
	}

	/**
	 * @param array $response
	 *
	 * @return AggregatedNumericRangesCollection
	 */
	public function parse_response( array $response ) {

		if ( ! isset( $response[ 'aggregations' ][ "{$this->field->id()}_min" ] ) ) {
			//Todo: Error handling something wrong with ES?
			return new GenericAggregatedNumericRangesCollection( [], [] );
		}

		if ( ! isset( $response[ 'aggregations' ][ "{$this->field->id()}_max" ] ) ) {
			//Todo: Error handling something wrong with ES?
			return new GenericAggregatedNumericRangesCollection( [], [] );
		}

		$min_response = $response[ 'aggregations' ][ "{$this->field->id()}_min" ];
		$max_response = $response[ 'aggregations' ][ "{$this->field->id()}_max" ];

		$range = new GenericNumericRange(
			isset( $min_response[ 'value' ] ) ? $min_response[ 'value' ] : - INF,
			isset( $max_response[ 'value' ] ) ? $max_response[ 'value' ] : INF
		);

		return new GenericAggregatedNumericRangesCollection( [ $range ], [ 0 ] );
	}

}