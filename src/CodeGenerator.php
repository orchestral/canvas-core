<?php

namespace Orchestra\Canvas\Core;

trait CodeGenerator
{
    /**
     * Canvas preset.
     *
     * @var \Orchestra\Canvas\Core\Presets\Preset
     */
    protected $preset;

    /**
     * Set Preset for generator.
     *
     * @param  \Orchestra\Canvas\Core\Presets\Preset  $preset
     *
     * @return $this
     */
    public function setPreset(Presets\Preset $preset)
    {
        $this->preset = $preset;

        return $this;
    }

    /**
     * Generate code.
     *
     * @return mixed
     */
    public function generateCode(bool $force = false)
    {
        return $this->resolveGeneratesCodeProcessor()($force);
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
        $class = property_exists($this, 'processor')
            ? $this->processor
            : GeneratesCode::class;

        return new $class($this->preset, $this);
    }
}
