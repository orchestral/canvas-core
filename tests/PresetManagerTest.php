<?php

namespace Orchestra\Canvas\Core\Tests;

use Illuminate\Support\Manager;
use Orchestra\Canvas\Core\PresetManager;
use Orchestra\Testbench\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PresetManagerTest extends TestCase
{
    #[Test]
    public function it_can_be_resolved()
    {
        $manager = $this->app[PresetManager::class];

        $this->assertInstanceOf(Manager::class, $manager);
        $this->assertSame('laravel', $manager->getDefaultDriver());
    }

    #[Test]
    public function it_can_override_default_driver()
    {
        $manager = $this->app[PresetManager::class];

        $this->assertSame('laravel', $manager->getDefaultDriver());

        $manager->setDefaultDriver('workbench');

        $this->assertSame('workbench', $manager->getDefaultDriver());
    }
}
