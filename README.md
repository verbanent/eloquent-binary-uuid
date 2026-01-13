# Ordered binary UUID in Laravel / Eloquent

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=verbanent_eloquent-binary-uuid&metric=alert_status)](https://sonarcloud.io/dashboard?id=verbanent_eloquent-binary-uuid)
[![Downloads](https://img.shields.io/packagist/dt/verbanent/eloquent-binary-uuid.svg)](https://packagist.org/packages/verbanent/eloquent-binary-uuid)
[![StyleCI](https://github.styleci.io/repos/285826449/shield?branch=main)](https://github.styleci.io/repos/285826449?branch=main)
[![CodeFactor](https://www.codefactor.io/repository/github/verbanent/eloquent-binary-uuid/badge/main)](https://www.codefactor.io/repository/github/verbanent/eloquent-binary-uuid/overview/main)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=verbanent_eloquent-binary-uuid&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=verbanent_eloquent-binary-uuid)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=verbanent_eloquent-binary-uuid&metric=ncloc)](https://sonarcloud.io/dashboard?id=verbanent_eloquent-binary-uuid)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=verbanent_eloquent-binary-uuid&metric=coverage)](https://sonarcloud.io/dashboard?id=verbanent_eloquent-binary-uuid)
![Packagist Version](https://img.shields.io/packagist/v/verbanent/eloquent-binary-uuid)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/verbanent/eloquent-binary-uuid)
![Packagist License](https://img.shields.io/packagist/l/verbanent/eloquent-binary-uuid)

Based on articles about the optimization of UUID storage in databases, I decided to write a simple library that allows this in my projects. I based on the information available here:  
https://www.percona.com/blog/2014/12/19/store-uuid-optimized-way/  
https://percona.community/blog/2018/10/12/generating-identifiers-auto_increment-sequence/

The package currently only supports MySQL.

## Installation

Please install the package via Composer:

```bash
composer require verbanent/eloquent-binary-uuid
```

## Basic example

This example keeps things simple: `Foo` and `Bar` both use UUID primary keys,
and `Bar.foo_id` references `Foo.id`.

### Migrations

```php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('foos', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name')->nullable();
    $table->timestamps();
});

Schema::create('bars', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('foo_id');
    $table->string('title')->nullable();
    $table->timestamps();

    $table->foreign('foo_id')->references('id')->on('foos')->cascadeOnDelete();
});
```

### Models

```php
namespace App\Models;

use Verbanent\Uuid\AbstractModel;

class Foo extends AbstractModel
{
    protected $fillable = ['name'];

    public function bars()
    {
        return $this->hasMany(Bar::class, 'foo_id');
    }
}
```

```php
namespace App\Models;

use Verbanent\Uuid\AbstractModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

class Bar extends AbstractModel
{
    protected $fillable = ['title'];

    public function foo()
    {
        return $this->belongsTo(Foo::class, 'foo_id');
    }
}
```

### Usage

```php
$foo = \App\Models\Foo::create(['name' => 'Alpha']);

$bar = new \App\Models\Bar(['title' => 'Child']);
$bar->foo()->associate($foo);
$bar->save();
```

## Examples

More scenarios are documented in the `examples/` directory:

- `examples/01-single-model-uuid-primary-key.md`
- `examples/02-two-models-uuid-pk-and-fk.md`
- `examples/03-creating-entities-with-binary-uuid.md`
- `examples/04-creating-entities-with-string-uuid.md`
- `examples/05-querying.md`

## Unit tests

Run this command if you want to check unit tests:

```shell
./vendor/bin/phpunit
```

Or if you want to check code coverage:

```shell
phpdbg -qrr vendor/bin/phpunit --coverage-html coverage tests
```
