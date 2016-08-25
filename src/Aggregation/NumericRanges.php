<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\Query\AggregationExpression;
use ElasticFacets\Result\ParseNumericRangesAggregation;

/**
 * Interface NumericRanges
 *
 * @package ElasticFacets\Aggregation
 */
interface NumericRanges extends AggregationExpression, ParseNumericRangesAggregation {}