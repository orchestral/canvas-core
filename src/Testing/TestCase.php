<?php

namespace Orchestra\Canvas\Core\Testing;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use Concerns\InteractsWithPublishedFiles;

    /**
     * Stubs files.
     *
     * @var array
     */
    protected $files = [];

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
