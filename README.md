<img src="https://raw.githubusercontent.com/clydescobidal/larasearch/main/art/bannercard.webp" />

# Larasearch

[![Latest Stable Version](http://poser.pugx.org/clydescobidal/larasearch/v)](https://packagist.org/packages/clydescobidal/larasearch)
[![Total Downloads](http://poser.pugx.org/clydescobidal/larasearch/downloads)](https://packagist.org/packages/clydescobidal/larasearch)
[![License](http://poser.pugx.org/clydescobidal/larasearch/license)](https://packagist.org/packages/clydescobidal/larasearch)
![GitHub Actions](https://github.com/clydescobidal/larasearch/actions/workflows/run-tests.yaml/badge.svg)

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
Add the `Clydescobidal\Larasearch\Searchable` trait to the model you would like to make searchable. Models that are using this trait will be indexed in the searchable table whenever changes are made on the model.
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

Search for posts and chain query builder methods as normal:
```php
<?php

$post= Post::search('my post title')->get();
$posts = Post::search('search posts')->paginate();
```

Make model searchable or unsearchable:
```php
<?php

$post= Post::find(1);
$post->searchable(); // Adds this model to the search index

$post= Post::find(2);
$post->unsearchable(); // Removes this model from the search index
```

### Commands
You can run the command below if you want to make all your models searchable. Note that this will only work on models with `Clydescobidal\Larasearch\Searchable` trait. This is applicable when you first install the package and you want your existing models to be searchable, or when you want to do a batch reindex of a model.

In this example, we will make all instances of `App\Models\Post` searchable.
```bash
php artisan make:searchable "App\Models\Post"
```

We can also do a batch unsearchable on a model.
```bash
php artisan make:unsearchable "App\Models\Post"
```

### Config
| Property      | Type |  Default  |  Description |
| ----------- |  ---- | ---| ---
| table    |string|   searchable       | The table where the searchable indices are stored
| cache   |boolean| true       | Enable caching of results
| queue   |boolean| true        | Run searchable syncs in queue (recommended)


### Cache
By default, search query results are cached. You can turn this off by setting the `cache` property in the configuration. In any case you want to clear the cached results, you can run the artisan command:
```bash
php artisan cache:clear --tags="App\Models\Post"
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [Clyde Escobidal](https://github.com/clydescobidal)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
