<?php

namespace Orchestra\Canvas\Core\Commands;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Canvas\Core\Concerns;
use Orchestra\Canvas\Core\Contracts\GeneratesCode;

/**
 * @property string|null $name
 * @property string|null $description
 */
abstract class GeneratorCommand extends \Illuminate\Console\GeneratorCommand implements GeneratesCode
{
    use Concerns\CodeGenerator;
    use Concerns\TestGenerator;
    use Concerns\UsesGeneratorOverrides;

    /** {@inheritDoc} */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);

        $this->addGeneratorPresetOptions();
    }

    /** {@inheritDoc} */
    #[\Override]
    public function handle()
    {
        /** @phpstan-ignore return.type */
        return $this->generateCode() ? self::SUCCESS : self::FAILURE;
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function getPath($name)
    {
        return $this->getPathUsingCanvas($name);
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function qualifyModel(string $model)
    {
        return $this->qualifyModelUsingCanvas($model);
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function rootNamespace()
    {
        return $this->rootNamespaceUsingCanvas();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function userProviderModel()
    {
        return $this->userProviderModelUsingCanvas();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function viewPath($path = '')
    {
        return $this->viewPathUsingCanvas($path);
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function possibleModels()
    {
        return $this->possibleModelsUsingCanvas();
    }

    /** {@inheritDoc} */
    #[\Override]
    protected function possibleEvents()
    {
        return $this->possibleEventsUsingCanvas();
    }
}
