<?php

namespace Orchestra\Canvas\Core\Testing;

use Composer\InstalledVersions;
use Composer\Semver\VersionParser;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use Concerns\InteractsWithPublishedFiles;

    /**
     * Stubs files.
     *
     * @var array<int, string>|null
     */
    protected $files = [];

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (InstalledVersions::satisfies(new VersionParser, 'orchestra/testbench-core', '<8.2.0')) {
            $this->setUpInteractsWithPublishedFiles();
        }
    }

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        if (InstalledVersions::satisfies(new VersionParser, 'orchestra/testbench-core', '<8.2.0')) {
            $this->tearDownInteractsWithPublishedFiles();
        }

        parent::tearDown();
    }
}
