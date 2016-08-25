<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\AggregationField;

use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RequestNumericRangeFieldTest
 *
 * @package ElasticFacets\AggregationField
 */
class RequestNumericRangeFieldTest extends BrainMonkeyWpTestCase {

	/**
	 * @see RequestNumericRangeField::field()
	 */
	public function test_field() {

		$field = 'my.custom.field';
		$request_mock = Mockery::mock( ServerRequestInterface::class );
		$request_mock
			->shouldReceive( 'getQueryParams' )
			->andReturn( [] );

		$testee = new RequestNumericRangeField( $field, $request_mock, '' );

		$this->assertSame(
			$field,
			$testee->field()
		);
	}

	/**
	 * @see RequestNumericRangeField::id()
	 */
	public function test_id() {

		$id = 'my_id';
		$request_mock = Mockery::mock( ServerRequestInterface::class );
		$request_mock
			->shouldReceive( 'getQueryParams' )
			->andReturn( [] );

		$testee = new RequestNumericRangeField( '' , $request_mock, '', $id );

		$this->assertSame(
			$id,
			$testee->id()
		);
	}

	/**
	 * @see RequestNumericRangeField::id()
	 */
	public function test_generated_id() {

		$field = 'my_field';
		$request_mock = Mockery::mock( ServerRequestInterface::class );
		$request_mock
			->shouldReceive( 'getQueryParams' )
			->andReturn( [] );

		$testee = new RequestNumericRangeField( $field , $request_mock, '' );

		$this->assertStringStartsWith(
			$field,
			$testee->id()
		);
	}

	/**
	 * @see RequestNumericRangeField::id()
	 */
	public function test_unique_id() {

		$request_mock = Mockery::mock( ServerRequestInterface::class );
		$request_mock
			->shouldReceive( 'getQueryParams' )
			->andReturn( [] );

		$testee1 = new RequestNumericRangeField( 'my_field' , $request_mock, '' );
		$testee2 = new RequestNumericRangeField( 'my_field' , $request_mock, '' );

		$this->assertNotSame(
			$testee1,
			$testee2
		);
	}

	/**
	 * @see RequestNumericRangeField::min()
	 * @see RequestNumericRangeField::max()
	 */
	public function test_min_max() {

		$min = '10';
		$max = '12';
		$request_name = 'test';
		$request_mock = Mockery::mock( ServerRequestInterface::class );
		$request_mock
			->shouldReceive( 'getQueryParams' )
			->andReturn( [ $request_name => [ $min, $max ] ] );

		$testee = new RequestNumericRangeField( '' , $request_mock, $request_name );

		$this->assertSame(
			(int) $min,
			$testee->min(),
			"Failed for RequestNumericRangeField::min()"
		);
		$this->assertSame(
			(int) $max,
			$testee->max(),
			"Failed for RequestNumericRangeField::max()"
		);
	}
}
