<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\AggregationField;

use MonkeryTestCase\BrainMonkeyWpTestCase;

/**
 * Class EpTermTaxonomyAggregationFieldTest
 *
 * @package ElasticFacets\Type
 */
class EpTermTaxonomyAggregationFieldTest extends BrainMonkeyWpTestCase {

	/**
	 * @see EsTermTaxonomyAggregation::id()
	 */
	public function test_id() {

		$id = 'my_aggregation';
		$testee = new EpTermTaxonomyAggregationField( '', $id );

		$this->assertSame(
			$id,
			$testee->id()
		);
	}

	/**
	 * @see EsTermTaxonomyAggregation::id()
	 */
	public function test_generated_id() {

		$taxonomy = 'category';
		$testee = new EpTermTaxonomyAggregationField( $taxonomy );

		$this->assertNotEquals(
			$taxonomy,
			$testee->id()
		);
		$this->assertStringStartsWith(
			$taxonomy,
			$testee->id()
		);
	}

	/**
	 * @see EsTermTaxonomyAggregation::id()
	 */
	public function test_unique_id() {

		$taxonomy = 'category';
		$testee1 = new EpTermTaxonomyAggregationField( $taxonomy );
		$testee2 = new EpTermTaxonomyAggregationField( $taxonomy );

		$this->assertNotEquals(
			$testee1->id(),
			$testee2->id()
		);
	}

	/**
	 * @see EsTermTaxonomyAggregation::field()
	 */
	public function test_field() {

		$taxonomy = 'category';
		$field    = 'terms.category.term_id';
		$testee = new EpTermTaxonomyAggregationField( $taxonomy );

		$this->assertSame(
			$field,
			$testee->field()
		);
	}


}
