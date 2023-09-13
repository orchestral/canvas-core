<?php

namespace Orchestra\Canvas\Tests\Feature\Commands\Generators;

use Illuminate\Console\Application as Artisan;
use Orchestra\Canvas\Core\Commands\Generators;
use Orchestra\Canvas\Core\Presets\Laravel;
use Orchestra\Canvas\Core\Testing\TestCase;
use Orchestra\Testbench\Concerns\WithWorkbench;

class ConsoleGeneratorTest extends TestCase
{
    use WithWorkbench;

    protected $files = [
        'app/Console/Commands/FooCommand.php',
    ];

    /** @test */
    public function it_can_generate_command_file()
    {
        $preset = new Laravel(
            ['namespace' => 'App', 'generators' => [Generators\ConsoleGenerator::class]], $this->app->basePath(), $this->filesystem
        );

        $this->instance('orchestra.canvas', $preset);

        Artisan::starting(fn ($artisan) => $preset->addAdditionalCommands($artisan));

        $this->artisan('make:generator', ['name' => 'FooCommand'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Console\Commands;',
            'use Orchestra\Canvas\Commands\Generator;',
            'use Symfony\Component\Console\Attribute\AsCommand;',
            '#[AsCommand(name: \'make:name\', description: \'Create a new class\')]',
            'class FooCommand extends Generator',
        ], 'app/Console/Commands/FooCommand.php');
    }

    /** @test */
    public function it_can_generate_command_file_with_command_name()
    {
        $preset = new Laravel(
            ['namespace' => 'App', 'generators' => [Generators\ConsoleGenerator::class]], $this->app->basePath(), $this->filesystem
        );

        $this->instance('orchestra.canvas', $preset);

        Artisan::starting(fn ($artisan) => $preset->addAdditionalCommands($artisan));

        $this->artisan('make:generator', ['name' => 'FooCommand', '--command' => 'make:foobar'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Console\Commands;',
            'use Orchestra\Canvas\Commands\Generator;',
            'use Symfony\Component\Console\Attribute\AsCommand;',
            '#[AsCommand(name: \'make:foobar\', description: \'Create a new class\')]',
            'class FooCommand extends Generator',
        ], 'app/Console/Commands/FooCommand.php');
    }

    /** @test */
    public function it_can_generate_command_file_with_command_name_without_make_prefix()
    {
        $preset = new Laravel(
            ['namespace' => 'App', 'generators' => [Generators\ConsoleGenerator::class]], $this->app->basePath(), $this->filesystem
        );

        $this->instance('orchestra.canvas', $preset);

        Artisan::starting(fn ($artisan) => $preset->addAdditionalCommands($artisan));

        $this->artisan('make:generator', ['name' => 'FooCommand', '--command' => 'foobar'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Console\Commands;',
            'use Orchestra\Canvas\Commands\Generator;',
            'use Symfony\Component\Console\Attribute\AsCommand;',
            '#[AsCommand(name: \'make:foobar\', description: \'Create a new class\')]',
            'class FooCommand extends Generator',
        ], 'app/Console/Commands/FooCommand.php');
    }
}
