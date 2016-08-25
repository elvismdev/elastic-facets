<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\AggregationField\SingleAggregationField;
use ElasticFacets\Type\AggregatedTermsCollection;
use ElasticFacets\Type\GenericAggregatedTermCollection;
use ElasticFacets\Type\WpTaxonomyTerm;

/**
 * Class SingleFieldTerms
 *
 * @package ElasticFacets\Aggregation
 */
final class SingleFieldTerms implements Terms {

	/**
	 * @var SingleAggregationField
	 */
	private $field;

	/**
	 * @param SingleAggregationField $field
	 */
	public function __construct( SingleAggregationField $field ) {

		$this->field = $field;
	}

	/**
	 * @param array $query (The complete so-far built query expression)
	 *
	 * @return array Associative array that express one aggregation E.g.
	 *               [
	 *                  "aggregation_id" => [ aggregation definition ]
	 *               ]
	 */
	public function expression( array $query = [ ] ) {

		/**
		 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html
		 */
		return [
			$this->field->id() => [
				"terms" => [
					"field" => $this->field->field()
				]
			]
		];
	}

	/**
	 * @param array $response
	 *
	 * @return AggregatedTermsCollection
	 */
	public function parse_response( array $response ) {

		/**
		 * @link https://www.elastic.co/guide/en/elasticsearch/reference/current/search-aggregations-bucket-terms-aggregation.html
		 */
		if ( ! isset( $response[ 'aggregations' ][ $this->field->id() ] ) ) {
			// Todo: Handle missing aggregation. Something went wrong with ES
			return new GenericAggregatedTermCollection( [], [] );
		}

		$aggregation = $response[ 'aggregations' ][ $this->field->id() ];
		if ( ! isset( $aggregation[ 'buckets' ] ) || ! is_array( $aggregation[ 'buckets' ] ) ) {
			// Todo: Handle missing aggregation. Something went wrong with ES
			return new GenericAggregatedTermCollection( [], [] );
		}

		$documents = [ ];
		$counts    = [ ];
		foreach ( $aggregation[ 'buckets' ] as $bucket ) {
			$wp_term = get_term( (int) $bucket[ 'key' ] );
			if ( is_wp_error( $wp_term ) ) {
				// Todo: Error handling
				continue;
			}
			$documents[] = WpTaxonomyTerm::create_from_wp_term( $wp_term );
			$counts[]    = (int) $bucket[ 'doc_count' ];
		}

		return new GenericAggregatedTermCollection( $documents, $counts );
	}

}