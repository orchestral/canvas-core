<?php

namespace Orchestra\Canvas\Core\Concerns;

use Illuminate\Support\Str;

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
}
