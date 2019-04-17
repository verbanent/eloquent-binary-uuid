<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Verbanent\Uuid\Exceptions\AccessedUnsetUuidPropertyException;
use Verbanent\Uuid\Test\Example\Binary\CatModel;
use Verbanent\Uuid\Test\Example\Binary\CowModel;
use Verbanent\Uuid\Test\Example\Binary\DogModel;
use Verbanent\Uuid\Test\Example\Binary\HorseModel;
use Verbanent\Uuid\Test\Example\Binary\PigModel;
use Verbanent\Uuid\Test\SetUpTrait;

class BinaryUuidSupportableTraitTest extends SetUpTrait
{
    public function testNotEmptyTable()
    {
        $cat = new CatModel();
        $cat->save();
        $uuid = $cat->uuid();
        $this->assertNotEmpty($cat->uuid());
        $this->assertEquals($uuid, $cat->find($uuid)->uuid());
    }

    public function testSetOwnUuidAsProperty()
    {
        $dog = new DogModel();
        $dog->uuid = $this->uuid;
        $dog->save();
        $this->assertEquals($this->uuid, $dog->uuid());
    }

    public function testSetOwnBinaryUuidAsProperty()
    {
        $cow = new CowModel();
        $cow->uuid = $this->binaryUuid;
        $cow->save();
        $this->assertEquals($this->uuid, $cow->uuid());
    }

    public function testFirstOrCreate()
    {
        $cow = CowModel::firstOrCreate(['uuid' => $this->uuid]);
        $this->assertNotEmpty($cow->uuid());
        $this->assertEquals($this->uuid, $cow->uuid());
    }

    public function testReadableUuid()
    {
        $pig = new PigModel();
        $pig->uuid = $this->uuid;
        $pig->save();
        $this->assertEquals($this->uuid, $pig->uuid());
    }

    public function testUuidNotSetButTryToGet()
    {
        $this->expectException(AccessedUnsetUuidPropertyException::class);
        $horse = new HorseModel();
        $this->assertNotNull($horse->uuid());
    }

    public function testEncodeUuid()
    {
        $binaryUuid = HorseModel::encodeUuid($this->uuid);
        $this->assertEquals($this->binaryUuid, $binaryUuid);
    }
}
