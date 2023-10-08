<?php

namespace Orchestra\Canvas\Core\Tests;

use Orchestra\Canvas\Core\PresetManager;
use Orchestra\Testbench\TestCase;

class PresetManagerTest extends TestCase
{
    /** @test */
    public function it_can_be_resolved()
    {
        $manager = $this->app[PresetManager::class];

        $this->assertSame('laravel', $manager->getDefaultDriver());
    }

    /** @test */
    public function it_can_override_default_driver()
    {
        $manager = $this->app[PresetManager::class];

        $this->assertSame('laravel', $manager->getDefaultDriver());

        $manager->setDefaultDriver('workbench');

        $this->assertSame('workbench', $manager->getDefaultDriver());
    }
}
