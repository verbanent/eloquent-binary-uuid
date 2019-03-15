<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
USE Verbanent\Uuid\Test\SetUpTrait;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

class ForeignBinaryUuidSupportableTraitTest extends SetUpTrait
{
    public function setUp(): void
    {
        parent::setUp();

        $this->model = new class() extends Model {
            use ForeignBinaryUuidSupportableTrait;
            protected $table = 'model_test';
            public $timestamps = false;
            protected $fillable = ['uuid'];
        };
    }

    public function testReturnedType()
    {
        $this->assertTrue($this->model->findByUuid('uuid', $this->uuid) instanceof Collection);
    }

    public function testReturnedModelType()
    {
        $this->model::create(['uuid' => $this->binaryUuid]);
        $this->assertTrue($this->model->findByUuid('uuid', $this->uuid)->first() instanceof Model);
    }

    public function testEmptyTable()
    {
        $this->assertEmpty($this->model->findByUuid('uuid', $this->uuid));
        $this->assertEquals(0, count($this->model->findByUuid('uuid', $this->uuid)));
    }

    public function testOneInTable()
    {
        $this->model::create(['uuid' => $this->binaryUuid]);
        $this->assertEquals(1, count($this->model->findByUuid('uuid', $this->uuid)));
    }

    public function testElementInCollection()
    {
        $this->model::create(['uuid' => $this->binaryUuid]);
        $this->assertEquals($this->binaryUuid, $this->model->findByUuid('uuid', $this->uuid)->first()->uuid);
    }

    public function testManyInTable()
    {
        $this->model::create(['uuid' => $this->binaryUuid]);
        $this->model::create(['uuid' => $this->binaryUuid]);
        $this->model::create(['uuid' => $this->binaryUuid]);

        $this->assertEquals(3, count($this->model->findByUuid('uuid', $this->uuid)));
    }
}
