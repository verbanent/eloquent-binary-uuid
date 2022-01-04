<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Illuminate\Database\Eloquent\Collection;
use Verbanent\Uuid\Test\Example\ForeignBinaryId\ChickenIdModel;
use Verbanent\Uuid\Test\Example\ForeignBinaryId\DuckIdModel;
use Verbanent\Uuid\Test\Example\ForeignBinaryId\MouseIdModel;
use Verbanent\Uuid\Test\Example\ForeignBinaryId\RabbitIdModel;
use Verbanent\Uuid\Test\Example\ForeignBinaryId\SnakeIdModel;
use Verbanent\Uuid\Test\SetUpTrait;

class ForeignBinaryIdSupportableTraitTest extends SetUpTrait
{
    public function testCreatingModelWithBinaryForeignUuid()
    {
        ChickenIdModel::create([
            'id'        => $this->uuid,
            'foreignUuid' => $this->binaryUuid,
        ]);

        $foundCollection = ChickenIdModel::findByUuid('foreignUuid', $this->uuid);
        $this->assertTrue($foundCollection instanceof Collection);
        $this->assertEquals(1, count($foundCollection));
        $this->assertEquals($this->uuid, $foundCollection[0]->foreignUuid('foreignUuid'));
    }

    public function testCreatingModelWithStringForeignUuid()
    {
        $duck = new DuckIdModel();
        $duck->id = $this->id;
        $duck->foreignUuid = $this->uuid;
        $duck->nonString = 3;
        $duck->save();

        $foundCollection = DuckIdModel::findByUuid('foreignUuid', $this->uuid);
        $this->assertTrue($foundCollection instanceof Collection);
        $this->assertEquals(1, count($foundCollection));
        $this->assertEquals($this->uuid, $duck->foreignUuid('foreignUuid'));
    }

    public function testUuidableIsNotArray()
    {
        $mouse = new MouseIdModel();
        $mouse->save();
        $this->assertIsString($mouse->uuid());
    }

    public function testReadableForeignUuid()
    {
        RabbitIdModel::create([
            'id'        => $this->uuid,
            'foreignUuid' => $this->uuid,
        ]);

        $rabbit = RabbitIdModel::find($this->uuid);
        $this->assertIsString($rabbit->uuid());
        $this->assertEquals($this->uuid, $rabbit->uuid());
        $this->assertEquals($this->uuid, $rabbit->foreignUuid('foreignUuid'));
    }

    public function testInvalidUuidInUuidable()
    {
        $incorrectUuid = 'incorrect-uuid-string';
        $snake = new SnakeIdModel();
        $snake->foreignUuid = $incorrectUuid;
        $snake->save();

        $this->assertEquals($incorrectUuid, $snake->foreignUuid);
    }
}
