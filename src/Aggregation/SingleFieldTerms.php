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
	 * @var int|NULL
	 */
	private $size;

	/**
	 * @param SingleAggregationField $field
	 */
	public function __construct( SingleAggregationField $field, $size = NULL ) {

		$this->field = $field;
		NULL !== $size and $this->size = (int) $size;
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
		$terms = [
			"field" => $this->field->field()
		];
		NULL !== $this->size and $terms[ 'size' ] = $this->size;

		return [
			$this->field->id() => [
				"terms" => $terms
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
			if ( ! is_a( $wp_term, 'WP_Term' ) ) {
				// Todo: Error handling
				continue;
			}
			$documents[] = WpTaxonomyTerm::create_from_wp_term( $wp_term );
			$counts[]    = (int) $bucket[ 'doc_count' ];
		}

		return new GenericAggregatedTermCollection( $documents, $counts );
	}

}