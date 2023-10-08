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

    /** @test */
    public function it_cannot_generate_class_file_given_reserved_name()
    {
        $this->artisan('make:code', ['name' => '__halt_compiler'])
            ->expectsOutputToContain('The name "__halt_compiler" is reserved by PHP.')
            ->assertFailed();
    }

    /** @test */
    public function it_cannot_generate_class_file_when_file_already_exist()
    {
        file_put_contents(base_path('app/Value/Foo.php'), '<?php '.PHP_EOL);

        $this->artisan('make:code', ['name' => 'Value/Foo'])
            ->expectsOutputToContain('class [app/Value/Foo.php] already exists!')
            ->assertFailed();
    }

    /** @test */
    public function it_can_generate_class_file_when_file_already_exist_using_force_option()
    {
        file_put_contents(base_path('app/Value/Foo.php'), '<?php '.PHP_EOL);

        $this->artisan('make:code', ['name' => 'Value/Foo', '--force' => true])
            ->assertSuccessful();

        $this->assertFileContains([
            'namespace App\Value;',
            'class Foo',
        ], 'app/Value/Foo.php');
    }
}
