<?php

namespace Orchestra\Canvas\Core\Tests\Commands;

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;

class GeneratorCommandTest extends TestCase
{
    use InteractsWithPublishedFiles;
    use WithWorkbench;

    protected $files = [
        'app/Value/Foo.php',
    ];

    /** @test */
    public function it_can_generate_class_file()
    {
        $this->artisan('make:code', ['name' => 'Value/Foo'])
            ->assertSuccessful();

        $this->assertFileContains([
            'namespace App\Value;',
            'class Foo',
        ], 'app/Value/Foo.php');
    }
}
