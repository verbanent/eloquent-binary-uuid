# Creating entities

This example shows how to create records with binary UUIDs and how to handle
foreign keys.

## Bar model

```php
namespace App\Models;

use Verbanent\Uuid\AbstractModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

class Bar extends AbstractModel
{
    public $timestamps = false;
}
```

## Create a parent record

```php
use App\Models\Foo;

$foo = Foo::create(['name' => 'Alpha']);
```

## Create a child record using binary UUID

```php
use App\Models\Bar;

$bar = Bar::create([
    'foo_id' => $foo->id, // already binary(16)
    'title' => 'Child',
]);
```