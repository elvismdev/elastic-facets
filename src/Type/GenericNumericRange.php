<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

use InvalidArgumentException;
use function ElasticFacets\string_to_number;

/**
 * Class GenericNumericRange
 *
 * @package ElasticFacets\Type
 */
final class GenericNumericRange implements NumericRange {

	/**
	 * @var int|float
	 */
	private $min = 0;

	/**
	 * @var int|float
	 */
	private $max = 0;

	/**
	 * @param int|float $min
	 * @param int|float $max
	 */
	public function __construct( $min, $max ) {

		$this->min = string_to_number( $min );
		$this->max = string_to_number( $max );
	}

	/**
	 * @return int|float
	 */
	public function min() {

		return $this->min;
	}

	/**
	 * @return int|float
	 */
	public function max() {

		return $this->max;
	}

	/**
	 * @param int[] $range
	 *
	 * @throws InvalidArgumentException
	 * @return GenericNumericRange
	 */
	public static function create_from_array( array $range ) {

		if ( 2 > count( $range ) ) {
			throw new InvalidArgumentException( "Argument must at least contain two elements" );
		}

		$range = array_map( 'intval', $range );
		sort( $range );
		$range = array_values( $range );

		return new GenericNumericRange( $range[ 0 ], $range[ 1 ] );
	}
}