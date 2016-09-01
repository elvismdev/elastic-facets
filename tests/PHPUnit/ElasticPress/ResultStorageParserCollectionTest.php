<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\ElasticPress;

use ElasticFacets\Result\ParseNumericRangesAggregation;
use ElasticFacets\Result\ParseTermsAggregation;
use ElasticFacets\Result\ResultStore;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;

class ResultStorageParserCollectionTest extends BrainMonkeyWpTestCase {

	/**
	 * @see ResultStorageParserCollection::push_parser()
	 */
	public function test_push_parser_term_parser() {

		// ElasticPress returns the body reduced to the aggregations…
		$ep_response = [ 'something' ];
		// but the interface expects the complete body
		$expected_response = [
			'aggregations' => $ep_response
		];
		$parser_mock       = Mockery::mock( ParseTermsAggregation::class );
		$parser_mock->shouldReceive( 'parse_response' )
			->once()
			->with(
				Mockery::on(
					function ( $response ) use ( $expected_response ) {

						$this->assertSame(
							$expected_response,
							$response,
							'Mismatching arguments for mock ParseTermsAggregation::parse_response() '
						);
						return TRUE;
					}
				)
			);

		$testee = new ResultStorageParserCollection;
		$testee->push_parser( $parser_mock, 'id' );
		$testee->parse_response( $ep_response );
	}

	/**
	 * @see ResultStorageParserCollection::push_parser()
	 */
	public function test_push_parser_numeric_range_parser() {

		// ElasticPress returns the body reduced to the aggregations…
		$ep_response = [ 'something' ];
		// but the interface expects the complete body
		$expected_response = [
			'aggregations' => $ep_response
		];
		$parser_mock       = Mockery::mock( ParseNumericRangesAggregation::class );
		$parser_mock->shouldReceive( 'parse_response' )
			->once()
			->with(
				Mockery::on(
					function ( $response ) use ( $expected_response ) {

						$this->assertSame(
							$expected_response,
							$response,
							'Mismatching arguments for mock ParseTermsAggregation::parse_response() '
						);
						return TRUE;
					}
				)
			);

		$testee = new ResultStorageParserCollection;
		$testee->push_parser( $parser_mock, 'id' );
		$testee->parse_response( $ep_response );
	}

	/**
	 * @see ResultStorageParserCollection::push_parser()
	 *
	 * @expectedException \InvalidArgumentException
	 */
	public function test_push_parser_throws_exception_on_wrong_type() {

		$testee = new ResultStorageParserCollection;
		$testee->push_parser( Mockery::mock( ResultStore::class ), 'my_aggregation' );
	}


	/**
	 * @see ResultStorageParserCollection::push_parser()
	 *
	 * @expectedException \InvalidArgumentException
	 */
	public function test_push_parser_throws_exception_on_duplicate_id() {

		$testee = new ResultStorageParserCollection;

		$testee->push_parser(
			Mockery::mock( ParseTermsAggregation::class ),
			'foo'
		);
		$testee->push_parser(
			Mockery::mock( ParseTermsAggregation::class ),
			'foo'
		);

	}

	/**
	 * @see ResultStorageParserCollection::terms_result()
	 */
	public function test_terms_result() {

		$id = 'my_aggregation';
		// ElasticPress returns the body reduced to the aggregations…
		$ep_response = [ 'something' ];
		// but the interface expects the complete body
		$expected_response = [
			'aggregations' => $ep_response
		];
		$result_mock = Mockery::mock( AggregatedTermsCollection::class );
		$parser_mock = Mockery::mock( ParseTermsAggregation::class );
		$parser_mock->shouldReceive( 'parse_response' )
			->with( $expected_response )
			->andReturn( $result_mock );

		$testee = new ResultStorageParserCollection;
		$testee->push_parser( $parser_mock, $id );
		$testee->parse_response( $ep_response );

		$this->assertSame(
			$result_mock,
			$testee->terms_result( $id )
		);
	}

	/**
	 * @see ResultStorageParserCollection::terms_result()
	 */
	public function test_terms_result_return_null() {

		$testee = new ResultStorageParserCollection;
		$this->assertNull(
			$testee->terms_result( 'my_aggregation' )
		);
	}

	/**
	 * @see ResultStorageParserCollection::numeric_ranges_result()
	 */
	public function test_numeric_ranges_result() {

		$id = 'my_aggregation';
		// ElasticPress returns the body reduced to the aggregations…
		$ep_response = [ 'something' ];
		// but the interface expects the complete body
		$expected_response = [
			'aggregations' => $ep_response
		];
		$result_mock = Mockery::mock( AggregatedNumericRangesCollection::class );
		$parser_mock = Mockery::mock( ParseNumericRangesAggregation::class );
		$parser_mock->shouldReceive( 'parse_response' )
			->with( $expected_response )
			->andReturn( $result_mock );

		$testee = new ResultStorageParserCollection;
		$testee->push_parser( $parser_mock, $id );
		$testee->parse_response( $ep_response );

		$this->assertSame(
			$result_mock,
			$testee->numeric_ranges_result( $id )
		);
	}

	/**
	 * @see ResultStorageParserCollection::numeric_ranges_result()
	 */
	public function test_numeric_ranges_result_return_null() {

		$testee = new ResultStorageParserCollection;
		$this->assertNull(
			$testee->numeric_ranges_result( 'my_aggregation' )
		);
	}

}
