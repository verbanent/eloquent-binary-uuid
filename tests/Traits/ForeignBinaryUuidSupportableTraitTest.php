<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Verbanent\Uuid\Traits\ForeignBinaryUuidSupportableTrait;

class ForeignBinaryUuidSupportableTraitTest extends TestCase
{
    private $uuid = '11e9469d-b942-b7e6-bc69-8df4f180e5a9';
    private $binaryUuid;
    private $model;

    public function setUp(): void
    {
        $this->binaryUuid = Uuid::fromString($this->uuid)->getBytes();
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver'    => 'sqlite',
            'database'  => ':memory:',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->model = new class() extends Model {
            use ForeignBinaryUuidSupportableTrait;
            protected $table = 'model_test';
            public $timestamps = false;
            protected $fillable = ['uuid'];
        };

        Capsule::schema()->create('model_test', function ($table) {
            $table->uuid('uuid');
        });
    }

    public function testReturnedType()
    {
        $this->assertTrue($this->model->findByUuid('uuid', $this->uuid) instanceof Collection);
    }

    public function testEmptyTable()
    {
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
