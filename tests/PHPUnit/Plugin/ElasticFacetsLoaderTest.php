<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Plugin;

use ElasticFacets\Aggregation\Registry;
use ElasticFacets\Query\AggregationExpressionCollection;
use ElasticFacets\Result\AggregationParserCollection;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;
use Brain\Monkey;
use Psr\Http\Message\ServerRequestInterface;

class ElasticFacetsLoaderTest extends BrainMonkeyWpTestCase {

	/**
	 * @see ElasticFacetsLoader::register_callbacks()
	 */
	public function test_register_callbacks() {

		$request_mock               = Mockery::mock( ServerRequestInterface::class );
		$expression_collection_mock = Mockery::mock( AggregationExpressionCollection::class );
		$parser_collection_mock     = Mockery::mock( AggregationParserCollection::class );
		$registry_mock              = Mockery::mock( Registry::class );

		$testee = new ElasticFacetsLoader(
			$request_mock,
			$expression_collection_mock,
			$parser_collection_mock,
			$registry_mock
		);

		Monkey\WP\Filters::expectAdded( 'ep_formatted_args' )
			->once()
			->with( [ $expression_collection_mock, 'append_to_query'] );

		Monkey\WP\Actions::expectAdded( 'ep_retrieve_aggregations' )
			->once()
			->with( [ $parser_collection_mock, 'parse_response' ] );

		Monkey\WP\Actions::expectFired( 'elastic_facets.register_aggregation' )
			->once()
			->with( $registry_mock, $request_mock );

		Monkey\Wp\Filters::expectAdded( 'elastic_facets.get_registry' )
			->once()
			->with( Mockery::type( 'closure' ) );

		$testee->register_callbacks();
	}

	public function test_build_from_request() {

		$request_mock = Mockery::mock( ServerRequestInterface::class );

		$testee = ElasticFacetsLoader::build_from_request( $request_mock );

		Monkey\WP\Filters::expectAdded( 'ep_formatted_args' )
			->once()
			->with( Mockery::on(
				function( $argument ) {
					$this->assertInstanceOf(
						AggregationExpressionCollection::class,
						$argument[ 0 ]
					);
					$this->assertSame(
						'append_to_query',
						$argument[ 1 ]
					);

					return TRUE;
				}
			) );

		Monkey\WP\Actions::expectAdded( 'ep_retrieve_aggregations' )
			->once()
			->with( Mockery::on(
				function( $argument ) {
					$this->assertInstanceOf(
						AggregationParserCollection::class,
						$argument[ 0 ]
					);
					$this->assertSame(
						'parse_response',
						$argument[ 1 ]
					);

					return TRUE;
				}
			) );

		Monkey\WP\Actions::expectFired( 'elastic_facets.register_aggregation' )
			->once()
			->with( Mockery::type( Registry::class ), $request_mock );

		Monkey\Wp\Filters::expectAdded( 'elastic_facets.get_registry' )
			->once()
			->with( Mockery::type( 'closure' ) );

		$testee->register_callbacks();
	}
}
