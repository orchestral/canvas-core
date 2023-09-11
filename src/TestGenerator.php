<?php

namespace Orchestra\Canvas\Core;

use Illuminate\Support\Str;

trait TestGenerator
{
    /**
     * Create the matching test case if requested.
     *
     * @param  string  $path
     * @return bool
     */
    protected function handleTestCreationUsingCanvas($path)
    {
        if (! $this->option('test') && ! $this->option('pest')) {
            return false;
        }

        return $this->callSilent('make:test', [
            'name' => Str::of($path)->after($this->preset->sourcePath())->beforeLast('.php')->append('Test')->replace('\\', '/'),
            '--pest' => $this->option('pest'),
        ]) == 0;
    }
}
