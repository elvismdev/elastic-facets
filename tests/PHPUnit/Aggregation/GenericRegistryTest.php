<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\Query\AggregationExpressionCollection;
use ElasticFacets\Result\AggregationParserCollection;
use ElasticFacets\Result\ResultStore;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;

/**
 * Class GenericRegistryTest
 *
 * @package ElasticFacets\Aggregation
 */
class GenericRegistryTest extends BrainMonkeyWpTestCase {

	/**
	 * @see GenericRegistry::add_terms_aggregation()
	 */
	public function test_add_terms_aggregation() {

		$aggregation_id   = 'my_aggregation';
		$aggregation_mock = Mockery::mock( Terms::class );

		$parser_collection_mock = Mockery::mock( AggregationParserCollection::class );
		$parser_collection_mock->shouldReceive( 'push_parser' )
			->once()
			->with( $aggregation_mock, $aggregation_id )
			->andReturn( TRUE );

		$expression_collection_mock = Mockery::mock( AggregationExpressionCollection::class );
		$expression_collection_mock->shouldReceive( 'push_expression' )
			->once()
			->with( $aggregation_mock )
			->andReturn( TRUE );

		$result_store_mock = Mockery::mock( ResultStore::class );

		$testee = new GenericRegistry(
			$expression_collection_mock,
			$parser_collection_mock,
			$result_store_mock
		);
		$this->assertTrue(
			$testee->add_terms_aggregation( $aggregation_id, $aggregation_mock )
		);
	}

	/**
	 * @see GenericRegistry::add_numeric_ranges_aggregation()
	 */
	public function test_add_numeric_ranges_aggregation() {


		$aggregation_id   = 'my_aggregation';
		$aggregation_mock = Mockery::mock( NumericRanges::class );

		$parser_collection_mock = Mockery::mock( AggregationParserCollection::class );
		$parser_collection_mock->shouldReceive( 'push_parser' )
			->once()
			->with( $aggregation_mock, $aggregation_id )
			->andReturn( TRUE );

		$expression_collection_mock = Mockery::mock( AggregationExpressionCollection::class );
		$expression_collection_mock->shouldReceive( 'push_expression' )
			->once()
			->with( $aggregation_mock )
			->andReturn( TRUE );

		$result_store_mock = Mockery::mock( ResultStore::class );

		$testee = new GenericRegistry(
			$expression_collection_mock,
			$parser_collection_mock,
			$result_store_mock
		);
		$this->assertTrue(
			$testee->add_numeric_ranges_aggregation( $aggregation_id, $aggregation_mock )
		);
	}

	/**
	 * @see GenericRegistry::get_terms_result()
	 */
	public function test_get_terms_result() {

		$id = 'my_aggregation';
		$terms_collection_mock = Mockery::mock( AggregatedTermsCollection::class );
		$result_store_mock = Mockery::mock( ResultStore::class );
		$result_store_mock->shouldReceive( 'terms_result' )
			->once()
			->with( $id )
			->andReturn( $terms_collection_mock );

		$testee = new GenericRegistry(
			Mockery::mock( AggregationExpressionCollection::class ),
			Mockery::mock( AggregationParserCollection::class ),
			$result_store_mock
		);

		$this->assertSame(
			$terms_collection_mock,
			$testee->get_terms_result( $id )
		);
	}

	/**
	 * @see GenericRegistry::get_numeric_ranges_result()
	 */
	public function test_get_numeric_ranges_result() {

		$id                             = 'my_aggregation';
		$numeric_ranges_collection_mock = Mockery::mock( AggregatedNumericRangesCollection::class );
		$result_store_mock              = Mockery::mock( ResultStore::class );
		$result_store_mock->shouldReceive( 'numeric_ranges_result' )
			->once()
			->with( $id )
			->andReturn( $numeric_ranges_collection_mock );

		$testee = new GenericRegistry(
			Mockery::mock( AggregationExpressionCollection::class ),
			Mockery::mock( AggregationParserCollection::class ),
			$result_store_mock
		);

		$this->assertSame(
			$numeric_ranges_collection_mock,
			$testee->get_numeric_ranges_result( $id )
		);
	}
}
