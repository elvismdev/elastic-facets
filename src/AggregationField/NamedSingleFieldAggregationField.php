<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\AggregationField;

/**
 * Class NamedSingleAggregationField
 *
 * @package ElasticFacets\Type
 */
final class NamedSingleAggregationField implements SingleAggregationField {

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @param string $field
	 * @param string $id
	 */
	public function __construct( $field, $id = '' ) {

		$this->field = (string) $field;
		$this->id    = $id ?: str_replace( '.', '_', uniqid( "{$field}_", TRUE ) );
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

}