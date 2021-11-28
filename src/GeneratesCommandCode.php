<?php

namespace Orchestra\Canvas\Core;

/**
 * @see https://github.com/laravel/framework/blob/8.x/src/Illuminate/Foundation/Console/ConsoleMakeCommand.php
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

        return str_replace(['dummy:command', '{{ command }}', '{{command}}'], $this->options['command'], $stub);
    }
}
