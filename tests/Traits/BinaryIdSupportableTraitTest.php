<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use PHPUnit\Framework\TestCase;
use Verbanent\Uuid\Exceptions\AccessedUnsetUuidPropertyException;
use Verbanent\Uuid\Test\Example\BinaryId\CatIdModel;
use Verbanent\Uuid\Test\Example\BinaryId\CowIdModel;
use Verbanent\Uuid\Test\Example\BinaryId\DogIdModel;
use Verbanent\Uuid\Test\Example\BinaryId\HorseIdModel;
use Verbanent\Uuid\Test\Example\BinaryId\PigIdModel;
use Verbanent\Uuid\Test\MockTablesAndUuidsTrait;

class BinaryIdSupportableTraitTest extends TestCase
{
    use MockTablesAndUuidsTrait;

    public function testNotEmptyTable()
    {
        $cat = new CatIdModel();
        $cat->save();
        $uuid = $cat->uuid();
        $this->assertNotEmpty($cat->uuid());
        $this->assertEquals($uuid, $cat->find($uuid)->uuid());
    }

    public function testSetOwnUuidAsProperty()
    {
        $dog = new DogIdModel();
        $dog->id = $this->id;
        $dog->save();
        $this->assertEquals($this->id, $dog->uuid());
    }

    public function testSetOwnBinaryUuidAsProperty()
    {
        $cow = new CowIdModel();
        $cow->id = $this->binaryUuid;
        $cow->save();
        $this->assertEquals($this->id, $cow->uuid());
    }

    public function testFirstOrCreate()
    {
        $cow = CowIdModel::firstOrCreate(['id' => $this->id]);
        $this->assertNotEmpty($cow->uuid());
        $this->assertEquals($this->id, $cow->uuid());
    }

    public function testReadableUuid()
    {
        $pig = new PigIdModel();
        $pig->id = $this->id;
        $pig->save();
        $this->assertEquals($this->id, $pig->uuid());
    }

    public function testUuidNotSetButTryToGet()
    {
        $this->expectException(AccessedUnsetUuidPropertyException::class);
        $horse = new HorseIdModel();
        $this->assertNotNull($horse->uuid());
    }

    public function testEncodeUuid()
    {
        $binaryUuid = HorseIdModel::encodeUuid($this->id);
        $this->assertEquals($this->binaryUuid, $binaryUuid);
    }

    public function testDefaultUuidColumnFromConfig()
    {
        $previous = Container::getInstance();
        $container = new Container();
        $container->instance('config', new Repository([
            'binary-uuid' => ['default_column' => 'uuid'],
        ]));
        Container::setInstance($container);

        try {
            $model = new CatIdModel();
            $this->assertSame('uuid', $model->getUuidColumn());
        } finally {
            Container::setInstance($previous);
        }
    }
}
