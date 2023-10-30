<?php

namespace Orchestra\Canvas\Core\Tests\Concerns;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Concerns\UsesGeneratorOverrides;
use Orchestra\Canvas\Core\PresetManager;
use Orchestra\Canvas\Core\Presets\Preset;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UsesGeneratorOverridesTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        $filesystem = new Filesystem();

        $this->afterApplicationCreated(static function () use ($filesystem) {
            $filesystem->ensureDirectoryExists(base_path('app/Events'));
            $filesystem->ensureDirectoryExists(base_path('app/Models'));
        });

        $this->beforeApplicationDestroyed(static function () use ($filesystem) {
            $filesystem->deleteDirectory(base_path('app/Events'));
            $filesystem->deleteDirectory(base_path('app/Models'));
        });

        parent::setUp();
    }

    #[Test]
    public function it_can_get_qualify_model_class()
    {
        $stub = new UsesGeneratorOverridesTestStub();

        $this->assertSame([
            'user-model' => 'App\Models\User',
            'welcome-view' => $this->app->joinPaths(base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'welcome.blade.php')),
            'possible-models' => [],
            'possible-events' => [],
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
            'possible-models' => $this->possibleModelsUsingCanvas(),
            'possible-events' => $this->possibleEventsUsingCanvas(),
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
