<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use PHPUnit\Framework\TestCase;
use Verbanent\Uuid\Exceptions\AccessedUnsetUuidPropertyException;
use Verbanent\Uuid\Test\Example\Binary\CatUuidModel;
use Verbanent\Uuid\Test\Example\Binary\CowUuidModel;
use Verbanent\Uuid\Test\Example\Binary\DogUuidModel;
use Verbanent\Uuid\Test\Example\Binary\HorseUuidModel;
use Verbanent\Uuid\Test\Example\Binary\PigUuidModel;
use Verbanent\Uuid\Test\MockTablesAndUuidsTrait;

class BinaryUuidSupportableTraitTest extends TestCase
{
    use MockTablesAndUuidsTrait;

    public function testNotEmptyTable()
    {
        $cat = new CatUuidModel();
        $cat->save();
        $uuid = $cat->uuid();
        $this->assertNotEmpty($cat->uuid());
        $this->assertEquals($uuid, $cat->find($uuid)->uuid());
    }

    public function testSetOwnUuidAsProperty()
    {
        $dog = new DogUuidModel();
        $dog->uuid = $this->uuid;
        $dog->save();
        $this->assertEquals($this->uuid, $dog->uuid());
    }

    public function testSetOwnBinaryUuidAsProperty()
    {
        $cow = new CowUuidModel();
        $cow->uuid = $this->binaryUuid;
        $cow->save();
        $this->assertEquals($this->uuid, $cow->uuid());
    }

    public function testFirstOrCreate()
    {
        $cow = CowUuidModel::firstOrCreate(['uuid' => $this->uuid]);
        $this->assertNotEmpty($cow->uuid());
        $this->assertEquals($this->uuid, $cow->uuid());
    }

    public function testReadableUuid()
    {
        $pig = new PigUuidModel();
        $pig->uuid = $this->uuid;
        $pig->save();
        $this->assertEquals($this->uuid, $pig->uuid());
    }

    public function testUuidNotSetButTryToGet()
    {
        $this->expectException(AccessedUnsetUuidPropertyException::class);
        $horse = new HorseUuidModel();
        $this->assertNotNull($horse->uuid());
    }

    public function testEncodeUuid()
    {
        $binaryUuid = HorseUuidModel::encodeUuid($this->uuid);
        $this->assertEquals($this->binaryUuid, $binaryUuid);
    }
}
