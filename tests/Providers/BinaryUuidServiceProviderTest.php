<?php

namespace Verbanent\Uuid\Test\Providers;

use Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\MySqlConnection;
use Illuminate\Contracts\Foundation\Application;
use Verbanent\Uuid\Providers\BinaryUuidServiceProvider;

class BinaryUuidServiceProviderTest extends TestCase
{
    /**
     * BinaryUuidServiceProvider instance.
     *
     * @var \Verbanent\Uuid\Providers\BinaryUuidServiceProvider
     */
    private $binaryUuidServiceProvider;

    public function setUp(): void
    {
        $dbMock= Mockery::mock('overload:' . DB::class);
        $dbMock->shouldReceive(['connection' => new MySqlConnection('')]);

        $app = Mockery::mock(Application::class);
        $this->binaryUuidServiceProvider = new BinaryUuidServiceProvider($app);
    }

    public function testBoot()
    {
        $this->assertInstanceOf(BinaryUuidServiceProvider::class, $this->binaryUuidServiceProvider);
        $this->assertNull($this->binaryUuidServiceProvider->boot());
    }
}
