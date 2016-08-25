<?php # -*- coding: utf-8 -*-

namespace ElasticFacets;

/**
 * @param mixed $value (Any scalar value)
 *
 * @return int|float
 */
function string_to_number( $value ) {

	if ( ! is_scalar( $value ) ) {
		throw new \InvalidArgumentException( "Value is not scalar or boolean" );
	}

	if ( is_int( $value ) || is_float( $value ) || is_infinite( $value ) ) {
		return $value;
	}

	if ( ! is_numeric( $value ) ) {
		return (int) $value;
	}

	if ( is_string( $value ) && FALSE !== strpos( $value, '.' ) ) {
		return (float) $value;
	}

	return (int) $value;
}