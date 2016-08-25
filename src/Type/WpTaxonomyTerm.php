<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

use WP_Term;

/**
 * Class WpTaxonomyTerm
 *
 * @package ElasticFacets\Type
 */
final class WpTaxonomyTerm implements Term {

	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @param int    $id
	 * @param string $name
	 */
	public function __construct( $id, $name = '' ) {

		$this->id   = (int) $id;
		$this->name = (string) $name;
	}

	/**
	 * Numeric ID
	 *
	 * @return int
	 */
	public function id() {

		return $this->id;
	}

	/**
	 * Human readable name
	 *
	 * @return string
	 */
	public function name() {

		return $this->name;
	}

	/**
	 * @param WP_Term $term
	 *
	 * @return WpTaxonomyTerm
	 */
	public static function create_from_wp_term( WP_Term $term ) {

		return new WpTaxonomyTerm( $term->term_id, $term->name );
	}
}