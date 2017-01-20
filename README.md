# Inpsyde Elastic Facets

**Package under construction. API and architecture is likely to be changed!** Keep an eye on the [CHANGELOG.md](https://github.com/inpsyde/elastic-facets/blob/master/CHANGELOG.md)

Provides an API to Elasticsearch (ES) aggregations to build faceted search forms. The plugin actually is build on top of [Elasticpress](https://github.com/10up/ElasticPress) even though the API to the aggregation expressions could be used with other Elasticsearch integrations. However, all examples assumes that you have Elasticpress enabled and indexed.

## Table Of Contents

* [Installation](#installation)
* [Usage](#usage)
* [Crafted by Inpsyde](#crafted-by-inpsyde)
* [License](#license)
* [Contributing](#contributing)

## Installation

The best way to use this package is through Composer:

```
$ composer require inpsyde/elastic-facets:dev-master
```

## Usage

### Aggregations for the main query

Example: Calculate term aggregations for default taxonomies (`post_tag` and `category`) on default search query.
```php

namespace Example;

use ElasticFacets\ElasticFacetsApi;
use ElasticFacets\Aggregation\SingleFieldTerms;
use ElasticFacets\AggregationField\EpTermTaxonomyAggregationField;
use Psr\Http\Message\ServerRequestInterface;
use WP_Query;

/**
 * Register aggregations (runs during `pre_get_posts`of the main query)
 */
add_action(
    'elastic_facets.register_aggregation',
    function( ElasticFacetsApi $ef, ServerRequestInterface $request, WP_Query $query ) {

        if ( ! $query->is_search() ) {
            return;
        }
        $cat_id = 'category';
        $tag_id = 'post_tag';
        $ef->add_aggregation(
            $cat_id,
            new SingleFieldTerms(
                new EpTermTaxonomyAggregationField( 'category' )
            )
        );
        $ef->add_aggregation(
            $tag_id,
            new SingleFieldTerms(
                new EpTermTaxonomyAggregationField( 'post_tag' )
            )
        );
    }
);

/**
 * Somewhen after `pre_get_posts`, probably in the theme
 */
$ef = apply_filters( 'elastic_facets.get_registry', FALSE );
if ( ! $ef instanceof ElasticFacetsApi ) {
    return;
}

$category_terms = $ef->get_aggregates( $cat_id );
if ( $category_terms ) {
    foreach ( $category_terms->terms() as $term ) {
        $term->id();   // Numeric ID from the ES index, identical to the WP term ID
        $term->name(); // Term name
        $count = $category_terms->count( $term ); // Counted documents for this term
    }
}

// Same for post_tag terms
```

### Aggregations for custom queries

Example: Query for a WooCommerce product category term `shoes` and calculate term aggregations for the product attributes (WooCommerce taxonomies) `pa_color` and `pa_size` as well as the min and max price (post meta).

```php

namespace Example;

use ElasticFacets\Aggregation\AbsoluteNumericRange;
use ElasticFacets\Aggregation\SingleFieldTerms;
use ElasticFacets\AggregationField\EpTermTaxonomyAggregationField;
use ElasticFacets\AggregationField\RequestNumericRangeField;
use ElasticFacets\ElasticFacets;
use ElasticFacets\Type\AggregatedNumericRangesCollection;
use ElasticFacets\Type\AggregatedTermsCollection;
use WP_Query;

$ef = ElasticFacets::create();
$ef
    ->add_aggregation(
        'color',
        new SingleFieldTerms(
            new EpTermTaxonomyAggregationField( 'pa_color' )
        )
    )
    ->add_aggregation(
        'size',
        new SingleFieldTerms(
            new EpTermTaxonomyAggregationField( 'pa_size' )
        )
    )
    ->add_aggregation(
        'price',
        new AbsoluteNumericRange(
            new RequestNumericRangeField(
                'meta._price.double',
                ElasticFacets::get_request(),
                'price_range'
            )
        )
    );

$query = new WP_Query(
    [
        'post_type' => [ 'product' ],
        'tax_query' => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => [ 'shoes' ]
            ]
        ],
        'posts_per_page' => 5,
        'elastic_facets' => $facets,
        'ep_integrate'   => TRUE
    ]
);

/* @var AggregatedTermsCollection | null $size */
$sizes = $ef->get_aggregates( 'size' );

/* @var AggregatedTermsCollection | null $colors */
$colors = $ef->get_aggregates( 'colors' );

/* @var AggregatedNumericRangesCollection | null $range_list */
$range_list = $ef->get_aggregates( 'price' );
if ( $range_list && ! empty( $range_list->ranges() ) ) {
    $price_range = current( $range_list->ranges() );
    $price_range->min(); // Min price
    $price_range->max(); // Max price
    $range_list->count( $price_range ); // Number of products in this range
}
```

The `RequestNumericRangeField` fetches its min/max values form a given `ServerRequestInterface` object using the request name (3rd parameter). This might be useful when the aggregations are combined with a filter for a specific price range.

```php
<?php
$field = new RequestNumericRangeField(
    'meta._price.double',
    ElasticFacets::get_request(),
    'price_range'
);
?>

<label for="min">Min</label>
<input type="number" name="price_range[]" id="min" value="<?= esc_attr( $field->min() ) ?>" />
<label for="max">Max</label>
<input type="number" name="price_range[]" id="max" value="<?= esc_attr( $field->max() ) ?>" />
```

## Elasticsearch aggregations

The way aggregations are calculated highly depends on the ES [query context type](https://www.elastic.co/guide/en/elasticsearch/reference/5.1/query-filter-context.html). For filters (filter context), aggregations are calculated on _all_ documents of the entire index. This is typically not what you want to build a facet filter search. However, to limit the calculation of the aggregations to the subset of documents that matches the filter criteria you should use query context.

Example:
```
{
    "filter": [
        {
            "term": {
                "post_status": "publish"
            }
        }
    ],
    "aggregations": {
        "category": {
            "terms": {
                "field": "term.category.term_id"
            }
        }
    }
}
```
A query like the one above will calculate the term aggregations (how many documents per term id) for the complete index, not only for the published ones.

To limit the aggregations to the matching documents, you need to use a query context:

```
{
    "query": {
        "filtered": {
            "filter": [
                {
                    "term": {
                        "post_status": "publish"
                    }
                }
            ]
        }
    },
    "aggregations": {
        "category": {
            "terms": {
                "field": "term.category.term_id"
            }
        }
    }
}
```

ElasticPress maps WP_Query args to ES queries which sometimes uses filter context. If you want to build aggregations upon these queries, you have to transform these filter contexts into filtered queries:

```php

add_filter(
    'ep_formatted_args',
    function( array $es_query, array $wp_query_args ) {

        if ( empty( $es_query[ 'aggs' ] ) || empty( $es_query[ 'filter' ] ) ) {
            return $es_query; //Don't touch queries that don't aggregate or has no filter
        }

        $es_query[ 'query' ] = [
            'filtered' => [
                'query' => $es_query[ 'query' ],
                'filter' => $es_query[ 'filter' ]
            ]
        ];
        unset( $es_query[ 'filter' ] );

        return $es_query;
    },
    20,
    2
}
```

## Domain language

### AggregationFields

`ElasticFacets\AggregationFields\*`

Aggregation fields are kind of _type_ objects that provides field names according to the ES index (defined by ElasticPress in this case) as well as a unique ID that is used to identify a single aggregation in the query. They further provide any information to build and parse a specific type of aggregation even validated request arguments.

 * `SingleAggregationField` is considered to use for an aggregation that only focuses one single field of a document.
 * `NumericRangeAggregationField` is considered to use for an numeric range (between) for a single field.

### Types

Type object provides aggregation results and other data structures. E.g. a `NumericRange` type provides `min()` and `max()` methods. A `Term` is defined by `id()` and `name()`.

### Query

Objects that build the query following ES DSL for aggregations.

### Result

Counterpart for `Query`: these objects are responsible for parsing the ES result into feasible value objects. (E.g. Term collections, numeric ranges)
 
### Aggregation
 
Implementors of Query and Result interfaces. Builds queries along ES DSL and parses ES responses.

 * `AbsoluteNumericRange` aggregates min/max values of a numeric field. (ES Min/Max aggregation)
 * `ArbitraryNumericRange` aggregates a range (count documents within a given range) (ES Range aggregation)
 * `SingleFieldTerms` aggregates terms. (ES Term aggregation)


## Testing
In order to run the tests you need to install PHPUnit globally on your system or use PhiVE to install it:

```
$ phive install
```
To run unit tests then use this command:
```
$ bin/phpunit 
```
(or `$ phpunit` if installed globally)

A code coverage report is stored in `tests/coverage.log`.

### Mutation testing

If you have humbug installed on your system (it's not supported yet by PhiVE) you can run it like this:

```
$ humbug
```
It will analyze all files in `src/` and create a report in `tests/humbug.log`.


## Crafted by Inpsyde

The team at [Inpsyde](http://inpsyde.com) is engineering the Web since 2006.

## License

Copyright (c) 2016 David Naber, Inpsyde

Good news, this plugin is free for everyone! Since it's released under the [MIT License](LICENSE) you can use it free of charge on your personal or commercial website.

## Contributing

All feedback / bug reports / pull requests are welcome.