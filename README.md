# A paginator that plays nice with the JSON API spec

This package is just [ElasticScoutDriverPlus](https://github.com/Jackardios/elastic-scout-driver-plus/) extension for [spatie/laravel-json-api-paginate](https://github.com/spatie/laravel-json-api-paginate)

In a vanilla Laravel application [the query builder paginators will listen to `page` request parameter](https://laravel.com/docs/master/pagination#paginating-query-builder-results). This works great, but it does follow the example solution of [the json:api spec](http://jsonapi.org/). That example [expects](http://jsonapi.org/examples/#pagination) the query builder paginator to listen to the `page[number]` and `page[size]` request parameters. 

This package adds a `jsonPaginate` method to the Scout query builder that listens to those parameters and adds [the pagination links the spec requires](http://jsonapi.org/format/#fetching-pagination).

## Installation

You can install the package via composer:

```bash
composer require jackardios/elastic-json-api-paginate
```

In Laravel 5.5 and above the service provider will automatically get registered. In older versions of the framework just add the service provider in `config/app.php` file:

```php
'providers' => [
    ...
    Spatie\JsonApiPaginate\JsonApiPaginateServiceProvider::class,
    Jackardios\ElasticJsonApiPaginate\JsonApiPaginateServiceProvider::class,
];
```

Optionally you can publish the config file with:

```bash
php artisan vendor:publish --provider="Spatie\JsonApiPaginate\JsonApiPaginateServiceProvider" --tag="config"
```

This is the content of the file that will be published in `config/json-api-paginate.php`

```php
<?php

return [

    /*
     * The maximum number of results that will be returned
     * when using the JSON API paginator.
     */
    'max_results' => 30,

    /*
     * The default number of results that will be returned
     * when using the JSON API paginator.
     */
    'default_size' => 30,

    /*
     * The key of the page[x] query string parameter for page number.
     */
    'number_parameter' => 'number',

    /*
     * The key of the page[x] query string parameter for page size.
     */
    'size_parameter' => 'size',

    /*
     * The name of the macro that is added to the Scout query builder.
     */
    'method_name' => 'jsonPaginate',

    /*
     * If you only need to display Next and Previous links, you may use
     * simple pagination to perform a more efficient query.
     *
     * THIS CONFIGURATION IS NOT SUPPORTED IN jackardios/elastic-json-api-paginate
     */
    'use_simple_pagination' => false,

    /*
     * Here you can override the base url to be used in the link items.
     */
    'base_url' => null,

    /*
     * The name of the query parameter used for pagination
     */
    'pagination_parameter' => 'page',
];
```

## Usage

To paginate the results according to the json API spec, simply call the `jsonPaginate` method.

```php
YourModel::searchQuery(...)->jsonPaginate();
```

Of course you may still use all the builder methods you know and love:

```php
YourModel::searchQuery(...)->sort('my_field', 'desc')->jsonPaginate();
```

By default the maximum page size is set to 30. You can change this number in the `config` file or just pass the value to  `jsonPaginate`.

```php
$maxResults = 60;

YourModel::searchQuery(...)->jsonPaginate($maxResults);
```

By default ElasticScoutDriverPlus paginates raw results, if you want to paginate models, call the `onlyModels` method after `jsonPaginate`

```php
YourModel::searchQuery(...)->jsonPaginate()->onlyModels();
```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
