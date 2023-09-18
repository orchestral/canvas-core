<?php

namespace Orchestra\Canvas\Core\Commands;

use Orchestra\Canvas\Core\Concerns;
use Orchestra\Canvas\Core\Contracts\GeneratesCode;

/**
 * @property string|null  $name
 * @property string|null  $description
 */
abstract class GeneratorCommand extends \Illuminate\Console\GeneratorCommand implements GeneratesCode
{
    use Concerns\CodeGenerator;
    use Concerns\CreatesUsingGeneratorPreset;
    use Concerns\TestGenerator;


    /**
     * Create a new controller creator command instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addGeneratorPresetOptions();
    }

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
        if ($this->isReservedName($name = $this->getNameInput())) {
            $this->components->error('The name "'.$name.'" is reserved by PHP.');

            return false;
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
