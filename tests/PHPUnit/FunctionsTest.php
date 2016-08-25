<?php # -*- coding: utf-8 -*-

namespace ElasticFacets;

use MonkeryTestCase\BrainMonkeyWpTestCase;

class FunctionsTest extends BrainMonkeyWpTestCase{

	/**
	 * @see string_to_number()
	 * @dataProvider string_to_number_test_data
	 * 
	 * @param $input
	 * @param $expected
	 */
	public function test_string_to_number( $input, $expected ) {

		$this->assertSame(
			$expected,
			string_to_number( $input )
		);
	}

	/**
	 * @see test_string_to_number
	 */
	public function string_to_number_test_data() {
		
		return [
			"int_to_int" => [
				10,
				10
			],
			"negattive_int_to_int" => [
				-42,
				-42
			],
			"int_max" => [
				PHP_INT_MAX,
				PHP_INT_MAX
			],
			"negative_inf" => [
				- INF,
				- INF
			],
			"float_to_float" => [
				14.5,
				14.5
			],
			"negative_float_to_float" => [
				-55.0,
				-55.0
			],
			"string_to_int" => [
				"584",
				584
			],
			"negattive_string_to_int" => [
				"-6",
				-6
			],
			"string_to_float" => [
				"42.12",
				42.12
			],
			"negattive_string_to_float" => [
				"-42.12",
				-42.12
			],
			"round_string_to_float" => [
				"10.0000000000",
				(float) 10
			]
		];
	}
}