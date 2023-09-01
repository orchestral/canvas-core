<?php

namespace Orchestra\Canvas\Core\Tests\Unit\Presets;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Presets\Laravel;
use PHPUnit\Framework\TestCase;

class LaravelTest extends TestCase
{
    /** @test */
    public function it_has_proper_signatures()
    {
        $directory = __DIR__;
        $preset = new Laravel([], $directory, $files = new Filesystem());

        $this->assertSame('laravel', $preset->name());
        $this->assertSame([], $preset->config());
        $this->assertTrue($preset->is('laravel'));
        $this->assertFalse($preset->is('package'));

        $this->assertSame($directory, $preset->basePath());

        $this->assertSame('App', $preset->rootNamespace());
        $this->assertSame('App\Models', $preset->modelNamespace());
        $this->assertSame('App\Providers', $preset->providerNamespace());
        $this->assertSame('Database\Factories', $preset->factoryNamespace());
        $this->assertSame('Database\Seeders', $preset->seederNamespace());

        $this->assertSame("{$directory}/app", $preset->sourcePath());
        $this->assertSame("{$directory}/resources", $preset->resourcePath());
        $this->assertSame("{$directory}/database/factories", $preset->factoryPath());
        $this->assertSame("{$directory}/database/migrations", $preset->migrationPath());
        $this->assertSame("{$directory}/database/seeders", $preset->seederPath());

        $this->assertTrue($preset->hasCustomStubPath());
        $this->assertSame("{$directory}/stubs", $preset->getCustomStubPath());

        $this->assertSame($files, $preset->filesystem());
    }

    /** @test */
    public function it_can_configure_model_namespace()
    {
        $directory = __DIR__;
        $preset = new Laravel(['namespace' => 'App', 'model' => ['namespace' => 'App\Model']], $directory, new Filesystem());

        $this->assertSame('App', $preset->rootNamespace());
        $this->assertSame('App\Model', $preset->modelNamespace());
        $this->assertSame('App\Providers', $preset->providerNamespace());
    }

    /** @test */
    public function it_can_configure_provider_namespace()
    {
        $directory = __DIR__;
        $preset = new Laravel(['namespace' => 'App', 'provider' => ['namespace' => 'App']], $directory, new Filesystem());

        $this->assertSame('App', $preset->rootNamespace());
        $this->assertSame('App\Models', $preset->modelNamespace());
        $this->assertSame('App', $preset->providerNamespace());
    }
}
