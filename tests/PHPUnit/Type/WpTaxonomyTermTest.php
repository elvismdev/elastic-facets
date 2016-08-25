<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

use Brain\Monkey;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;

/**
 * Class WpTaxonomyTermTest
 *
 * @package ElasticFacets\Type
 */
class WpTaxonomyTermTest extends BrainMonkeyWpTestCase {

	/**
	 * @see WpTaxonomyTerm::id()
	 */
	public function test_id() {

		$id     = 42;
		$testee = new WpTaxonomyTerm( $id );

		$this->assertSame(
			$id,
			$testee->id()
		);
	}

	/**
	 * @see WpTaxonomyTerm::name()
	 */
	public function test_name() {

		$id   = 42;
		$name = 'Hello World!';

		$testee = new WpTaxonomyTerm( $id, $name );
		$this->assertSame(
			$name,
			$testee->name()
		);
	}

	/**
	 * @see WpTaxonomyTerm::create_from_wp_term()
	 */
	public function test_create_from_wp_term() {

		$term_id            = 42;
		$term_name          = 'Hello World';
		$term_mock          = Mockery::mock( 'WP_Term' );
		$term_mock->term_id = $term_id;
		$term_mock->name    = $term_name;

		$testee = WpTaxonomyTerm::create_from_wp_term( $term_mock );

		$this->assertInstanceOf(
			WpTaxonomyTerm::class,
			$testee
		);
		$this->assertSame(
			$term_id,
			$testee->id()
		);
		$this->assertSame(
			$term_name,
			$testee->name()
		);
	}
}