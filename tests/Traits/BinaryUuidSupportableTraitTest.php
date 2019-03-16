<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Verbanent\Uuid\AbstractModel;
use Verbanent\Uuid\Test\SetUpTrait;
use Verbanent\Uuid\Traits\BinaryUuidSupportableTrait;

class BinaryUuidSupportableTraitTest extends SetUpTrait
{
    public function setUp(): void
    {
        parent::setUp();

        $this->model = new class() extends AbstractModel {
            use BinaryUuidSupportableTrait;
            protected $table = 'model_test';
            public $timestamps = false;
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

    public function testSetOwnUuid()
    {
        $uuid = 'f326aa08-47e7-11e9-8f58-f3ba25528813';
        $model = new $this->model([
            'uuid' => $uuid,
        ]);
        $this->assertEquals($uuid, $model->uuid);
        $model->save();
        $this->assertEquals($uuid, $model->uuid);
    }
}
