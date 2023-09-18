<?php

namespace Orchestra\Canvas\Core\Concerns;

use Illuminate\Support\Str;

trait TestGenerator
{
    use CreatesUsingGeneratorPreset;

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

        return $this->call('make:test', array_merge([
            'name' => Str::of($path)->after($sourcePath)->beforeLast('.php')->append('Test')->replace('\\', '/'),
            '--pest' => $this->option('pest'),
        ], array_filter([
            '--preset' => $this->hasOption('preset') ? $this->option('preset') : null,
        ])) == 0;
    }
}
