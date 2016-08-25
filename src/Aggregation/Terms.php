<?php # -*- coding: utf-8 -*-

namespace ElasticFacets\Aggregation;

use ElasticFacets\Query\AggregationExpression;
use ElasticFacets\Result\ParseTermsAggregation;

/**
 * Interface Terms
 *
 * @package ElasticFacets\Aggregation
 */
interface Terms extends AggregationExpression, ParseTermsAggregation {}