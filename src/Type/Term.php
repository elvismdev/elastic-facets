<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

/**
 * Interface Term
 *
 * A term document
 *
 * @package ElasticFacets\Type
 */
interface Term {
	
	/**
	 * Numeric ID
	 * 
	 * @return int
	 */
	public function id();
	
	/**
	 * Human readable name
	 *
	 * @return string
	 */
	public function name();
}