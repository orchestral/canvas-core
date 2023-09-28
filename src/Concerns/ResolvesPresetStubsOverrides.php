<?php

namespace Orchestra\Canvas\Core\Concerns;

trait ResolvesPresetStubsOverrides
{
    /**
     * Resolve the fully-qualified path to the stub.
     */
    protected function resolveStubPathUsingCanvas(string $stub): string
    {
        $preset = $this->generatorPreset();

        return $preset->hasCustomStubPath() && file_exists($customPath = implode('/', [$preset->basePath(), trim($stub, '/')]))
            ? $customPath
            : $this->resolveDefaultStubPath($stub);
    }

    /**
     * Resolve the default fully-qualified path to the stub.
     *
     * @param  string  $stub
     * @return string
     */
    abstract protected function resolveDefaultStubPath($stub);
}
