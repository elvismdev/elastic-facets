<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\Query\AggregationExpression;
use ElasticFacets\Result\ParseNumericRangesAggregation;

/**
 * Interface NumericRange
 *
 * @package ElasticFacets\Aggregation
 */
interface NumericRange extends AggregationExpression, ParseNumericRangesAggregation {}