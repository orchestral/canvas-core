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
     * @return int
     */
    public function generateCode(bool $force = false)
    {
        return $this->resolveGeneratesCodeProcessor()($force);
    }

    /**
     * Code already exists.
     *
     * @return int
     */
    public function codeAlreadyExists(string $className)
    {
        $this->components->error(sprintf('%s [%s] already exists!', $this->type, $className));

        return 1;
    }

    /**
     * Code successfully generated.
     *
     * @return int
     */
    public function codeHasBeenGenerated(string $className)
    {
        $this->components->info(sprintf('%s [%s] created successfully.', $this->type, $className));

        return 0;
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
     *
     * @return array{name: string}
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
        /** @var \Orchestra\Canvas\Core\GeneratesCode $class */
        $class = property_exists($this, 'processor')
            ? $this->processor
            : GeneratesCode::class;

        return new $class($this->preset, $this);
    }
}
