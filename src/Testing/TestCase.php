<?php

namespace Orchestra\Canvas\Core\Testing;

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use InteractsWithPublishedFiles;

    /**
     * Stubs files.
     *
     * @var array<int, string>|null
     */
    protected $files = [];
}
