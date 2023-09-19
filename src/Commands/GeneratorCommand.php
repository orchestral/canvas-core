<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Concerns;
use Orchestra\Canvas\Core\Contracts\GeneratesCode;

/**
 * @property string|null  $name
 * @property string|null  $description
 */
abstract class GeneratorCommand extends \Illuminate\Console\GeneratorCommand implements GeneratesCode
{
    use Concerns\CodeGenerator;
    use Concerns\TestGenerator;
    use Concerns\UsesGeneratorOverrides;

    /**
     * Create a new controller creator command instance.
     *
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
        return $this->generateCode() ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->getPathUsingCanvas($name);
    }

    /**
     * Qualify the given model class base name.
     *
     * @return string
     */
    protected function qualifyModel(string $model)
    {
        return $this->qualifyModelUsingCanvas($model);
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->rootNamespaceUsingCanvas();
    }

    /**
     * Get the model for the default guard's user provider.
     *
     * @return string|null
     */
    protected function userProviderModel()
    {
        return $this->userProviderModelUsingCanvas();
    }

    /**
     * Get the first view directory path from the application configuration.
     *
     * @return string
     */
    protected function viewPath($path = '')
    {
        return $this->viewPathUsingCanvas($path);
    }
}
