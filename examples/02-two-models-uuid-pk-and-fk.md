# Two models with UUID primary keys and foreign key

This example shows two models where both use UUID primary keys and the child
model stores a UUID foreign key.

## Migrations

```php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('foos', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name')->nullable();
});

Schema::create('bars', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('foo_id');
    $table->string('title')->nullable();

    $table->foreign('foo_id')->references('id')->on('foos')->cascadeOnDelete();
});
```

## Models

```php
namespace App\Models;

use Verbanent\Uuid\AbstractModel;

class Foo extends AbstractModel
{
    public $timestamps = false;

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
    use ForeignBinaryUuidSupportableTrait;

    public $timestamps = false;

    // Convert string UUIDs to binary for these columns on create.
    protected $uuidable = ['foo_id'];

    public function foo()
    {
        return $this->belongsTo(Foo::class, 'foo_id');
    }
}
```
