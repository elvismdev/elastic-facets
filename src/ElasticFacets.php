<?php # -*- coding: utf-8 -*-

namespace ElasticFacets;

use ElasticFacets\ElasticPress\ResultStorageParserCollection;
use ElasticFacets\Query\AggregationExpression;
use ElasticFacets\Query\AggregationExpressionCollection;
use ElasticFacets\Result\AggregationParserCollection;
use ElasticFacets\Result\ResultStore;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ElasticFacets
 *
 * @package ElasticFacets
 */
class ElasticFacets implements ElasticFacetsApi {

	private static $request;

	/**
	 * @var AggregationExpressionCollection
	 */
	private $expressions;

	/**
	 * @var AggregationParserCollection
	 */
	private $parsers;

	/**
	 * @var ResultStore
	 */
	private $result_store;

	/**
	 * @param AggregationExpressionCollection $expressions
	 * @param AggregationParserCollection     $parsers
	 * @param ResultStore                     $result_store
	 */
	public function __construct(
		AggregationExpressionCollection $expressions,
		AggregationParserCollection $parsers,
		ResultStore $result_store
	) {

		$this->expressions  = $expressions;
		$this->parsers      = $parsers;
		$this->result_store = $result_store;
	}

	/**
	 * @param AggregationExpression $expression
	 *
	 * @return ElasticFacets
	 */
	public function push_expression( AggregationExpression $expression ) {

		$this->expressions->push_expression( $expression );

		return $this;
	}

	/**
	 * @param array $query
	 *
	 * @return array Associative array with the complete aggregation expressions
	 *               merged into the original query
	 *               [
	 *                  "aggs" => [ [ aggregation expreession], ... ]
	 *               ]
	 */
	public function append_to_query( array $query ) {

		return $this->expressions->append_to_query( $query );
	}

	/**
	 * @param string $id
	 *
	 * @return AggregatedTermsCollection|null (Returns null if no aggregation was found, which probably means ES service is down)
	 */
	public function terms_result( $id ) {

		return $this->result_store->terms_result( $id );
	}

	/**
	 * @param string $id
	 *
	 * @return AggregatedNumericRangesCollection|null (Returns null if no aggregation was found, which probably means ES service is down)
	 */
	public function numeric_ranges_result( $id ) {

		return $this->result_store->numeric_ranges_result( $id );
	}

	/**
	 * @param array $response
	 */
	public function parse_response( array $response ) {

		$this->parsers->parse_response( $response );
	}

	/**
	 * @return ElasticFacets
	 */
	public static function create() {

		$expressions    = new ElasticPress\AggregationExpressionCollection();
		$parser_storage = new ResultStorageParserCollection();

		return new static(
			$expressions,
			$parser_storage,
			$parser_storage
		);
	}

	/**
	 * Used to set the request object once
	 *
	 * @param ServerRequestInterface $request
	 */
	public static function set_request( ServerRequestInterface $request ) {

		if ( self::$request ) {
			throw new \LogicException( "Request was already stored" );
		}

		self::$request = $request;
	}

	/**
	 * @return ServerRequestInterface
	 */
	public static function get_request() {

		if ( ! self::$request ) {
			throw new \LogicException( "Request was not set yet. set_request() must be called before" );
		}

		return self::$request;
	}
}