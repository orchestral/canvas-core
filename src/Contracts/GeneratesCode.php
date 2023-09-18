<?php

namespace Orchestra\Canvas\Core\Contracts;

interface GeneratesCode
{
    /**
     * Code already exists.
     */
    public function codeAlreadyExists(string $className): bool;

    /**
     * Handle generating code.
     */
    public function generatingCode(string $stub, string $className): string;

    /**
     * Code successfully generated.
     */
    public function codeHasBeenGenerated(string $className): bool;

    /**
     * Run after code successfully generated.
     *
     * @return void
     */
    public function afterCodeHasBeenGenerated(string $className, string $path);
}
