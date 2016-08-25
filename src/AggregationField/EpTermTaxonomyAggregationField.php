<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\AggregationField;

/**
 * Class EpTermTaxonomyAggregationField
 *
 * @package ElasticFacets\Type
 */
final class EpTermTaxonomyAggregationField implements SingleAggregationField {

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @param string $taxonomy
	 * @param string $id
	 */
	public function __construct( $taxonomy, $id = '' ) {

		$taxonomy    = (string) $taxonomy;
		$this->field = $this->build_field_name( $taxonomy );
		$this->id    = $id ?: uniqid( "{$taxonomy}_", TRUE );
	}

	/**
	 * Unique ID to identify the aggregation in the results
	 *
	 * @return string
	 */
	public function id() {

		return $this->id;
	}

	/**
	 * Field name that is used in the aggregation query
	 *
	 * @return string
	 */
	public function field() {

		return $this->field;
	}

	/**
	 * @param string $field
	 *
	 * @return string
	 */
	private function build_field_name( $field ) {

		return "terms.{$field}.term_id";
	}
}