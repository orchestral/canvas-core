<?php

namespace Orchestra\Canvas\Core;

/**
 * @see https://github.com/laravel/framework/blob/8.x/src/Illuminate/Foundation/Console/ConsoleMakeCommand.php
 */
class GeneratesCommandCode extends GeneratesCode
{
    /**
     * Handle generating code.
     */
    protected function generatingCode(string $stub, string $name): string
    {
        $stub = parent::generatingCode($stub, $name);

        return str_replace(['dummy:command', '{{ command }}', '{{command}}'], $this->options['command'], $stub);
    }
}
