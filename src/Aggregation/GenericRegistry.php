<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\Query\AggregationExpressionCollection;
use ElasticFacets\Result\AggregationParserCollection;
use ElasticFacets\Result\ResultStore;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;

/**
 * Class GenericRegistry
 *
 * Todo: Consider to deprecate this API in favor of ElasticFacetsApi
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
	 * @param AggregationExpressionCollection $expression_collection
	 * @param AggregationParserCollection     $parser_collection
	 * @param ResultStore                     $results
	 */
	public function __construct(
		AggregationExpressionCollection $expression_collection,
		AggregationParserCollection $parser_collection,
		ResultStore $results
	) {

		$this->expression_collection = $expression_collection;
		$this->parser_collection     = $parser_collection;
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
	 *
	 * @throws \InvalidArgumentException (If no aggregation is registered for the given ID)
	 *
	 * @return AggregatedTermsCollection|NULL
	 */
	public function get_terms_result( $id ) {

		return $this->results->terms_result( $id );
	}

	/**
	 * @param string $id
	 *
	 * @throws \InvalidArgumentException (If no aggregation is registered for the given ID)
	 *
	 * @return AggregatedNumericRangesCollection|NULL
	 */
	public function get_numeric_ranges_result( $id ) {

		return $this->results->numeric_ranges_result( $id );
	}

}