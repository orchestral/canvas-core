<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Concerns;

/**
 * @property string|null  $name
 * @property string|null  $description
 */
abstract class GeneratorCommand extends \Illuminate\Console\GeneratorCommand
{
    use Concerns\UsesGeneratorOverrides;

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

    /**
     * Get a list of possible model names.
     *
     * @return array<int, string>
     */
    protected function possibleModels()
    {
        return $this->possibleModelsUsingCanvas();
    }

    /**
     * Get a list of possible event names.
     *
     * @return array<int, string>
     */
    protected function possibleEvents()
    {
        return $this->possibleEventsUsingCanvas();
    }
}
