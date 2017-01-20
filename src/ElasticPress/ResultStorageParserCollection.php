<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\ElasticPress;

use ElasticFacets\Result\ParseNumericRangesAggregation;
use ElasticFacets\Result\AggregationParserCollection;
use ElasticFacets\Result\ParseTermsAggregation;
use ElasticFacets\Result\ResultStore;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;
use ElasticFacets\Type\AggregatesCollection;
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
	 * @var AggregatesCollection[]
	 */
	private $aggregates = [];

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

		/* ElasticPress does not match the interface here */
		$response = [
			'aggregations' => $response
		];

		foreach ( $this->parsers as $id => $parser ) {
			switch ( $this->parser_types[ $id ] ) {
				case self::PARSER_TYPE_NUMERIC_RANGE :
					/* @var ParseNumericRangesAggregation $parser */
					$this->aggregated_numeric_ranges[ $id ] = $parser->parse_response( $response );
					$this->aggregates[ $id ] = $this->aggregated_numeric_ranges[ $id ];
					break;
				case self::PARSER_TYPE_TERM :
					/* @var ParseTermsAggregation $parser */
					$this->aggregated_terms[ $id ] = $parser->parse_response( $response );
					$this->aggregates[ $id ] = $this->aggregated_terms[ $id ];
					break;
			}
		}
	}

	/**
	 * @param string $id
	 *
	 * @return AggregatedTermsCollection|null (Returns null if no aggregation was found, which probably means ES service is down)
	 */
	public function terms_result( $id ) {

		$id = (string) $id;
		if ( ! isset( $this->aggregated_terms[ $id ] ) ) {
			return null;
		}

		return $this->aggregated_terms[ $id ];
	}

	/**
	 * @param string $id
	 *
	 * @return AggregatedNumericRangesCollection|null (Returns null if no aggregation was found, which probably means ES service is down)
	 */
	public function numeric_ranges_result( $id ) {

		$id = (string) $id;
		if ( ! isset( $this->aggregated_numeric_ranges[ $id ] ) ) {
			return null;
		}

		return $this->aggregated_numeric_ranges[ $id ];
	}

	/**
	 * @param $id
	 *
	 * @return AggregatesCollection|null
	 */
	public function result( $id ) {

		$id = (string) $id;
		if ( ! isset( $this->aggregates[ $id ] ) ) {
			return null;
		}

		return $this->aggregates[ $id ];
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