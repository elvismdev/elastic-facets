<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

/**
 * Interface NumericRange
 *
 * @package ElasticFacets\Type
 */
interface NumericRange {

	/**
	 * @return int|float
	 */
	public function min();

	/**
	 * @return int|float
	 */
	public function max();
}