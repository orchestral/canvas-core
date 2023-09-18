<?php

namespace Orchestra\Canvas\Core\Concerns;

use Illuminate\Support\Str;

trait TestGenerator
{
    /**
     * Create the matching test case if requested.
     */
    protected function handleTestCreationUsingCanvas(string $path): bool
    {
        if (! $this->option('test') && ! $this->option('pest')) {
            return false;
        }

        $sourcePath = \in_array(CreatesUsingGeneratorPreset::class, class_uses_recursive($this))
            ? $this->generatorPreset()->sourcePath()
            : $this->laravel['path'];

        return $this->call('make:test', [
            'name' => Str::of($path)->after($sourcePath)->beforeLast('.php')->append('Test')->replace('\\', '/'),
            '--pest' => $this->option('pest'),
        ]) == 0;
    }
}
