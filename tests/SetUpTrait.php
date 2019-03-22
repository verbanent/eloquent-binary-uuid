<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SetUpTrait extends TestCase
{
    protected $uuid = '11e9469d-b942-b7e6-bc69-8df4f180e5a9';
    protected $binaryUuid;

    public function setUp(): void
    {
        $capsule = new Capsule();
        $capsule->addConnection([
            'driver'   => 'sqlite',
            'database' => ':memory:',
        ]);
        $capsule->setEventDispatcher(new Dispatcher(new Container()));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        Capsule::schema()->create('model_test', function ($table) {
            $table->uuid('uuid');
            $table->primary('uuid');
            $table->uuid('foreignUuid')->nullable();
            $table->integer('nonString')->nullable();
        });

        $this->binaryUuid = Uuid::fromString($this->uuid)->getBytes();
    }

    public function tearDown(): void
    {
        Capsule::schema()->dropAllTables();
    }
}
