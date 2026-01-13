# Creating entities with string UUID

This example shows how to create records with string UUIDs.

## Bar model (uuidable configuration)

```php
namespace App\Models;

use Verbanent\Uuid\AbstractModel;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

class Bar extends AbstractModel
{
    use ForeignBinaryUuidSupportableTrait;

    public $timestamps = false;

    // Columns that should accept string UUIDs and be converted to binary.
    protected $uuidable = ['foo_id'];
}
```

## Create a parent record

```php
use App\Models\Foo;

$foo = Foo::create(['name' => 'Alpha']);
```

## Create a child record using string UUID

If `Bar` uses `ForeignBinaryUuidSupportableTrait` and has `foo_id` in
`$uuidable`, you can pass a string UUID and it will be converted on create.

```php
$bar = Bar::create([
    'foo_id' => $foo->uuid(), // string UUID, auto-converted to binary
    'title' => 'Child as string',
]);
```
