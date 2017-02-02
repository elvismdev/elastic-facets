<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Plugin;

use ElasticFacets\ElasticFacetsApi;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;
use Brain\Monkey;
use Psr\Http\Message\ServerRequestInterface;

class ElasticFacetsLoaderTest extends BrainMonkeyWpTestCase {

	/**
	 * @see ElasticFacetsLoader::register_callbacks()
	 */
	public function test_register_callbacks() {

		$ef_api_mock = Mockery::mock( ElasticFacetsApi::class );

		$testee = new ElasticFacetsLoader( $ef_api_mock );

		Monkey\WP\Actions::expectAdded( 'pre_get_posts' )
			->once()
			->with( Mockery::type( 'closure' ), 5 );

		Monkey\WP\Filters::expectAdded( 'elastic_facets.get_registry' )
			->once()
			->with( Mockery::type( 'closure' ) );

		Monkey\WP\Filters::expectAdded( 'ep_formatted_args' )
			->once()
			->with( Mockery::type( 'closure' ), 10, 2 );

		Monkey\WP\Actions::expectAdded( 'ep_retrieve_aggregations' )
			->once()
			->with( Mockery::type( 'closure' ), 10, 4 );

		$testee->register_callbacks();
	}

	public function test_build_from_request() {

		$request_mock = Mockery::mock( ServerRequestInterface::class );

		$testee = ElasticFacetsLoader::build_from_request( $request_mock );

		$this->assertInstanceOf(
			ElasticFacetsLoader::class,
			$testee
		);
	}
}
