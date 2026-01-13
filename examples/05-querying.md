# Querying by UUID

These examples show how to query records when UUIDs are stored as binary.

## Find by primary UUID (string)

`BinaryUuidSupportableTrait` overrides `find()` to accept a UUID string.

```php
use App\Models\Foo;

$foo = Foo::find('9d2e6a5c-8f1f-4e39-9ce6-6a4f0a9e3d12');
```

## Find by foreign UUID (string)

`ForeignBinaryUuidSupportableTrait` provides `findByUuid()` for foreign columns.

```php
use App\Models\Bar;

$bars = Bar::findByUuid('foo_id', '9d2e6a5c-8f1f-4e39-9ce6-6a4f0a9e3d12');
```

## Manual conversion

If you want to build queries yourself, you can convert a UUID string to binary:

```php
use App\Models\Foo;

$binary = Foo::encodeUuid('9d2e6a5c-8f1f-4e39-9ce6-6a4f0a9e3d12');
$foo = Foo::where('id', $binary)->first();
```
