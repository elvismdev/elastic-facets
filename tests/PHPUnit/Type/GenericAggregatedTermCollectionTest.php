<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Type;

use MonkeryTestCase\BrainMonkeyWpTestCase;
use Mockery;

class GenericAggregatedTermCollectionTest extends BrainMonkeyWpTestCase {

	/**
	 * @see ConcreteAggregatedTermCollection::terms()
	 */
	public function test_terms() {

		$terms  = [
			$this->build_term_mock( 12 ),
			$this->build_term_mock( 13 ),
			$this->build_term_mock( 14 )
		];
		$testee = new GenericAggregatedTermCollection( $terms, [ ] );

		$this->assertSame(
			$terms,
			$testee->terms()
		);
	}

	/**
	 * @see ConcreteAggregatedTermCollection::terms()
	 */
	public function test_invalid_terms_gets_striped() {

		$term_1 = $this->build_term_mock( 1 );
		$term_2 = $this->build_term_mock( 2 );
		$term_3 = $this->build_term_mock( 3 );
		$testee = new GenericAggregatedTermCollection(
			[ $term_1, 'foo', $term_2, Mockery::mock( NumericRange::class ), $term_3 ],
			[ ]
		);

		$this->assertSame(
			[ $term_1, $term_2, $term_3 ],
			$testee->terms()
		);
	}

	/**
	 * @see ConcreteAggregatedTermCollection::count()
	 */
	public function test_count() {

		$terms = [
			$this->build_term_mock( 4 ),
			$this->build_term_mock( 26 ),
			$this->build_term_mock( 13 ),
			$this->build_term_mock( 55 )
		];
		$count = [
			3, 5, 17, 5
		];
		$testee = new GenericAggregatedTermCollection( $terms, $count );

		$this->assertSame(
			$count[ 0 ],
			$testee->count( $terms[ 0 ] )
		);
		$this->assertSame(
			$count[ 1 ],
			$testee->count( $terms[ 1 ] )
		);
		$this->assertSame(
			$count[ 2 ],
			$testee->count( $terms[ 2 ] )
		);
		$this->assertSame(
			$count[ 3 ],
			$testee->count( $terms[ 3 ] )
		);
	}

	/**
	 * @see ConcreteAggregatedTermCollection::count()
	 */
	public function test_count_missing_value() {

		$terms = [
			$this->build_term_mock( 4 ),
			$this->build_term_mock( 26 )
		];
		$count = [
			4
		];
		$testee = new GenericAggregatedTermCollection( $terms, $count );

		$this->assertSame(
			$count[ 0 ],
			$testee->count( $terms[ 0 ] )
		);
		$this->assertSame(
			0,
			$testee->count( $terms[ 1 ] )
		);
	}

	/**
	 * @param mixed $id
	 * @param mixed $name
	 *
	 * @return Term
	 */
	private function build_term_mock( $id, $name = '' ) {

		$term_mock = Mockery::mock( Term::class );
		$term_mock->shouldReceive( 'id' )
			->andReturn( $id );
		if ( $name ) {
			$term_mock->shouldReceive( 'name' )
				->andReturn( $name );
		}

		return $term_mock;
	}
}
