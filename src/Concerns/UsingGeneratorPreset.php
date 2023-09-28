<?php

namespace Orchestra\Canvas\Core\Concerns;

use Illuminate\Console\Concerns\CreatesUsingGeneratorPreset;

trait UsingGeneratorPreset
{
    use CreatesUsingGeneratorPreset;

    /**
     * Get the generator preset source path.
     */
    protected function getGeneratorSourcePath(): string
    {
        return $this->generatorPreset()->sourcePath();
    }
}
