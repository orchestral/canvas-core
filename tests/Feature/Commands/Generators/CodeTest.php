<?php

namespace Orchestra\Canvas\Tests\Feature\Commands\Generators;

use Illuminate\Console\Application as Artisan;
use Orchestra\Canvas\Core\Commands\Generators;
use Orchestra\Canvas\Core\Presets\Laravel;
use Orchestra\Canvas\Core\Testing\TestCase;
use Orchestra\Testbench\Concerns\WithWorkbench;

class CodeTest extends TestCase
{
    use WithWorkbench;

    protected $files = [
        'app/Value/Foo.php',
    ];

    /** @test */
    public function it_can_generate_class_file()
    {
        $preset = new Laravel(
            ['namespace' => 'App', 'generators' => [Generators\Code::class]], $this->app->basePath(), $this->filesystem
        );

        $this->instance('orchestra.canvas', $preset);

        Artisan::starting(fn ($artisan) => $preset->addAdditionalCommands($artisan));

        $this->artisan('make:class', ['name' => 'Value/Foo'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Value;',
            'class Foo',
        ], 'app/Value/Foo.php');
    }

    /** @test */
    public function it_cant_generate_class_file()
    {
        $this->expectException('Symfony\Component\Console\Exception\CommandNotFoundException');
        $this->expectExceptionMessage('The command "make:class" does not exist.');

        $this->artisan('make:class', ['name' => 'Foo'])
            ->assertExitCode(0);

        $this->assertFileContains([
            'namespace App\Value;',
            'class Foo',
        ], 'app/Value/Foo.php');
    }
}
