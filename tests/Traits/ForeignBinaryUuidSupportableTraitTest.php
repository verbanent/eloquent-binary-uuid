<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;
use Verbanent\Uuid\Test\Example\ForeignBinary\ChickenUuidModel;
use Verbanent\Uuid\Test\Example\ForeignBinary\DuckUuidModel;
use Verbanent\Uuid\Test\Example\ForeignBinary\MouseUuidModel;
use Verbanent\Uuid\Test\Example\ForeignBinary\RabbitUuidModel;
use Verbanent\Uuid\Test\Example\ForeignBinary\SnakeUuidModel;
use Verbanent\Uuid\Test\MockTablesAndUuidsTrait;

class ForeignBinaryUuidSupportableTraitTest extends TestCase
{
    use MockTablesAndUuidsTrait;

    public function testCreatingModelWithBinaryForeignUuid()
    {
        ChickenUuidModel::create([
            'uuid' => $this->uuid,
            'foreignUuid' => $this->binaryUuid,
        ]);

        $foundCollection = ChickenUuidModel::findByUuid('foreignUuid', $this->uuid);
        $this->assertTrue($foundCollection instanceof Collection);
        $this->assertEquals(1, count($foundCollection));
        $this->assertEquals($this->uuid, $foundCollection[0]->foreignUuid('foreignUuid'));
    }

    public function testCreatingModelWithStringForeignUuid()
    {
        $duck = new DuckUuidModel();
        $duck->uuid = $this->uuid;
        $duck->foreignUuid = $this->uuid;
        $duck->nonString = 3;
        $duck->save();

        $foundCollection = DuckUuidModel::findByUuid('foreignUuid', $this->uuid);
        $this->assertTrue($foundCollection instanceof Collection);
        $this->assertEquals(1, count($foundCollection));
        $this->assertEquals($this->uuid, $duck->foreignUuid('foreignUuid'));
    }

    public function testUuidableIsNotArray()
    {
        $mouse = new MouseUuidModel();
        $mouse->save();
        $this->assertIsString($mouse->uuid());
    }

    public function testReadableForeignUuid()
    {
        RabbitUuidModel::create([
            'uuid' => $this->uuid,
            'foreignUuid' => $this->uuid,
        ]);

        $rabbit = RabbitUuidModel::find($this->uuid);
        $this->assertIsString($rabbit->uuid());
        $this->assertEquals($this->uuid, $rabbit->uuid());
        $this->assertEquals($this->uuid, $rabbit->foreignUuid('foreignUuid'));
    }

    public function testInvalidUuidInUuidable()
    {
        $incorrectUuid = 'incorrect-uuid-string';
        $snake = new SnakeUuidModel();
        $snake->foreignUuid = $incorrectUuid;
        $snake->save();

        $this->assertEquals($incorrectUuid, $snake->foreignUuid);
    }
}
