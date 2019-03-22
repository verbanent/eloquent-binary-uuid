<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Traits;

use Illuminate\Database\Eloquent\Collection;
use Verbanent\Uuid\Test\Example\ForeignBinary\ChickenModel;
use Verbanent\Uuid\Test\Example\ForeignBinary\DuckModel;
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
}
