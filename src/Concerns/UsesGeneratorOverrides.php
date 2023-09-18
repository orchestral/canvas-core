<?php

namespace Orchestra\Canvas\Core\Concerns;

use Illuminate\Support\Str;

trait UsesGeneratorOverrides
{
    /**
     * Get the destination class path.
     */
    protected function getPath(string $name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->getGeneratorSourcePath().'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the root namespace for the class.
     */
    protected function rootNamespace(): string
    {
        return $this->generatorPreset()->rootNamespace();
    }

    /**
     * Get the model for the default guard's user provider.
     */
    protected function userProviderModel(): ?string
    {
        return $this->generatorPreset()->userProviderModel();
    }

    /**
     * Get the first view directory path from the application configuration.
     */
    protected function viewPath(string $path = ''): string
    {
        $views = $this->generatorPreset()->viewPath();

        return $views.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
