<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\AggregationField\NumericRangeAggregationField;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;

/**
 * Class ArbitraryNumericRangeTest
 *
 * @package ElasticFacets\Aggregation
 */
class ArbitraryNumericRangeTest extends BrainMonkeyWpTestCase {

	/**
	 * @see NumericRange::expression()
	 */
	public function test_expression() {

		$field_mock = $this->get_field_mock(
			'my_field_aggregation',
			'meta._regular_price.value',
			3,
			10
		);

		$expected_expression = [
			'my_field_aggregation' => [
				'range' => [
					'field'  => 'meta._regular_price.value',
					'ranges' => [
						[
							'from' => 3,
							'to'   => 10
						]
					]
				]
			]
		];

		$testee = new ArbitraryNumericRange( $field_mock );

		$this->assertSame(
			$expected_expression,
			$testee->expression()
		);
	}

	/**
	 * @see NumericRange::expression()
	 */
	public function test_expression_no_range_requested() {

		$field_mock = $this->get_field_mock(
			'my_field_aggregation',
			'meta._regular_price.long'
		);

		$testee = new ArbitraryNumericRange( $field_mock );

		$this->assertSame(
			[ ],
			$testee->expression()
		);
	}

	/**
	 * Todo: Test error handling and bad arguments for expression()
	 */
	public function test_expression_errors() {

		$this->markTestIncomplete( 'Under construction…' );
	}

	/**
	 * @dataProvider parse_response_test_data
	 * @see          NumericRange::parse_response()
	 *
	 * @param array $response
	 * @param array $expected
	 */
	public function test_parse_response( array $response, array $expected ) {

		$field_mock = $this->get_field_mock( $expected[ 'id' ], '' );
		$testee     = new ArbitraryNumericRange( $field_mock );

		$collection = $testee->parse_response( $response );
		$this->assertCount(
			$expected[ 'number_ranges' ],
			$collection->ranges()
		);

		foreach ( $collection->ranges() as $index => $range ) {
			$this->assertSame(
				$expected[ 'ranges' ][ $index ][ 'min' ],
				$range->min()
			);
			$this->assertSame(
				$expected[ 'ranges' ][ $index ][ 'max' ],
				$range->max()
			);
			$this->assertSame(
				$expected[ 'ranges' ][ $index ][ 'count' ],
				$collection->count( $range )
			);
		}
	}

	/**
	 * Todo: Test error handling and bad arguments for parse_response()
	 */
	public function test_parse_response_errors() {

		$this->markTestIncomplete( 'Under construction…' );
	}

	/**
	 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-range-aggregation.html
	 * @see  test_parse_response
	 *
	 * @return array
	 */
	public function parse_response_test_data() {

		return [
			[
				// 1. argument $response
				[
					"aggregations" => [
						"my_aggregation" => [
							"buckets" => [
								[
									"from"      => 10,
									"to"        => 50,
									"doc_count" => 24
								]
							]
						]
					]
				],
				// 2. argument $options
				[
					'id'            => 'my_aggregation',
					'number_ranges' => 1,
					'ranges'        => [
						[
							'min'   => 10,
							'max'   => 50,
							'count' => 24
						]
					]
				]
			]
		];
	}

	/**
	 * @param string   $id
	 * @param string   $field
	 * @param int|null $min
	 * @param int|null $max
	 *
	 * @return NumericRangeAggregationField
	 */
	private function get_field_mock( $id, $field, $min = NULL, $max = NULL ) {

		$field_mock = Mockery::mock( NumericRangeAggregationField::class );
		$field_mock->shouldReceive( 'id' )
			->andReturn( $id );
		$field_mock->shouldReceive( 'field' )
			->andReturn( $field );
		$field_mock->shouldReceive( 'min' )
			->andReturn( $min );
		$field_mock->shouldReceive( 'max' )
			->andReturn( $max );

		return $field_mock;
	}
}
