<?php

namespace Orchestra\Canvas\Core;

use Illuminate\Support\Str;

/**
 * @see https://github.com/laravel/framework/blob/10.x/src/Illuminate/Foundation/Console/ConsoleMakeCommand.php
 */
class GeneratesCommandCode extends GeneratesCode
{
    /**
     * Replace the class name for the given stub.
     *
     * @todo need to be updated
     */
    protected function replaceClass(string $stub, string $name): string
    {
        $stub = parent::replaceClass($stub, $name);

        $command = $this->options['command'] ?: 'app:'.Str::of($name)->classBasename()->kebab()->value();

        return str_replace(['dummy:command', '{{ command }}', '{{command}}'], $command, $stub);
    }
}
