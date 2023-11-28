<img src="https://raw.githubusercontent.com/clydescobidal/larasearch/main/art/bannercard.webp" />

# Larasearch

[![Latest Version on Packagist](https://img.shields.io/packagist/v/clydescobidal/larasearch.svg?style=flat-square)](https://packagist.org/packages/clydescobidal/larasearch)
[![Total Downloads](https://img.shields.io/packagist/dt/clydescobidal/larasearch.svg?style=flat-square)](https://packagist.org/packages/clydescobidal/larasearch)
![GitHub Actions](https://github.com/clydescobidal/larasearch/actions/workflows/main.yaml/badge.svg)

The goal of this Laravel package is to offer fast FULLTEXT indexed searches. This is only relevant if you wish to include a basic search feature in your project. However, if your project has a large amount of data that needs to be searched and is frequently used, search engines like Typesense, ElasticSearch, Algolia, and similar ones are more appropriate.

Search queries are executed on the searchable table to save your main table from the heavy search workload. 

## Features

- FULLTEXT index search
- Cached results

## Installation

You can install the package via composer:

```bash
composer require clydescobidal/larasearch
```

Publish the configuration file:
```bash
php artisan vendor:publish --provider="Clydescobidal\Larasearch\LarasearchServiceProvider"
```

Run migration:
```bash
php artisan migrate
```

## Usage
Add the `Clydescobidal\Larasearch\Searchable` trait to the model you would like to make searchable:
```php
<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Clydescobidal\Larasearch\Searchable;
 
class Post extends Model
{
    use Searchable;
}
```


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email cleidoscope@gmail.com instead of using the issue tracker.

## Credits

-   [Clyde Escobidal](https://github.com/clydescobidal)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
