<?php

namespace Orchestra\Canvas\Core\Tests;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\PresetManager;
use Orchestra\Canvas\Core\Presets\Laravel;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

use function Illuminate\Filesystem\join_paths;

class LaravelPresetTest extends TestCase
{
    use WithWorkbench;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        $filesystem = new Filesystem();

        $this->afterApplicationCreated(static function () use ($filesystem) {
            $filesystem->ensureDirectoryExists(join_paths(base_path('app'), 'Events'));
            $filesystem->ensureDirectoryExists(join_paths(base_path('app'), 'Models'));
        });

        $this->beforeApplicationDestroyed(static function () use ($filesystem) {
            $filesystem->deleteDirectory(join_paths(base_path('app'), 'Events'));
            $filesystem->deleteDirectory(join_paths(base_path('app'), 'Models'));
        });

        parent::setUp();
    }

    #[Test]
    public function it_can_be_resolved()
    {
        $preset = $this->app[PresetManager::class]->driver('laravel');

        $this->assertInstanceOf(Laravel::class, $preset);
        $this->assertSame('laravel', $preset->name());
        $this->assertTrue($preset->is('laravel'));

        $this->assertSame($this->app->basePath(), $preset->basePath());
        $this->assertSame($this->app->basePath('app'), $preset->sourcePath());
        $this->assertSame($this->app->basePath('tests'), $preset->testingPath());
        $this->assertSame($this->app->resourcePath(), $preset->resourcePath());
        $this->assertSame($this->app->resourcePath('views'), $preset->viewPath());
        $this->assertSame($this->app->databasePath('factories'), $preset->factoryPath());
        $this->assertSame($this->app->databasePath('migrations'), $preset->migrationPath());
        $this->assertSame($this->app->databasePath('seeders'), $preset->seederPath());

        $this->assertSame($this->app->getNamespace(), $preset->rootNamespace());
        $this->assertSame($this->app->getNamespace().'Console\Commands\\', $preset->commandNamespace());
        $this->assertSame($this->app->getNamespace().'Models\\', $preset->modelNamespace());
        $this->assertSame($this->app->getNamespace().'Providers\\', $preset->providerNamespace());
        $this->assertSame('Database\Factories\\', $preset->factoryNamespace());
        $this->assertSame('Database\Seeders\\', $preset->seederNamespace());
        $this->assertSame('Tests\\', $preset->testingNamespace());

        $this->assertTrue($preset->hasCustomStubPath());
        $this->assertSame('Illuminate\Foundation\Auth\User', $preset->userProviderModel());
    }

    #[Test]
    public function it_available_as_default_driver()
    {
        $preset = $this->app[PresetManager::class]->driver();

        $this->assertInstanceOf(Laravel::class, $preset);
        $this->assertSame('laravel', $preset->name());
        $this->assertTrue($preset->is('laravel'));
    }
}
