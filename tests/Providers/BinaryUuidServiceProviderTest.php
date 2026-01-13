<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\MySqlConnection;
use Mockery;
use PHPUnit\Framework\TestCase;
use Verbanent\Uuid\Providers\BinaryUuidServiceProvider;

class BinaryUuidServiceProviderTest extends TestCase
{
    /**
     * BinaryUuidServiceProvider instance.
     *
     * @var BinaryUuidServiceProvider
     */
    private $binaryUuidServiceProvider;

    public function setUp(): void
    {
        Mockery::mock('overload:db')
            ->shouldReceive(['connection' => new MySqlConnection('')]);

        $app = Mockery::mock(Application::class);
        $app->shouldReceive('runningInConsole')->andReturn(false);

        $this->binaryUuidServiceProvider = new BinaryUuidServiceProvider($app);
    }

    public function testBoot()
    {
        $this->assertInstanceOf(BinaryUuidServiceProvider::class, $this->binaryUuidServiceProvider);
        $this->assertNull($this->binaryUuidServiceProvider->boot());
    }
}
