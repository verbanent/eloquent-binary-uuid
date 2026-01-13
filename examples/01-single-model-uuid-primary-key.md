# Single model with UUID primary key

This example shows a single model using binary UUIDs as the primary key.

## Migration

```php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('foos', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name')->nullable();
});
```

## Model

```php
namespace App\Models;

use Verbanent\Uuid\AbstractModel;

class Foo extends AbstractModel
{
    public $timestamps = false;
}
```

## What happens

- The package stores UUIDs as binary(16) via the custom schema grammar.
- A UUID is generated automatically on model creation.
