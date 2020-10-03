# Ordered binary UUID in Laravel / Eloquent

[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=verbanent_eloquent-binary-uuid&metric=alert_status)](https://sonarcloud.io/dashboard?id=verbanent_eloquent-binary-uuid)
[![Downloads](https://img.shields.io/packagist/dt/verbanent/eloquent-binary-uuid.svg)](https://packagist.org/packages/verbanent/eloquent-binary-uuid)
[![StyleCI](https://github.styleci.io/repos/285826449/shield?branch=master)](https://github.styleci.io/repos/285826449)
[![CodeFactor](https://www.codefactor.io/repository/github/verbanent/eloquent-binary-uuid/badge)](https://www.codefactor.io/repository/github/verbanent/eloquent-binary-uuid)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=verbanent_eloquent-binary-uuid&metric=sqale_rating)](https://sonarcloud.io/dashboard?id=verbanent_eloquent-binary-uuid)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=verbanent_eloquent-binary-uuid&metric=ncloc)](https://sonarcloud.io/dashboard?id=verbanent_eloquent-binary-uuid)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=verbanent_eloquent-binary-uuid&metric=coverage)](https://sonarcloud.io/dashboard?id=verbanent_eloquent-binary-uuid)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/verbanent/eloquent-binary-uuid)
![Packagist License](https://img.shields.io/packagist/l/verbanent/eloquent-binary-uuid)

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

#### Abstract model for model with UUID

For your convenience you can extend your model with _AbstractModel_:

```php
use Verbanent\Uuid\AbstractModel;

class Lang extends AbstractModel
{

}
```

#### Foreign binary UUID

If you would like to use UUID as a foreign key, use another trait and set _$uuidable_ property for this model:

```php
use Verbanent\Uuid\AbstractModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

class LangTranslation extends AbstractModel
{
    use ForeignBinaryUuidSupportableTrait;

    private $uuidable = [
        'lang',
        'one_lang_bucket',
    ];
}
```

### Getting a string form of UUID

The library is kept as simple as possible, so if you want to get a string form of UUID, just use a method:

```php
$book = new \App\Book;
$book->save();
dd($book->uuid());
// Output: "11e947f9-a1bd-f844-88d8-6030d483c5fe"
```

or use a property, if you need a binary value:

```php
dd($book->uuid);
// Output: b"\x11éGù¡½øDˆØ`0ÔƒÅþ"
```

### Finding by primary UUID

For primary keys finding rows is simple and always return a model:

```php
$lang = Lang::find('11e947f9-a1bd-f844-88d8-6030d483c5fe');
dd($lang->uuid());
// Output: "11e947f9-a1bd-f844-88d8-6030d483c5fe"
```

### Finding by foreign UUID

For foreign keys finding rows requires a column name and returns collection of model:

```php
$langTranslation = LangTranslation::findByUuid('lang', '11e947f9-a1bd-f844-88d8-6030d483c5fe');
dd($langTranslation[0]->uuid(), $langTranslation[1]->uuid(), $langTranslation[2]->uuid());
// Output: "11e94805-b94c-68e0-8720-6030d483c5fe"
//         "11e94805-b955-4e2e-b089-6030d483c5fe"
//         "11e94805-b957-af02-8bf8-6030d483c5fe"
```

### Getting a foreign UUID string

You can print string form of your foreign UUID keys:

```php
$translation = LangTranslation::findByUuid('lang', '11e947f9-a1bd-f844-88d8-6030d483c5fe')->first();
dd($translation->foreignUuid('lang'));
// Output: "11e947f9-a1bd-f844-88d8-6030d483c5fe"
```

Because trying to have an access to the property directly will print binary form of UUID: 

```php
dd($translation->lang);
// Output: b"\x11éGù¡½øDˆØ`0ÔƒÅþ"
```
