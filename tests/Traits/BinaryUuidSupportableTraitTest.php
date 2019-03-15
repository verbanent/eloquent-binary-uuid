<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Illuminate\Database\Eloquent\Model;
use Verbanent\Uuid\Test\SetUpTrait;
use Verbanent\Uuid\Traits\BinaryUuidSupportableTrait;

class BinaryUuidSupportableTraitTest extends SetUpTrait
{
    public function setUp(): void
    {
        parent::setUp();

        $this->model = new class() extends Model {
            use BinaryUuidSupportableTrait;
            protected $table = 'model_test';
            public $timestamps = false;
            protected $fillable = ['uuid'];
        };
    }

    public function testNotEmptyTable()
    {
        $model = new $this->model();
        $model->save();
        $this->assertNotEmpty($this->model->find($model->uuid()));
        $this->assertEquals($model->uuid(), $this->model->find($model->uuid())->uuid());
    }

    public function testResetUuid()
    {
        $model = new $this->model();
        $model->uuid = null;
        $this->assertNull($model->uuid);
    }
}
