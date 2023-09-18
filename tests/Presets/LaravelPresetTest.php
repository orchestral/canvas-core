<?php

namespace Orchestra\Canvas\Core\Tests;

use Orchestra\Canvas\Core\PresetManager;
use Orchestra\Canvas\Core\Presets\Laravel;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;

class LaravelPresetTest extends TestCase
{
    use WithWorkbench;

    /** @test */
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

    /** @test */
    public function it_available_as_default_driver()
    {
        $preset = $this->app[PresetManager::class]->driver();

        $this->assertInstanceOf(Laravel::class, $preset);
        $this->assertSame('laravel', $preset->name());
        $this->assertTrue($preset->is('laravel'));
    }
}
