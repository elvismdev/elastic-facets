<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\AggregationField;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RequestNumericRangeField
 *
 * Aggregation field definition for single numeric ranges (min/max)
 *
 * @package ElasticFacets\Type
 */
final class RequestNumericRangeField implements NumericRangeAggregationField {

	/**
	 * @var ServerRequestInterface
	 */
	private $request;

	/**
	 * @var string
	 */
	private $request_name;

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $field;

	/**
	 * @var int|float|NULL
	 */
	private $min = NULL;

	/**
	 * @var int|float|null
	 */
	private $max = NULL;

	/**
	 * @param string                 $field
	 * @param ServerRequestInterface $request
	 * @param string                 $request_name
	 * @param string                 $id (Optional)
	 */
	public function __construct( $field, ServerRequestInterface $request, $request_name, $id = '' ) {

		$this->field        = (string) $field;
		$this->request      = $request;
		$this->request_name = (string) $request_name;
		$this->id           = $id ? : str_replace( '.', '_', uniqid( "{$field}_", TRUE ) );
		$this->parse_request();
	}

	/**
	 * @return int|float|NULL
	 */
	public function min() {

		return $this->min;
	}

	/**
	 * @return int|float|NULL
	 */
	public function max() {

		return $this->max;
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
	 * Field name that is used in the query
	 *
	 * @return string
	 */
	public function field() {

		return $this->field;
	}

	/**
	 * Parses min/max values from the request parameter.
	 * The lower value is considered the min, the other
	 * the max value.
	 */
	private function parse_request() {

		$params = $this->request->getQueryParams();
		if ( empty( $params[ $this->request_name ] )
			|| ! is_array( $params[ $this->request_name ] )
		) {
			return;
		}

		$params = array_map(
			function( $el ) {
				if ( '-inf' === strtolower( $el ) ) {
					return - INF;
				}

				return 'inf' === strtolower( $el )
					? INF
					: (int) $el;
			},
			$params[ $this->request_name ]
		);
		sort( $params );
		$params    = array_values( $params );
		$this->min = $params[ 0 ];
		if ( isset( $params[ 1 ] ) ) {
			$this->max = $params[ 1 ];
		}
	}
}