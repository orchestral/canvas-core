<?php

namespace Orchestra\Canvas\Core;

trait CodeGenerator
{
    /**
     * Generate code.
     *
     * @param  bool   $force
     *
     * @return mixed
     */
    public function generateCode(bool $force)
    {
        return $this->resolveGeneratesCodeProcessor()(
            $this->generatorName(), $force
        );
    }

    /**
     * Code already exists.
     */
    public function codeAlreadyExists(string $className)
    {
        return false;
    }

    /**
     * Code successfully generated.
     */
    public function codeHasBeenGenerated(string $className)
    {
        return true;
    }

    /**
     * Get the default namespace for the class.
     */
    public function getDefaultNamespace(string $rootNamespace): string
    {
        return $rootNamespace;
    }

    /**
     * Generator options.
     */
    public function generatorOptions(): array
    {
        return [
            'name' => $this->generatorName(),
        ];
    }

    /**
     * Resolve generates code processor.
     */
    protected function resolveGeneratesCodeProcessor(): GeneratesCode
    {
        $class = \property_exists($this, 'processor')
            ? $this->processor
            : GeneratesCode::class;

        return new $class($this->preset, $this);
    }
}
