<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Console\GeneratorCommand;
use Orchestra\Canvas\Core\CodeGenerator;
use Orchestra\Canvas\Core\TestGenerator;

/**
 * @property string|null  $name
 * @property string|null  $description
 */
abstract class Generator extends GeneratorCommand
{
    use CodeGenerator, TestGenerator;

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        // First we need to ensure that the given name is not a reserved word within the PHP
        // language and that the class name will actually be valid. If it is not valid we
        // can error now and prevent from polluting the filesystem using invalid files.
        if ($this->isReservedName($name = $this->generatorName())) {
            $this->components->error('The name "'.$name.'" is reserved by PHP.');

            return GeneratorCommand::FAILURE;
        }

        $force = $this->hasOption('force') && $this->option('force') === true;

        return $this->generateCode($force);
    }

    /**
     * Build the class with the given name.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->generatingCode(
            $this->replaceNamespace($stub, $name)->replaceClass($stub, $name), $name
        );
    }

    /**
     * Handle generating code.
     */
    public function generatingCode(string $stub, string $className): string
    {
        return $stub;
    }
}
