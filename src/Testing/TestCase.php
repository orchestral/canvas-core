<?php

namespace Orchestra\Canvas\Core\Testing;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PHPUnit\Framework\Assert;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use Concerns\InteractsWithPublishedFiles;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpInteractsWithPublishedFiles();
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        $this->tearDownInteractsWithPublishedFiles();

        parent::tearDown();
    }
}
