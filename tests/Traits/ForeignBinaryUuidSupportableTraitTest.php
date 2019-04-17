<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Illuminate\Database\Eloquent\Collection;
use Verbanent\Uuid\Test\Example\ForeignBinary\ChickenModel;
use Verbanent\Uuid\Test\Example\ForeignBinary\DuckModel;
use Verbanent\Uuid\Test\Example\ForeignBinary\MouseModel;
use Verbanent\Uuid\Test\Example\ForeignBinary\RabbitModel;
use Verbanent\Uuid\Test\Example\ForeignBinary\SnakeModel;
use Verbanent\Uuid\Test\SetUpTrait;

class ForeignBinaryUuidSupportableTraitTest extends SetUpTrait
{
    public function testCreatingModelWithBinaryForeignUuid()
    {
        ChickenModel::create([
            'uuid'        => $this->uuid,
            'foreignUuid' => $this->binaryUuid,
        ]);

        $foundCollection = ChickenModel::findByUuid('foreignUuid', $this->uuid);
        $this->assertTrue($foundCollection instanceof Collection);
        $this->assertEquals(1, count($foundCollection));
        $this->assertEquals($this->uuid, $foundCollection[0]->foreignUuid('foreignUuid'));
    }

    public function testCreatingModelWithStringForeignUuid()
    {
        $duck = new DuckModel();
        $duck->uuid = $this->uuid;
        $duck->foreignUuid = $this->uuid;
        $duck->nonString = 3;
        $duck->save();

        $foundCollection = DuckModel::findByUuid('foreignUuid', $this->uuid);
        $this->assertTrue($foundCollection instanceof Collection);
        $this->assertEquals(1, count($foundCollection));
        $this->assertEquals($this->uuid, $duck->foreignUuid('foreignUuid'));
    }

    public function testUuidableIsNotArray()
    {
        $mouse = new MouseModel();
        $mouse->save();
        $this->assertIsString($mouse->uuid());
    }

    public function testReadableForeignUuid()
    {
        RabbitModel::create([
            'uuid'        => $this->uuid,
            'foreignUuid' => $this->uuid,
        ]);

        $rabbit = RabbitModel::find($this->uuid);
        $this->assertIsString($rabbit->uuid());
        $this->assertEquals($this->uuid, $rabbit->uuid());
        $this->assertEquals($this->uuid, $rabbit->foreignUuid('foreignUuid'));
    }

    public function testInvalidUuidInUuidable()
    {
        $incorrectUuid = 'incorrect-uuid-string';
        $snake = new SnakeModel();
        $snake->foreignUuid = $incorrectUuid;
        $snake->save();

        $this->assertEquals($incorrectUuid, $snake->foreignUuid);
    }
}
