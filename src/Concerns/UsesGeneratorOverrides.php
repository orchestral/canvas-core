<?php

namespace Orchestra\Canvas\Core\Concerns;

use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

trait UsesGeneratorOverrides
{
    /**
     * Qualify the given model class base name.
     */
    protected function qualifyModelUsingCanvas(string $model): string
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        if (Str::startsWith($model, $this->rootNamespace())) {
            return $model;
        }

        return $this->generatorPreset()->modelNamespace().$model;
    }

    /**
     * Get the destination class path.
     */
    protected function getPathUsingCanvas(string $name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->getGeneratorSourcePath().'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the root namespace for the class.
     */
    protected function rootNamespaceUsingCanvas(): string
    {
        return $this->generatorPreset()->rootNamespace();
    }

    /**
     * Get the model for the default guard's user provider.
     */
    protected function userProviderModelUsingCanvas(?string $guard = null): ?string
    {
        return $this->generatorPreset()->userProviderModel($guard);
    }

    /**
     * Get the first view directory path from the application configuration.
     */
    protected function viewPathUsingCanvas(string $path = ''): string
    {
        $views = $this->generatorPreset()->viewPath();

        return $views.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get a list of possible model names.
     *
     * @return array<int, string>
     */
    protected function possibleModelsUsingCanvas(): array
    {
        $sourcePath = $this->generatorPreset()->sourcePath();

        $modelPath = is_dir("{$sourcePath}/Models") ? "{$sourcePath}/Models" : $sourcePath;

        return collect((new Finder)->files()->depth(0)->in($modelPath))
            ->map(fn ($file) => $file->getBasename('.php'))
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Get a list of possible event names.
     *
     * @return array<int, string>
     */
    protected function possibleEventsUsingCanvas(): array
    {
        $eventPath = sprintf('%s/Events', $this->generatorPreset()->sourcePath());

        if (! is_dir($eventPath)) {
            return [];
        }

        return collect((new Finder)->files()->depth(0)->in($eventPath))
            ->map(fn ($file) => $file->getBasename('.php'))
            ->sort()
            ->values()
            ->all();
    }
}
