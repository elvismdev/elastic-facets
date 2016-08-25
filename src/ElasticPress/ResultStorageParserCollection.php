<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\ElasticPress;

use ElasticFacets\Result\ParseNumericRangesAggregation;
use ElasticFacets\Result\AggregationParserCollection;
use ElasticFacets\Result\ParseTermsAggregation;
use ElasticFacets\Result\ResultStore;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;
use InvalidArgumentException;

/**
 * Class ResultStorageParserCollection
 *
 * @package ElasticFacets\ElasticPress
 */
final class ResultStorageParserCollection implements AggregationParserCollection, ResultStore {

	const PARSER_TYPE_TERM          = 'terms';
	const PARSER_TYPE_NUMERIC_RANGE = 'numeric_ranges';

	/**
	 * @var array
	 */
	private $parsers = [ ];

	/**
	 * @var array
	 */
	private $parser_types = [ ];

	/**
	 * @var AggregatedNumericRangesCollection[]
	 */
	private $aggregated_numeric_ranges = [ ];

	/**
	 * @var AggregatedTermsCollection[]
	 */
	private $aggregated_terms = [];

	/**
	 * @param ParseNumericRangesAggregation|ParseTermsAggregation $parser
	 * @param string                                              $id
	 *
	 * @throws InvalidArgumentException
	 *
	 * @return bool
	 */
	public function push_parser( $parser, $id ) {

		$id = (string) $id;
		if ( isset( $this->parsers[ $id ] ) ) {
			throw new InvalidArgumentException( "ID {$id} is already in use" );
		}

		if ( $parser instanceof ParseNumericRangesAggregation ) {
			return $this->push_numeric_range_parser( $parser, $id );
		}

		if ( $parser instanceof ParseTermsAggregation ) {
			return $this->push_terms_parser( $parser, $id );
		}

		throw new InvalidArgumentException(
			'Aggregation is not of type ParseNumericRangesAggregation or ParseTermsAggregation'
		);
	}

	/**
	 * @wp-hook ep_retrieve_aggregations
	 *
	 * @param array $response Contains a key 'aggregations' with all aggregations:
	 *                        [ "aggregations => [ "aggregation_name" => [ ... ]   ] ]
	 *
	 * @return void
	 */
	public function parse_response( array $response ) {
		
		foreach ( $this->parsers as $id => $parser ) {
			switch ( $this->parser_types[ $id ] ) {
				case self::PARSER_TYPE_NUMERIC_RANGE :
					/* @var ParseNumericRangesAggregation $parser */
					$this->aggregated_numeric_ranges[ $id ] = $parser->parse_response( $response );
					break;
				case self::PARSER_TYPE_TERM :
					/* @var ParseTermsAggregation $parser */
					$this->aggregated_terms[ $id ] = $parser->parse_response( $response );
					break;
			}
		}
	}

	/**
	 * @param string $id
	 *
	 * @throws InvalidArgumentException if there's no aggregation is registered for this ID
	 *
	 * @return AggregatedTermsCollection
	 */
	public function terms_result( $id ) {
		
		$id = (string) $id;
		if ( ! isset( $this->aggregated_terms[ $id ] ) ) {
			throw new InvalidArgumentException( "No aggregation registered for id {$id}" );
		}
		
		return $this->aggregated_terms[ $id ];
	}

	/**
	 * @param string $id
	 *
	 * @throws InvalidArgumentException if there's no aggregation is registered for this ID
	 *
	 * @return AggregatedNumericRangesCollection
	 */
	public function numeric_ranges_result( $id ) {
		
		$id = (string) $id;
		if ( ! isset( $this->aggregated_numeric_ranges[ $id ] ) ) {
			throw new InvalidArgumentException( "No aggregation registered for id {$id}" );
		}

		return $this->aggregated_numeric_ranges[ $id ];
	}

	/**
	 * @param ParseNumericRangesAggregation $parser
	 * @param string                        $id
	 *
	 * @return bool
	 */
	private function push_numeric_range_parser( ParseNumericRangesAggregation $parser, $id ) {

		if ( isset( $this->parsers[ $id ] ) ) {
			return FALSE;
		}
		if ( in_array( $parser, $this->parsers, TRUE ) ) {
			return FALSE;
		}
		$this->parsers[ $id ] = $parser;
		$this->parser_types[ $id ] = self::PARSER_TYPE_NUMERIC_RANGE;

		return TRUE;
	}

	/**
	 * @param ParseTermsAggregation $parser
	 *
	 * @return bool
	 */
	private function push_terms_parser( ParseTermsAggregation $parser, $id ) {

		if ( isset( $this->parsers[ $id ] ) ) {
			return FALSE;
		}
		if ( in_array( $parser, $this->parsers, TRUE ) ) {
			return FALSE;
		}
		$this->parsers[ $id ]      = $parser;
		$this->parser_types[ $id ] = self::PARSER_TYPE_TERM;

		return TRUE;
	}

}