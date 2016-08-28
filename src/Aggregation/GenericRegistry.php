<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\ElasticPress\AggregationExpressionCollection;
use ElasticFacets\Result\AggregationParserCollection;
use ElasticFacets\Result\ResultStore;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;

/**
 * Class GenericRegistry
 *
 * @package ElasticFacets\Aggregation
 */
final class GenericRegistry implements Registry {

	/**
	 * @var AggregationParserCollection
	 */
	private $parser_collection;

	/**
	 * @var AggregationExpressionCollection
	 */
	private $expression_collection;

	/**
	 * @var ResultStore
	 */
	private $results;

	/**
	 * @param AggregationParserCollection     $parser_collection
	 * @param AggregationExpressionCollection $expression_collection
	 * @param ResultStore                     $results
	 */
	public function __construct(
		AggregationParserCollection $parser_collection,
		AggregationExpressionCollection $expression_collection,
		ResultStore $results
	) {

		$this->parser_collection     = $parser_collection;
		$this->expression_collection = $expression_collection;
		$this->results               = $results;
	}

	/**
	 * @param string $id
	 * @param Terms  $aggregation
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return bool
	 */
	public function add_terms_aggregation( $id, Terms $aggregation ) {

		return $this->parser_collection->push_parser( $aggregation, $id )
			&& $this->expression_collection->push_expression( $aggregation );
	}

	/**
	 * ID needs to be unique across all aggregation types
	 *
	 * @param string        $id
	 * @param NumericRanges $aggregation
	 *
	 * @return mixed
	 */
	public function add_numeric_ranges_aggregation( $id, NumericRanges $aggregation ) {

		return $this->parser_collection->push_parser( $aggregation, $id )
			&& $this->expression_collection->push_expression( $aggregation );
	}

	/**
	 * @param string $id
	 * @param array  $config
	 *
	 * @throws \InvalidArgumentException
	 *
	 * @return bool
	 */
	public function build_aggregation( $id, array $config ) {
		// TODO: Implement build_aggregation() method.
	}

	/**
	 * @param string $id
	 *
	 * @throws \InvalidArgumentException (If no aggregation is registered for the given ID)
	 *
	 * @return AggregatedTermsCollection
	 */
	public function get_terms_result( $id ) {

		return $this->results->terms_result( $id );
	}

	/**
	 * @param string $id
	 *
	 * @throws \InvalidArgumentException (If no aggregation is registered for the given ID)
	 *
	 * @return AggregatedNumericRangesCollection
	 */
	public function get_numeric_ranges_result( $id ) {

		return $this->results->numeric_ranges_result( $id );
	}

}