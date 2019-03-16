<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Verbanent\Uuid\AbstractModel;
use Verbanent\Uuid\Test\SetUpTrait;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

class ForeignBinaryUuidSupportableTraitTest extends SetUpTrait
{
    public function setUp(): void
    {
        parent::setUp();

        $this->model = new class() extends AbstractModel {
            use ForeignBinaryUuidSupportableTrait;
            protected $table = 'model_test';
            public $timestamps = false;
            protected $fillable = ['uuid', 'foreignUuid'];
            private $uuidable = ['foreignUuid'];
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
        $this->model::create(['uuid' => $this->model->generateUuid()]);
        $this->model::create(['uuid' => $this->model->generateUuid()]);
        $this->model::create(['uuid' => $this->model->generateUuid()]);

        $this->assertEquals(3, count($this->model::all()));
    }

    public function testCreatingModel()
    {
        $this->model::create([
            'uuid' => $this->uuid,
            'foreignUuid' => $this->binaryUuid,
        ]);

        $foundCollection = $this->model::findByUuid('foreignUuid', $this->uuid);
        $this->assertTrue($foundCollection instanceof Collection);
        $this->assertEquals(1, count($foundCollection));
        $this->assertEquals($this->uuid, $foundCollection[0]->foreignUuid('foreignUuid'));
    }
}
