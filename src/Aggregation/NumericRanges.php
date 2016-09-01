<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\Query\AggregationExpression;
use ElasticFacets\Result\ParseNumericRangesAggregation;

/**
 * Interface NumericRanges
 *
 * Handle an AggregationExpression and Parser in one instance. One good friend
 * (for the registry) is better than many.
 *
 * @package ElasticFacets\Aggregation
 */
interface NumericRanges extends AggregationExpression, ParseNumericRangesAggregation {}