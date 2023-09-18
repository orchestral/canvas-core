<?php

namespace Orchestra\Canvas\Core\Concerns;

trait UsesGeneratorOverrides
{
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
    protected function userProviderModelUsingCanvas(): ?string
    {
        return $this->generatorPreset()->userProviderModel();
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
