# Ordered binary UUID in Laravel / Eloquent

Based on articles about the optimization of UUID storage in databases, I decided to write a simple library that allows this in my projects. I based on the information available here:  
https://www.percona.com/blog/2014/12/19/store-uuid-optimized-way/  
https://www.percona.com/community-blog/2018/10/12/generating-identifiers-auto_increment-sequence/

The package currently only supports MySQL.

## Installation

Please install the package via Composer:

```bash
composer require verbanent/eloquent-binary-uuid
```

### Migration

Your model will use an ordered binary UUID, if you prepare a migration:

```php
Schema::create('table_name', function (Blueprint $table) {
    $table->uuid('uuid');
    $table->primary('uuid');
});
```

### Using UUID in models

All what you have to do, is use a new trait in models with UUID as a primary key:

```php
use Illuminate\Database\Eloquent\Model;
use Verbanent\Uuid\Traits\BinaryUuidSupportableTrait;

class Book extends Model
{
    use BinaryUuidSupportableTrait;
}
```

### Getting a string form of UUID

The library is kept as simple as possible, so if you want to get a string form of UUID, just use a method:

```php
$book = new \App\Book;
$book->save();
dd($book->uuid());
```

or use a property, if you need a binary value:

```php
dd($book->uuid);
```

