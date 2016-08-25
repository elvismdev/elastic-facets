<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\ElasticPress;

use ElasticFacets\Query\AggregationExpression;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;

class AggregationExpressionCollectionTest extends BrainMonkeyWpTestCase {

	/**
	 * @see AggregationExpressionCollection::append_to_query()
	 */
	public function test_append_to_query_single_expression() {

		$expression_mock = $this->get_expression_mock( [ 'some_aggregation' => [ 'whatever' ] ] );
		$expected_query  = [
			'aggs' => [
				'some_aggregation' => [ 'whatever' ]
			]
		];

		$testee = new AggregationExpressionCollection( [ $expression_mock ] );
		$this->assertSame(
			$expected_query,
			$testee->append_to_query( [ ] )
		);
	}

	/**
	 * @see AggregationExpressionCollection::append_to_query()
	 */
	public function test_append_to_query_single_expression_with_query() {

		$expression_mock = $this->get_expression_mock( [ 'some_aggregation' => [ 'whatever' ] ] );
		$expected_query  = [
			'query' => [
				'match_all' => [ '*' ]
			],
			'aggs'  => [
				'some_aggregation' => [ 'whatever' ]
			]
		];

		$testee = new AggregationExpressionCollection( [ $expression_mock ] );
		$this->assertSame(
			$expected_query,
			$testee->append_to_query( [ 'query' => [ 'match_all' => [ '*' ] ] ] )
		);
	}
	
	
	/**
	 * @see AggregationExpressionCollection::append_to_query()
	 */
	public function test_append_to_query_multiple_aggregations() {

		$expression_mocks = [
			$this->get_expression_mock( [ 'some_aggregation' => [ 'whatever' ] ] ),
			$this->get_expression_mock( [ 'another_aggregation' => [ 'foo' => [ 'bar' ] ] ] ),
			$this->get_expression_mock( [ 'one_more_aggregation' => [ 'bar' => 'bazz' ] ] )
		];
		$expected_query  = [
			'query' => [
				'match_all' => [ '*' ]
			],
			'aggs'  => [
				'some_aggregation' => [ 'whatever' ],
				'another_aggregation' => [ 'foo' => [ 'bar' ] ],
				'one_more_aggregation' => [ 'bar' => 'bazz' ]
			]
		];

		$testee = new AggregationExpressionCollection( $expression_mocks );
		$this->assertSame(
			$expected_query,
			$testee->append_to_query( [ 'query' => [ 'match_all' => [ '*' ] ] ] )
		);
	}
	
	/**
	 * @see AggregationExpressionCollection::append_to_query()
	 */
	public function test_append_to_query_multiple_pushed_aggregations() {

		$expression_mocks = [
			$this->get_expression_mock( [ 'some_aggregation' => [ 'whatever' ] ] ),
			$this->get_expression_mock( [ 'another_aggregation' => [ 'foo' => [ 'bar' ] ] ] ),
			$this->get_expression_mock( [ 'one_more_aggregation' => [ 'bar' => 'bazz' ] ] )
		];
		$expected_query  = [
			'query' => [
				'match_all' => [ '*' ]
			],
			'aggs'  => [
				'some_aggregation' => [ 'whatever' ],
				'another_aggregation' => [ 'foo' => [ 'bar' ] ],
				'one_more_aggregation' => [ 'bar' => 'bazz' ]
			]
		];

		$testee = new AggregationExpressionCollection();
		foreach ( $expression_mocks as $expression_mock ) {
			$testee->push_expression( $expression_mock );
		}
		$this->assertSame(
			$expected_query,
			$testee->append_to_query( [ 'query' => [ 'match_all' => [ '*' ] ] ] )
		);
	}	
	/**
	 * @see AggregationExpressionCollection::append_to_query()
	 */
	public function test_append_to_query_multiple_aggregations_with_existing_aggregation() {

		$expression_mocks = [
			$this->get_expression_mock( [ 'some_aggregation' => [ 'whatever' ] ] ),
			$this->get_expression_mock( [ 'another_aggregation' => [ 'foo' => [ 'bar' ] ] ] ),
			$this->get_expression_mock( [ 'one_more_aggregation' => [ 'bar' => 'bazz' ] ] )
		];
		$query = [
			'query' => [ 'match_all' => [ '*' ] ],
			'aggs' => [
				'existing_aggregation' => [ 'lorem' => 'ipsum' ],
				'another_existing_aggr' => [ 
					'range' => [
						'ranges' => [
							[ 'from' => 10, 'to' => 100 ]
						]
					]
				]
			]
		];
		$expected_query  = [
			'query' => [
				'match_all' => [ '*' ]
			],
			'aggs'  => [
				'existing_aggregation' => [ 'lorem' => 'ipsum' ],
				'another_existing_aggr' => [
					'range' => [
						'ranges' => [
							[ 'from' => 10, 'to' => 100 ]
						]
					]
				],
				'some_aggregation' => [ 'whatever' ],
				'another_aggregation' => [ 'foo' => [ 'bar' ] ],
				'one_more_aggregation' => [ 'bar' => 'bazz' ]
			]
		];

		$testee = new AggregationExpressionCollection( $expression_mocks );
		$this->assertSame(
			$expected_query,
			$testee->append_to_query( $query )
		);
	}

	/**
	 * @see AggregationExpressionCollection::push_expression()
	 */
	public function test_push_expression() {

		$expression_mock = $this->get_expression_mock( [ 'some_aggregation' => [ 'whatever' ] ] );
		$testee          = new AggregationExpressionCollection;
		$this->assertTrue(
			$testee->push_expression( $expression_mock )
		);
		$this->assertNotEmpty(
			$testee->append_to_query( [ ] )
		);

		$this->assertFalse(
			$testee->push_expression( $expression_mock )
		);
	}

	/**
	 * @param mixed $expression_value
	 *
	 * @return AggregationExpression
	 */
	private function get_expression_mock( $expression_value ) {

		$expression_mock = Mockery::mock( AggregationExpression::class );
		if ( is_callable( $expression_value ) ) {
			$expression_mock->shouldReceive( 'expression' )
				->andReturnUsing( $expression_value );
		} else {
			$expression_mock->shouldReceive( 'expression' )
				->andReturn(
					$expression_value
				);
		}

		return $expression_mock;
	}
}
