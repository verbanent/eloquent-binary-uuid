<?php

declare(strict_types=1);

namespace Verbanent\Uuid\Test\Providers;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\MySqlConnection;
use Illuminate\Support\ServiceProvider;
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

    public function testRegisterMergesConfig()
    {
        $config = new Repository([]);
        $app = Mockery::mock(Application::class);
        $app->shouldReceive('make')->with('config')->andReturn($config);

        $provider = new BinaryUuidServiceProvider($app);
        $provider->register();

        $this->assertSame('id', $config->get('binary-uuid.default_column'));
    }

    public function testBootPublishesConfigWhenRunningInConsole()
    {
        $app = Mockery::mock(Application::class);
        $app->shouldReceive('runningInConsole')->andReturn(true);
        $app->shouldReceive('configPath')->with('binary-uuid.php')->andReturn('/tmp/binary-uuid.php');

        $provider = new BinaryUuidServiceProvider($app);
        $provider->boot();

        $paths = ServiceProvider::pathsToPublish(
            BinaryUuidServiceProvider::class,
            'binary-uuid-config'
        );

        $this->assertNotEmpty($paths);
        $this->assertTrue(in_array('/tmp/binary-uuid.php', array_values($paths), true));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        ServiceProvider::$publishes = [];
        ServiceProvider::$publishGroups = [];
    }
}
