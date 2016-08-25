<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\AggregationField\SingleAggregationField;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;

class AbsoluteNumericRangeTest extends BrainMonkeyWpTestCase {

	/**
	 * @see AbsoluteNumericRange::expression()
	 */
	public function test_expression() {

		$field_mock = $this->get_field_mock( 'total_price_range', 'meta._regular_price.long' );
		$expected_expression = [
			'total_price_range_min' => [
				'min' => [
					'field' => 'meta._regular_price.long'
				]
			],
			'total_price_range_max' => [
				'max' => [
					'field' => 'meta._regular_price.long'
				]
			]
		];

		$testee = new AbsoluteNumericRange( $field_mock );

		$this->assertSame(
			$expected_expression,
			$testee->expression()
		);
	}

	/**
	 * @see AbsoluteNumericRange::expression()
	 */
	public function test_parse_response() {

		$field_mock = $this->get_field_mock( 'total_price_range', 'meta._regular_price.long' );
		$response  = [
			'aggregations' => [
				'total_price_range_min' => [
					'value' => '10.0'
				],
				'total_price_range_max' => [
					'value' => '100.0'
				]
			]
		];
		
		$testee = new AbsoluteNumericRange( $field_mock );
		
		$collection = $testee->parse_response( $response );
		
		$this->assertCount(
			1,
			$collection->ranges()
		);
		$this->assertSame(
			(float) 10,
			$collection->ranges()[ 0 ]->min(),
			"Failed for min()"
		);
		$this->assertSame(
			(float) 100,
			$collection->ranges()[ 0 ]->max(),
			"Failed for max()"
		);
	
	}

	/**
	 * @param $id
	 * @param $field
	 *
	 * @return SingleAggregationField
	 */
	private function get_field_mock( $id, $field ) {

		$field_mock = Mockery::mock( SingleAggregationField::class );
		$field_mock->shouldReceive( 'id' )
			->andReturn( $id );
		$field_mock->shouldReceive( 'field' )
			->andReturn( $field );

		return $field_mock;
	}
}
