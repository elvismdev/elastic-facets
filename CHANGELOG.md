# Change Log
All notable changes to this project will be documented in this file.
This project adheres to [Semantic Versioning](http://semver.org/).

## [Unreleased]

### Changed
* Pass ElasticFacet API objects via query arguments to `WP_Query`
* Don't initialize plugin on `parse_query` but on `wp_loaded`

### Added
* Interface `ElasticFacets\ElasticFacetsApi`
* Implementation of that interface: `ElasticFacets\ElasticFacets`


## [1.0.0-alpha1 (02.12.2016)]

### Added

* First shot of the public API for aggregations as described in the README.md
* Composer support: `inpsyde/elastic-facets`
* README.md
* CHANGELOG.md

[Unreleased]: https://github.com/inpsyde/elastic-facets/compare/1.0.0-alpha1...master
[1.0.0-alpha1 (02.12.2016)]: https://github.com/inpsyde/elastic-facets/tree/1.0.0-alpha1
