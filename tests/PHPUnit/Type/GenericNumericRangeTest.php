<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

use MonkeryTestCase\BrainMonkeyWpTestCase;

/**
 * Class GenericNumericRangeTest
 *
 * @package ElasticFacets\Type
 */
class GenericNumericRangeTest extends BrainMonkeyWpTestCase {

	/**
	 * @see StaticCreatableNumericRange::min()
	 */
	public function test_min() {

		$min = 42;
		$testee = new GenericNumericRange( $min, 0 );

		$this->assertSame(
			$min,
			$testee->min()
		);
	}

	/**
	 * @see StaticCreatableNumericRange::max()
	 */
	public function test_max() {

		$max = 42;
		$testee = new GenericNumericRange( 0, $max );

		$this->assertSame(
			$max,
			$testee->max()
		);
	}

	/**
	 * @see StaticCreatableNumericRange::create_from_array()
	 */
	public function test_create_from_array() {

		$min = 24;
		$max = 42;
		$testee = GenericNumericRange::create_from_array( [ $min, $max ] );

		$this->assertInstanceOf(
			GenericNumericRange::class,
			$testee
		);
		$this->assertSame(
			$min,
			$testee->min()
		);
		$this->assertSame(
			$max,
			$testee->max()
		);
	}

	/**
	 * @see StaticCreatableNumericRange::create_from_array()
	 *
	 * @expectedException \InvalidArgumentException
	 */
	public function test_create_from_array_throws_exception() {

		GenericNumericRange::create_from_array( [ 10 ] );
	}
}
