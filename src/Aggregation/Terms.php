<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\Query\AggregationExpression;
use ElasticFacets\Result\ParseTermsAggregation;

/**
 * Interface Terms
 *
 * Handle an AggregationExpression and Parser in one instance. One good friend
 * (for the registry) is better than many.
 *
 * @package ElasticFacets\Aggregation
 */
interface Terms extends AggregationExpression, ParseTermsAggregation {}