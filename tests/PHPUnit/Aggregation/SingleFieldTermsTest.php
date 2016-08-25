<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use Brain\Monkey;
use ElasticFacets\AggregationField\SingleAggregationField;
use ElasticFacets\Type\AggregatedTermsCollection;
use ElasticFacets\Type\Term;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;

class SingleFieldTermsTest extends BrainMonkeyWpTestCase {

	/**
	 * @see Terms::expression()
	 */
	public function test_expression() {

		$field_mock = $this->get_field_mock(
			'my_aggregation',
			'terms.category.term_id'
		);
		$expected   = [
			'my_aggregation' => [
				'terms' => [
					'field' => 'terms.category.term_id'
				]
			]
		];

		$testee = new SingleFieldTerms( $field_mock );

		$this->assertSame(
			$expected,
			$testee->expression()
		);
	}

	/**
	 * @see          Terms::parse_response()
	 * @dataProvider parse_response_test_data
	 */
	public function test_parse_response( array $response, array $options ) {

		$field_mock = $this->get_field_mock( $options[ 'aggregation' ], '' );
		Monkey::functions()
			->expect( 'get_term' )
			->andReturnUsing(
				function( $term_id ) {
					$term_mock = Mockery::mock( 'WP_term' );
					$term_mock->term_id = $term_id;
					$term_mock->name    = '';

					return $term_mock;
				}
			);
		Monkey::functions()
			->when( 'is_wp_error' )
			->justReturn( FALSE );

		$testee     = new SingleFieldTerms( $field_mock );
		$collection = $testee->parse_response( $response );

		$this->assertInstanceOf(
			AggregatedTermsCollection::class,
			$collection
		);
		$this->assertCount(
			$options[ 'number_of_terms' ],
			$collection->terms()
		);

		foreach ( $collection->terms() as $term ) {
			$this->assertInstanceOf(
				Term::class,
				$term,
				"Failed for term {$term->id()}"
			);
			$this->assertSame(
				$options[ 'term_count' ][ $term->id() ],
				$collection->count( $term ),
				"Failed for term {$term->id()}"
			);
		}
	}

	/**
	 * @see test_parse_response
	 * @return array
	 */
	public function parse_response_test_data() {

		return [
			'test_1' => [
				// 1. argument $response
				[
					'aggregations' => [
						'my_aggregation' => [
							'doc_count_error_upper_bound' => 0,
							'sum_other_doc_count'         => 0,
							'buckets'                     => [
								[
									'key'   => '13',
									'doc_count' => '0'
								],
								[
									'key'   => '26',
									'doc_count' => '10'
								],
								[
									'key'   => '5',
									'doc_count' => '26'
								]
							]
						]
					]
				],
				// 2. argument $options
				[
					'aggregation' => 'my_aggregation',
					'number_of_terms' => 3,
					'term_count'  => [
						13 => 0,
						26 => 10,
						5  => 26
					]
				]
			]
		];
	}

	/**
	 * Todo: Test error handling and behaviour with bad arguments of parse_response()
	 */
	public function test_parse_response_error() {

		$this->markTestIncomplete( 'Under construction…' );
	}

	/**
	 * @param $id
	 * @param $field
	 *
	 * @return SingleAggregationField
	 */
	private function get_field_mock( $id, $field ) {

		$mock = Mockery::mock( SingleAggregationField::class );
		$mock->shouldReceive( 'id' )
			->andReturn( $id );
		$mock->shouldReceive( 'field' )
			->andReturn( $field );

		return $mock;
	}
}
