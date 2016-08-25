# Inpsyde Elastic Facets

Provide an API to Elasticsearch aggregations to build faceted search forms

## Table Of Contents

* [Installation](#installation)
* [Usage](#usage)
* [Crafted by Inpsyde](#crafted-by-inpsyde)
* [License](#license)
* [Contributing](#contributing)

## Installation

The best way to use this package is through Composer:

```BASH
$ composer require inpsyde/elastic-facets
```

## Usage

`// Todo`

## API

### AggregationFields

`ElasticFacets\AggregationFields\*`

Aggregation fields are kind of _type_ objects that providing field names according to the ES index (defined by ElasticPress in this case) as well as a unique ID that is used to identify a single aggregation. They further provide any information to build an parse a specific type of aggregation even validated query arguments.

 * `SingleAggregationField` is considered to use for an aggregation that only focuses one single field of a document.
 * `NumericRangeAggregationField` is considered to use for an numeric range (between) for a single field.

### Types

Type object that provides aggregation results and other data structures. E.g. a `NumericRange` type provides `min()` and `max()` methods. A `Term` is defined by `id()` and `name()`.

### Query

Interfaces for objects that build the query following ES DSL for aggregations.

### Result

Counterpart for `Query`: these interfaces describes objects that are responsible for parsing the ES result into feasible value objects. (E.g. Term collections, numeric ranges)
 
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

## Developer notes

ES query args: `EP_API::format_args()`, filter `ep_formatted_args`,  (`ep_search_args` in `EP_API::search()`)

ES response: `EP_API::search()`, filter `ep_retrieve_aggregations`

## Crafted by Inpsyde

The team at [Inpsyde](http://inpsyde.com) is engineering the Web since 2006.

## License

Copyright (c) 2016 David Naber, Inpsyde

Good news, this plugin is free for everyone! Since it's released under the [MIT License](LICENSE) you can use it free of charge on your personal or commercial website.

## Contributing

All feedback / bug reports / pull requests are welcome.