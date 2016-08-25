<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;

/**
 * Class GenericAggregatedNumericRangesCollectionTest
 *
 * @package ElasticFacets\Type
 */
class GenericAggregatedNumericRangesCollectionTest extends BrainMonkeyWpTestCase {

	/**
	 * @see GenericAggregatedNumericRange::ranges()
	 */
	public function test_ranges() {

		$ranges = [
			$this->build_numeric_range_mock( 10, 20 ),
			$this->build_numeric_range_mock( 20, 50 )
		];
		$testee = new GenericAggregatedNumericRangesCollection( $ranges, [ ] );

		$this->assertSame(
			$ranges,
			$testee->ranges()
		);
	}

	/**
	 * @see GenericAggregatedNumericRange::ranges()
	 */
	public function test_invalid_ranges_get_striped() {

		$range_1 = $this->build_numeric_range_mock( 10, 20 );
		$range_2 = $this->build_numeric_range_mock( 20, 50 );
		$testee  = new GenericAggregatedNumericRangesCollection(
			[ $range_1, Mockery::mock( Term::class ), [], 'foo', $range_2, 0 ],
			[ ]
		);

		$this->assertSame(
			[ $range_1, $range_2 ],
			$testee->ranges()
		);
	}

	/**
	 * @see GenericAggregatedNumericRange::count()
	 */
	public function test_count() {


		$ranges = [
			$this->build_numeric_range_mock( 10, 20 ),
			$this->build_numeric_range_mock( 20, 50 )
		];
		$counts = [
			20,
			25
		];
		$testee = new GenericAggregatedNumericRangesCollection( $ranges, $counts );

		$this->assertSame(
			$counts[ 0 ],
			$testee->count( $ranges[ 0 ] )
		);
		$this->assertSame(
			$counts[ 1 ],
			$testee->count( $ranges[ 1 ] )
		);
	}

	/**
	 * @see GenericAggregatedNumericRange::count()
	 */
	public function test_count_missing_value() {


		$ranges = [
			$this->build_numeric_range_mock( 10, 20 ),
			$this->build_numeric_range_mock( 20, 50 ),
			$this->build_numeric_range_mock( 50, 100 ),
		];
		$counts = [
			0,
			25
		];
		$testee = new GenericAggregatedNumericRangesCollection( $ranges, $counts );

		$this->assertSame(
			$counts[ 0 ],
			$testee->count( $ranges[ 0 ] )
		);
		$this->assertSame(
			$counts[ 1 ],
			$testee->count( $ranges[ 1 ] )
		);
		$this->assertSame(
			0,
			$testee->count( $ranges[ 2 ] )
		);
	}

	/**
	 * @param int $min
	 * @param int $max
	 *
	 * @return Mockery\MockInterface
	 */
	private function build_numeric_range_mock( $min = 0, $max = 0 ) {

		$mock = Mockery::mock( NumericRange::class );
		$mock->shouldReceive( 'min' )
			->andReturn( $min );
		$mock->shouldReceive( 'max' )
			->andReturn( $max );

		return $mock;
	}
}
