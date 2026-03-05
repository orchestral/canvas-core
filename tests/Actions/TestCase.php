<?php

namespace Orchestra\Canvas\Core\Tests\Actions;

use Orchestra\Canvas\Core\LaravelServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /** {@inheritDoc} */
    #[\Override]
    protected function getPackageProviders($app)
    {
        return [
            LaravelServiceProvider::class,
        ];
    }
}
