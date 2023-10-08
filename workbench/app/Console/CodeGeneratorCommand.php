<?php

namespace Workbench\App\Console;

use Orchestra\Canvas\Core\Commands\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class CodeGeneratorCommand extends GeneratorCommand
{
    /**
     * The name of the console command.
     *
     * @var string
     */
    protected $name = 'make:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate custom class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/class.stub';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the cast already exists'],
        ];
    }
}
