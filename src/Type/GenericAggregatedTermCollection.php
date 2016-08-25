<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

/**
 * Class GenericAggregatedTermCollection
 *
 * @package ElasticFacets\Type
 */
final class GenericAggregatedTermCollection implements AggregatedTermsCollection {

	/**
	 * @var Term[]
	 */
	private $terms = [];

	/**
	 * @var int[]
	 */
	private $counts = [];

	/**
	 * @param Term[] $terms
	 * @param int[]  $counts
	 */
	public function __construct( array $terms, array $counts ) {

		$this->terms  = $this->sanitize_terms( $terms );
		$this->counts = $this->sanitize_counts( $counts, $this->terms );
	}

	/**
	 * Number of matching documents for the given term
	 *
	 * @param Term $term
	 *
	 * @return int
	 */
	public function count( Term $term ) {

		return isset( $this->counts[ $term->id() ] )
			? $this->counts[ $term->id() ]
			: 0;
	}

	/**
	 * List of matched terms
	 *
	 * @return Term[]
	 */
	public function terms() {

		return $this->terms;
	}

	/**
	 * @param array $terms
	 *
	 * @return Term[]
	 */
	private function sanitize_terms( array $terms ) {

		$terms = array_filter(
			$terms,
			function( $el ) {
				return $el instanceof Term;
			}
		);

		return array_values( $terms );
	}

	/**
	 * @param array $counts
	 * @param Term[] $terms
	 *
	 * @return array
	 */
	private function sanitize_counts( array $counts, array $terms ) {

		$counts = array_values( $counts );
		$sanitized_counts = [];
		foreach ( $terms as $key => $term ) {
			$sanitized_counts[ $term->id() ] = isset( $counts[ $key ] )
				? (int) $counts[ $key ]
				: 0;
		}

		return $sanitized_counts;
	}

}