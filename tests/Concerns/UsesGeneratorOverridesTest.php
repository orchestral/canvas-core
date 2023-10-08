<?php

namespace Orchestra\Canvas\Core\Tests\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Orchestra\Canvas\Core\Concerns\UsesGeneratorOverrides;
use Orchestra\Canvas\Core\PresetManager;
use Orchestra\Canvas\Core\Presets\Preset;
use Orchestra\Testbench\TestCase;

class UsesGeneratorOverridesTest extends TestCase
{
    /** @test */
    public function it_can_get_qualify_model_class()
    {
        $filesystem = $this->app['files'];

        $filesystem->ensureDirectoryExists(base_path('app/Models'));

        $stub = new UsesGeneratorOverridesTestStub();

        $this->assertSame([
            'user-model' => 'App\Models\User',
            'welcome-view' => $this->app->joinPaths(base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'welcome.blade.php')),
        ], $stub->toArray());
    }
}

class UsesGeneratorOverridesTestStub implements Arrayable
{
    use UsesGeneratorOverrides;

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->rootNamespaceUsingCanvas();
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray()
    {
        return [
            'user-model' => $this->qualifyModelUsingCanvas('User'),
            'welcome-view' => $this->viewPathUsingCanvas('welcome.blade.php'),
        ];
    }

    /**
     * Resolve the generator preset.
     */
    protected function generatorPreset(): Preset
    {
        return app(PresetManager::class)->driver('laravel');
    }
}
